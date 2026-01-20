<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AssessmentController extends Controller
{
    use AuthorizesRequests;

    /**
     * عرض قائمة جميع الاختبارات
     */
    public function index()
    {
        $student = auth()->user()->student;

        // جلب جميع الاختبارات النشطة
        $assessments = Assessment::where('is_active', true)->get();

        // بناء معلومات المحاولات لكل اختبار
        $assessmentAttempts = [];
        $maxAttempts = 3; // الحد الأقصى للمحاولات

        if ($student) {
            foreach ($assessments as $assessment) {
                $responses = AssessmentResponse::where('student_id', $student->id)
                    ->where('assessment_id', $assessment->id)
                    ->get();

                $attemptsCount = $responses->count();
                $isCompleted = $attemptsCount > 0;
                $canRetake = $attemptsCount < $maxAttempts;

                $assessmentAttempts[$assessment->id] = [
                    'attempts' => $attemptsCount,
                    'isCompleted' => $isCompleted,
                    'canRetake' => $canRetake,
                    'lastAttempt' => $responses->last(),
                ];
            }
        }

        return Inertia::render('Assessment/Index', [
            'assessments' => $assessments->map(function ($assessment) {
                return [
                    'id' => $assessment->id,
                    'title_ar' => $assessment->title_ar,
                    'title_en' => $assessment->title_en,
                    'description_ar' => $assessment->description_ar,
                    'description_en' => $assessment->description_en,
                    'type' => $assessment->type,
                    'duration_minutes' => $assessment->duration,
                    'total_questions' => $assessment->total_questions,
                    'questions' => [], // سيتم ملؤها في صفحة الاختبار
                ];
            }),
            'assessmentAttempts' => $assessmentAttempts,
            'maxAttempts' => $maxAttempts,
        ]);
    }

    /**
     * عرض صفحة الاختبار
     */
    public function show(Assessment $assessment)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('student.index')
                ->with('error', 'يجب إكمال الملف الشخصي أولاً قبل البدء بالاختبارات');
        }

        // جلب الأسئلة
        $questions = $this->getQuestionsForAssessment($assessment->type);

        return Inertia::render('Assessment/Show', [
            'assessment' => [
                'id' => $assessment->id,
                'title_ar' => $assessment->title_ar,
                'title_en' => $assessment->title_en,
                'description_ar' => $assessment->description_ar,
                'description_en' => $assessment->description_en,
                'type' => $assessment->type,
                'duration' => $assessment->duration,
                'total_questions' => $assessment->total_questions,
            ],
            'questions' => $questions,
        ]);
    }

    /**
     * حفظ إجابات الاختبار
     */
    public function submit(Request $request, Assessment $assessment)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return back()->withErrors([
                'error' => 'يجب إكمال الملف الشخصي أولاً'
            ]);
        }

        $validated = $request->validate([
            'answers' => 'required|array|min:1',
            'time_taken' => 'required|integer|min:0',
        ]);

        try {
            // حساب النتيجة
            $score = $this->calculateScore($assessment, $validated['answers']);

            // حفظ الإجابات
            $response = AssessmentResponse::create([
                'student_id' => $student->id,
                'assessment_id' => $assessment->id,
                'answers' => $validated['answers'],
                'score' => $score,
                'time_taken' => $validated['time_taken'],
                'completed_at' => now(),
            ]);

            return redirect()->route('assessments.results', $response->id)
                ->with('success', 'تم حفظ إجاباتك بنجاح!');

        } catch (\Exception $e) {
            Log::error('Error submitting assessment', [
                'error' => $e->getMessage(),
                'student_id' => $student->id,
                'assessment_id' => $assessment->id,
            ]);

            return back()->withErrors([
                'error' => 'حدث خطأ أثناء حفظ إجاباتك. يرجى المحاولة مرة أخرى.'
            ]);
        }
    }

    /**
     * عرض نتائج الاختبار
     */
    public function results(AssessmentResponse $response)
    {
        $this->authorize('view', $response);

        $response->load(['assessment', 'student']);

        return Inertia::render('Assessment/Results', [
            'response' => [
                'id' => $response->id,
                'answers' => $response->answers,
                'score' => $response->score,
                'time_taken' => $response->time_taken,
                'completed_at' => $response->completed_at->toDateTimeString(),
                'assessment' => [
                    'id' => $response->assessment->id,
                    'title_ar' => $response->assessment->title_ar,
                    'title_en' => $response->assessment->title_en,
                    'type' => $response->assessment->type,
                    'total_questions' => $response->assessment->total_questions,
                ],
            ],
        ]);
    }

    /**
     * جلب الأسئلة بناءً على نوع الاختبار
     */
    private function getQuestionsForAssessment($type)
    {
        switch ($type) {
            case 'academic':
                return $this->getAcademicQuestions();
            case 'personality':
                return $this->getPersonalityQuestions();
            case 'interests':
                return $this->getInterestsQuestions();
            case 'skills':
                return $this->getSkillsQuestions();
            default:
                return [];
        }
    }

    private function getAcademicQuestions()
    {
        return [
            [
                'id' => 1,
                'category' => 'المعلومات الأساسية',
                'question_text' => 'ما فرعك الدراسي في المرحلة الثانوية؟',
                'type' => 'single',
                'options' => [
                    ['text' => 'علمي', 'value' => 'science'],
                    ['text' => 'أدبي', 'value' => 'literary'],
                ],
            ],
            [
                'id' => 2,
                'category' => 'المعلومات الأساسية',
                'question_text' => 'ما المادة الدراسية التي تفضلها أكثر؟',
                'type' => 'single',
                'options' => [
                    ['text' => 'الرياضيات', 'value' => 'math'],
                    ['text' => 'الفيزياء', 'value' => 'physics'],
                    ['text' => 'الكيمياء', 'value' => 'chemistry'],
                    ['text' => 'الأحياء', 'value' => 'biology'],
                ],
            ],
        ];
    }

    private function getPersonalityQuestions()
    {
        return [
            [
                'id' => 1,
                'category' => 'الشخصية',
                'question_text' => 'هل تفضل العمل الفردي أم الجماعي؟',
                'type' => 'single',
                'options' => [
                    ['text' => 'الفردي', 'value' => 'individual'],
                    ['text' => 'الجماعي', 'value' => 'team'],
                ],
            ],
        ];
    }

    private function getInterestsQuestions()
    {
        return [
            [
                'id' => 1,
                'category' => 'الاهتمامات',
                'question_text' => 'ما هي مجالات اهتمامك؟',
                'type' => 'multiple',
                'options' => [
                    ['text' => 'التكنولوجيا', 'value' => 'tech'],
                    ['text' => 'الطب', 'value' => 'medicine'],
                ],
            ],
        ];
    }

    private function getSkillsQuestions()
    {
        return [
            [
                'id' => 1,
                'category' => 'المهارات',
                'question_text' => 'ما هي أهم مهاراتك؟',
                'type' => 'multiple',
                'options' => [
                    ['text' => 'حل المشكلات', 'value' => 'problem-solving'],
                    ['text' => 'التواصل', 'value' => 'communication'],
                ],
            ],
        ];
    }

    private function calculateScore(Assessment $assessment, array $answers): int
    {
        return count($answers) * 10;
    }
}
