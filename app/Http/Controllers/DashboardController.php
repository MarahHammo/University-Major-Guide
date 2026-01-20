<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;

        // جلب الاختبارات النشطة
        $assessments = Assessment::where('is_active', true)->get();

        // عدد الاختبارات المكتملة (unique لتجنب التكرار)
        $completedAssessments = $student
            ? $student->assessmentResponses()
                      ->distinct('assessment_id')
                      ->count('assessment_id')
            : 0;

        // عدد التوصيات
        $recommendationsCount = $student
            ? $student->recommendations()->count()
            : 0;

        // التحقق من اكتمال الملف الشخصي
        $isProfileComplete = $this->checkProfileComplete($student);

        // تمرير البيانات للـ Vue
        return Inertia::render('Dashboard', [
            'student' => $student,
            'assessments' => $assessments,
            'completedAssessments' => $completedAssessments,
            'totalAssessments' => $assessments->count(),
            'recommendationsCount' => $recommendationsCount,
            'isProfileComplete' => $isProfileComplete,
        ]);
    }

    private function checkProfileComplete($student): bool
    {
        if (!$student) {
            return false;
        }

        // التحقق من الحقول الأساسية
        return !empty($student->full_name_ar)
            && !empty($student->gpa)
            && $student->gpa > 0;
    }
}
