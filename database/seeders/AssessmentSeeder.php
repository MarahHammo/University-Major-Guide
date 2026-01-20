<?php

namespace Database\Seeders;

use App\Models\Assessment;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $assessments = [
            [
                'title_ar' => 'اختبار القدرات الأكاديمية',
                'title_en' => 'Academic Abilities Test',
                'description_ar' => 'اختبار شامل يقيس قدراتك الأكاديمية وأسلوب تعلمك ومهاراتك التقنية',
                'description_en' => 'Comprehensive test to measure your academic abilities',
                'type' => 'academic',
                'duration_minutes' => 30,
                'is_active' => true,
            ],
            [
                'title_ar' => 'اختبار الشخصية',
                'title_en' => 'Personality Test',
                'description_ar' => 'اختبار يكشف سماتك الشخصية وأسلوب عملك وطريقة تفكيرك',
                'description_en' => 'Test to reveal your personality traits',
                'type' => 'personality',
                'duration_minutes' => 25,
                'is_active' => true,
            ],
            [
                'title_ar' => 'اختبار الاهتمامات والميول',
                'title_en' => 'Interests and Inclinations Test',
                'description_ar' => 'اختبار يحدد اهتماماتك وميولك المهنية',
                'description_en' => 'Test to determine your interests',
                'type' => 'interests',
                'duration_minutes' => 35,
                'is_active' => true,
            ],
            [
                'title_ar' => 'اختبار الدوافع والقيم',
                'title_en' => 'Motivations and Values Test',
                'description_ar' => 'اختبار يكشف دوافعك وقيمك وتوقعاتك المستقبلية',
                'description_en' => 'Test to reveal your motivations and values',
                'type' => 'skills',
                'duration_minutes' => 30,
                'is_active' => true,
            ],
        ];

        foreach ($assessments as $assessment) {
            Assessment::create($assessment);
        }
    }
}
