<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RecommendationController extends Controller
{
    public function __construct(
        private RecommendationService $recommendationService
    ) {}

    public function index()
    {
        $student = auth()->user()->student;

        $recommendations = $student
            ? $student->recommendations()
                ->with('major')
                ->orderBy('match_score', 'desc')
                ->get()
            : [];

        return Inertia::render('Recommendation/Index', [
            'recommendations' => $recommendations,
        ]);
    }

    public function generate(Request $request)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return back()->withErrors(['message' => 'يرجى إكمال ملفك الشخصي أولاً']);
        }

        // استخدم الـ Service لإنشاء التوصيات
        $this->recommendationService->generateRecommendations($student);

        // استخدم Inertia::location للتأكد من تحديث البيانات
        return Inertia::location(route('recommendations.index'));
    }
}
