<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private ?string $apiKey = null;

    private string $apiUrl =
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');

        if (!$this->apiKey) {
            throw new \Exception('Gemini API key is not configured');
        }
    }

    /**
     * دالة المحادثة مع الطالب
     */
    public function chat(string $message, string $context, array $history = []): string
    {
        try {
            $contents = [];

            // سياق النظام
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $context]],
            ];

            $contents[] = [
                'role' => 'model',
                'parts' => [[
                    'text' => 'فهمت. أنا مستشار توجيه جامعي وسأساعدك في اختيار التخصص المناسب.'
                ]],
            ];

            // تاريخ المحادثة
            foreach ($history as $msg) {
                if (isset($msg['role'], $msg['content'])) {
                    $contents[] = [
                        'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                        'parts' => [['text' => $msg['content']]],
                    ];
                }
            }

            // الرسالة الحالية
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $message]],
            ];

            $response = Http::withOptions(['verify' => false])
                ->timeout(30)
                ->post($this->apiUrl . '?key=' . $this->apiKey, [
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 400,
                        'topP' => 0.8,
                        'topK' => 40,
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Gemini Chat API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return 'عذراً، حدث خطأ أثناء معالجة طلبك.';
            }

            return trim(
                $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? ''
            );

        } catch (\Exception $e) {
            Log::error('Gemini Chat Exception', [
                'error' => $e->getMessage(),
            ]);

            return 'عذراً، حدث خطأ غير متوقع.';
        }
    }

    /**
     * توليد توصيات ذكية للتخصصات
     */
    public function generateSmartRecommendations(Student $student, $majors): array
    {
        try {
            $prompt = $this->buildRecommendationPrompt($student, $majors);

            $response = Http::withOptions(['verify' => false])
                ->timeout(60)
                ->post($this->apiUrl . '?key=' . $this->apiKey, [
                    'contents' => [
                        [
                            'parts' => [['text' => $prompt]],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.8,
                        'maxOutputTokens' => 2000,
                        'topP' => 0.9,
                        'topK' => 40,
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Gemini Recommendation API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [];
            }

            $text = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';

            return $this->parseRecommendations($text);

        } catch (\Exception $e) {
            Log::error('Gemini Recommendation Exception', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    private function buildRecommendationPrompt(Student $student, $majors): string
    {
        $majorsList = $majors->map(function ($major) {
            return "{$major->name_ar} (GPA الأدنى: {$major->min_gpa})";
        })->implode("\n");

        $studentData = json_encode([
            'gpa' => $student->gpa,
            'interests' => $student->interests ?? [],
            'skills' => $student->skills ?? [],
            'personality_traits' => $student->personality_traits ?? [],
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return <<<PROMPT
أنت خبير توجيه جامعي.
بناءً على بيانات الطالب التالية، اقترح 5 تخصصات مناسبة.

بيانات الطالب:
{$studentData}

التخصصات المتاحة:
{$majorsList}

أرجع النتيجة بصيغة JSON فقط.
PROMPT;
    }

    private function parseRecommendations(string $text): array
    {
        $text = trim($text);
        $text = preg_replace('/```json|```/', '', $text);

        $start = strpos($text, '[');
        $end = strrpos($text, ']');

        if ($start === false || $end === false) {
            return [];
        }

        $json = substr($text, $start, $end - $start + 1);
        $decoded = json_decode($json, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
    }
}
