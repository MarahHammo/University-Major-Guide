<?php

namespace App\Services;

use App\Models\Major;
use App\Models\Recommendation;
use App\Models\Student;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RecommendationService
{
    public function __construct(
        private GeminiService $geminiService
    ) {}

    /**
     * Generate recommendations for a student
     */
    public function generateRecommendations(Student $student): void
    {
        // حذف التوصيات القديمة
        $student->recommendations()->delete();

        // جلب كل التخصصات
        $majors = Major::all();

        if ($majors->isEmpty()) {
            Log::warning('No majors found in database', [
                'student_id' => $student->id
            ]);
            return;
        }

        // محاولة استخدام Gemini أولاً
        try {
            $aiRecommendations = $this->geminiService->generateSmartRecommendations($student, $majors);

            if (!empty($aiRecommendations)) {
                $this->saveAIRecommendations($student, $majors, $aiRecommendations);
                Log::info('AI recommendations generated successfully', [
                    'student_id' => $student->id,
                    'count' => count($aiRecommendations)
                ]);
            } else {
                // Fallback: خوارزمية تقليدية
                Log::info('Falling back to traditional algorithm', [
                    'student_id' => $student->id
                ]);
                $this->saveFallbackRecommendations($student, $majors);
            }
        } catch (\Exception $e) {
            Log::error('Error generating AI recommendations', [
                'student_id' => $student->id,
                'error' => $e->getMessage()
            ]);
            // Fallback: خوارزمية تقليدية
            $this->saveFallbackRecommendations($student, $majors);
        }

        // Clear cache for this student
        Cache::forget("recommendations_{$student->id}");
    }

    /**
     * Save AI-generated recommendations
     */
    private function saveAIRecommendations(Student $student, $majors, array $aiRecommendations): void
    {
        foreach ($aiRecommendations as $rec) {
            try {
                $major = $majors->where('name_ar', $rec['major_name_ar'])->first();

                if (!$major) {
                    Log::warning('Major not found for AI recommendation', [
                        'major_name' => $rec['major_name_ar'],
                        'student_id' => $student->id
                    ]);
                    continue;
                }

                Recommendation::create([
                    'student_id' => $student->id,
                    'major_id' => $major->id,
                    'match_score' => (float) ($rec['match_score'] ?? 0),
                    'factors' => [
                        'strengths' => $rec['strengths'] ?? [],
                        'challenges' => $rec['challenges'] ?? [],
                    ],
                    'reasoning_ar' => $rec['reasoning'] ?? '',
                    'reasoning_en' => $rec['reasoning_en'] ?? '',
                ]);
            } catch (\Exception $e) {
                Log::error('Error saving AI recommendation', [
                    'error' => $e->getMessage(),
                    'major_name' => $rec['major_name_ar'] ?? 'unknown',
                    'student_id' => $student->id
                ]);
            }
        }
    }

    /**
     * Save fallback recommendations using traditional algorithm
     */
    private function saveFallbackRecommendations(Student $student, $majors): void
    {
        $recommendations = [];

        foreach ($majors as $major) {
            $score = $this->calculateFallbackScore($student, $major);

            if ($score >= 40) { // Lowered threshold for fallback
                $recommendations[] = [
                    'major' => $major,
                    'score' => $score,
                    'reasoning' => $this->generateFallbackReasoning($student, $major, $score)
                ];
            }
        }

        // Sort by score
        usort($recommendations, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Take top 5
        $recommendations = array_slice($recommendations, 0, 5);

        // Save recommendations
        foreach ($recommendations as $rec) {
            try {
                Recommendation::create([
                    'student_id' => $student->id,
                    'major_id' => $rec['major']->id,
                    'match_score' => $rec['score'],
                    'factors' => [
                        'strengths' => $this->extractStrengths($student, $rec['major']),
                        'challenges' => $this->extractChallenges($student, $rec['major']),
                    ],
                    'reasoning_ar' => $rec['reasoning'],
                ]);
            } catch (\Exception $e) {
                Log::error('Error saving fallback recommendation', [
                    'error' => $e->getMessage(),
                    'major_id' => $rec['major']->id,
                    'student_id' => $student->id
                ]);
            }
        }
    }

    /**
     * Calculate fallback score using traditional algorithm
     */
    private function calculateFallbackScore(Student $student, Major $major): float
    {
        $score = 0;

        // GPA matching (40 points)
        if ($student->gpa && $major->min_gpa) {
            if ($student->gpa >= $major->min_gpa) {
                $gpaRatio = min($student->gpa / $major->min_gpa, 1.2);
                $score += $gpaRatio * 40;
            } else {
                // Penalty for below minimum GPA
                $gpaDeficit = ($major->min_gpa - $student->gpa) / $major->min_gpa;
                $score += max((1 - $gpaDeficit) * 30, 0);
            }
        }

        // Skills matching (30 points)
        if ($student->skills && $major->required_skills) {
            $matchingSkills = array_intersect($student->skills, $major->required_skills);
            $skillsRatio = count($matchingSkills) / count($major->required_skills);
            $score += $skillsRatio * 30;
        }

        // Interests matching (30 points)
        if ($student->interests && $major->required_skills) {
            $matchingInterests = array_intersect($student->interests, $major->required_skills);
            $score += count($matchingInterests) * 10;
        }

        return min(round($score, 2), 100);
    }

    /**
     * Generate fallback reasoning
     */
    private function generateFallbackReasoning(Student $student, Major $major, float $score): string
    {
        $reasoning = "نسبة توافقك مع تخصص {$major->name_ar} هي {$score}%. ";

        // GPA analysis
        if ($student->gpa && $major->min_gpa) {
            if ($student->gpa >= $major->min_gpa) {
                $difference = round($student->gpa - $major->min_gpa, 2);
                $reasoning .= "معدلك ({$student->gpa}) يتجاوز الحد الأدنى ({$major->min_gpa}) بمقدار {$difference} نقطة، مما يجعلك مؤهلاً بشكل جيد. ";
            } else {
                $difference = round($major->min_gpa - $student->gpa, 2);
                $reasoning .= "معدلك ({$student->gpa}) أقل من الحد الأدنى ({$major->min_gpa}) بمقدار {$difference} نقطة، لكن لا يزال بإمكانك تحسينه. ";
            }
        }

        // Skills analysis
        if ($student->skills && $major->required_skills) {
            $matchingSkills = array_intersect($student->skills, $major->required_skills);
            if (count($matchingSkills) > 0) {
                $skillsList = implode('، ', array_slice($matchingSkills, 0, 3));
                $reasoning .= "مهاراتك في ({$skillsList}) تتوافق بشكل جيد مع متطلبات التخصص. ";
            }
        }

        // Interests analysis
        if ($student->interests && $major->required_skills) {
            $matchingInterests = array_intersect($student->interests, $major->required_skills);
            if (count($matchingInterests) > 0) {
                $interestsList = implode('، ', array_slice($matchingInterests, 0, 2));
                $reasoning .= "اهتماماتك في ({$interestsList}) تدعم اختيارك لهذا التخصص. ";
            }
        }

        return $reasoning;
    }

    /**
     * Extract strengths for fallback
     */
    private function extractStrengths(Student $student, Major $major): array
    {
        $strengths = [];

        if ($student->gpa >= $major->min_gpa) {
            $strengths[] = "معدل أكاديمي ممتاز يفوق متطلبات التخصص";
        }

        if ($student->skills && $major->required_skills) {
            $matchingSkills = array_intersect($student->skills, $major->required_skills);
            if (count($matchingSkills) > 0) {
                $strengths[] = "مهارات متوافقة: " . implode('، ', $matchingSkills);
            }
        }

        if ($student->interests) {
            $strengths[] = "اهتمامات داعمة للتخصص";
        }

        return $strengths;
    }

    /**
     * Extract challenges for fallback
     */
    private function extractChallenges(Student $student, Major $major): array
    {
        $challenges = [];

        if ($student->gpa < $major->min_gpa) {
            $challenges[] = "المعدل أقل من الحد الأدنى المطلوب";
        }

        if ($student->skills && $major->required_skills) {
            $missingSkills = array_diff($major->required_skills, $student->skills);
            if (count($missingSkills) > 0) {
                $challenges[] = "يحتاج تطوير المهارات: " . implode('، ', array_slice($missingSkills, 0, 2));
            }
        }

        return $challenges;
    }
}
