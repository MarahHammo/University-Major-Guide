<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\Major;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        $user = User::create([
            'name' => 'Hala Abdallah',
            'email' => 'hala@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Create student profile
        Student::create([
            'user_id' => $user->id,
            'full_name_ar' => 'حلا عبدالله',
            'full_name_en' => 'Hala Abdallah',
            'gpa' => 95.00,
            'gender' => 'female',
            'phone' => '0501234567',
            'school_name' => 'مدرسة النجاح الثانوية',
            'birth_date' => '2005-05-15',
            'interests' => ['التكنولوجيا', 'البرمجة', 'الذكاء الاصطناعي', 'التصميم'],
            'skills' => ['حل المشكلات', 'التفكير النقدي', 'البرمجة', 'التحليل'],
        ]);

        // Create assessments
        $assessments = [
            [
                'title_ar' => 'اختبار القدرات الأكاديمية',
                'title_en' => 'Academic Abilities Test',
                'description_ar' => 'اختبار شامل يقيس قدراتك الأكاديمية وأسلوب تعلمك ومهاراتك التقنية',
                'description_en' => 'Comprehensive test to measure your academic abilities, learning style, and technical skills',
                'type' => 'academic',
                'duration' => 30,
                'total_questions' => 15,
                'is_active' => true,
            ],
            [
                'title_ar' => 'اختبار الشخصية',
                'title_en' => 'Personality Test',
                'description_ar' => 'اختبار يكشف سماتك الشخصية وأسلوب عملك وطريقة تفكيرك',
                'description_en' => 'Test to reveal your personality traits, work style, and thinking patterns',
                'type' => 'personality',
                'duration' => 25,
                'total_questions' => 20,
                'is_active' => true,
            ],
            [
                'title_ar' => 'اختبار الاهتمامات والميول',
                'title_en' => 'Interests and Inclinations Test',
                'description_ar' => 'اختبار يحدد اهتماماتك وميولك المهنية والبيئة المفضلة للعمل',
                'description_en' => 'Test to determine your interests, career inclinations, and preferred work environment',
                'type' => 'interests',
                'duration' => 35,
                'total_questions' => 27,
                'is_active' => true,
            ],
            [
                'title_ar' => 'اختبار الدوافع والقيم',
                'title_en' => 'Motivations and Values Test',
                'description_ar' => 'اختبار يكشف دوافعك وقيمك وتوقعاتك المستقبلية وأولوياتك',
                'description_en' => 'Test to reveal your motivations, values, future expectations, and priorities',
                'type' => 'skills',
                'duration' => 30,
                'total_questions' => 27,
                'is_active' => true,
            ],
        ];

        foreach ($assessments as $assessment) {
            Assessment::create($assessment);
        }

        // Create majors
        $majors = [
            [
                'name_ar' => 'هندسة الحاسوب',
                'name_en' => 'Computer Engineering',
                'category' => 'الهندسة والتكنولوجيا',
                'min_gpa' => 85.00,
                'required_skills' => ['البرمجة', 'الرياضيات', 'التفكير المنطقي', 'حل المشكلات'],
                'career_prospects' => ['مهندس برمجيات', 'مطور تطبيقات', 'مهندس شبكات', 'مهندس أمن سيبراني'],
                'universities' => ['جامعة الملك سعود', 'جامعة الملك عبدالعزيز', 'جامعة الإمام'],
            ],
            [
                'name_ar' => 'علوم الحاسب',
                'name_en' => 'Computer Science',
                'category' => 'العلوم والتقنية',
                'min_gpa' => 80.00,
                'required_skills' => ['البرمجة', 'التحليل', 'الخوارزميات', 'الرياضيات'],
                'career_prospects' => ['مطور برمجيات', 'محلل بيانات', 'مهندس ذكاء اصطناعي', 'مطور ويب'],
                'universities' => ['جامعة الملك سعود', 'جامعة الملك فهد', 'جامعة الأمير سلطان'],
            ],
            [
                'name_ar' => 'الطب البشري',
                'name_en' => 'Medicine',
                'category' => 'العلوم الصحية',
                'min_gpa' => 95.00,
                'required_skills' => ['التفكير التحليلي', 'التواصل', 'الصبر', 'الدقة'],
                'career_prospects' => ['طبيب عام', 'طبيب متخصص', 'جراح', 'باحث طبي'],
                'universities' => ['جامعة الملك سعود', 'جامعة الملك عبدالعزيز', 'جامعة الدمام'],
            ],
            [
                'name_ar' => 'الهندسة المعمارية',
                'name_en' => 'Architecture',
                'category' => 'الهندسة والتصميم',
                'min_gpa' => 80.00,
                'required_skills' => ['الإبداع', 'التصميم', 'الرسم', 'التخطيط'],
                'career_prospects' => ['مهندس معماري', 'مصمم داخلي', 'مخطط حضري', 'مستشار معماري'],
                'universities' => ['جامعة الملك سعود', 'جامعة دار الحكمة', 'جامعة الأمير سلطان'],
            ],
            [
                'name_ar' => 'إدارة الأعمال',
                'name_en' => 'Business Administration',
                'category' => 'الإدارة والأعمال',
                'min_gpa' => 75.00,
                'required_skills' => ['القيادة', 'التواصل', 'التخطيط', 'اتخاذ القرار'],
                'career_prospects' => ['مدير أعمال', 'محلل مالي', 'رائد أعمال', 'مستشار إداري'],
                'universities' => ['جامعة الملك سعود', 'جامعة الملك فهد', 'جامعة الأعمال والتكنولوجيا'],
            ],
            [
                'name_ar' => 'التصميم الجرافيكي',
                'name_en' => 'Graphic Design',
                'category' => 'الفنون والتصميم',
                'min_gpa' => 70.00,
                'required_skills' => ['الإبداع', 'التصميم', 'استخدام البرامج', 'الذوق الفني'],
                'career_prospects' => ['مصمم جرافيك', 'مصمم UI/UX', 'مصمم رقمي', 'مخرج فني'],
                'universities' => ['جامعة دار الحكمة', 'كلية الأمير سلطان', 'معهد التصميم'],
            ],
            [
                'name_ar' => 'القانون',
                'name_en' => 'Law',
                'category' => 'القانون والعلوم الإنسانية',
                'min_gpa' => 85.00,
                'required_skills' => ['التحليل', 'الكتابة', 'النقاش', 'البحث'],
                'career_prospects' => ['محامي', 'مستشار قانوني', 'قاضي', 'باحث قانوني'],
                'universities' => ['جامعة الملك سعود', 'جامعة الإمام', 'جامعة الملك عبدالعزيز'],
            ],
            [
                'name_ar' => 'الصيدلة',
                'name_en' => 'Pharmacy',
                'category' => 'العلوم الصحية',
                'min_gpa' => 90.00,
                'required_skills' => ['الكيمياء', 'الدقة', 'التواصل', 'البحث العلمي'],
                'career_prospects' => ['صيدلي', 'باحث دوائي', 'مدير صيدلية', 'مستشار دوائي'],
                'universities' => ['جامعة الملك سعود', 'جامعة الملك عبدالعزيز', 'جامعة الدمام'],
            ],
            [
                'name_ar' => 'الهندسة الكهربائية',
                'name_en' => 'Electrical Engineering',
                'category' => 'الهندسة والتكنولوجيا',
                'min_gpa' => 85.00,
                'required_skills' => ['الفيزياء', 'الرياضيات', 'حل المشكلات', 'التصميم'],
                'career_prospects' => ['مهندس كهرباء', 'مهندس إلكترونيات', 'مهندس طاقة', 'مهندس اتصالات'],
                'universities' => ['جامعة الملك سعود', 'جامعة الملك فهد', 'جامعة الملك عبدالعزيز'],
            ],
            [
                'name_ar' => 'الهندسة المدنية',
                'name_en' => 'Civil Engineering',
                'category' => 'الهندسة والتكنولوجيا',
                'min_gpa' => 80.00,
                'required_skills' => ['الرياضيات', 'الفيزياء', 'التصميم', 'التخطيط'],
                'career_prospects' => ['مهندس مدني', 'مهندس إنشائي', 'مدير مشاريع', 'مستشار هندسي'],
                'universities' => ['جامعة الملك سعود', 'جامعة الملك فهد', 'جامعة الملك عبدالعزيز'],
            ],
        ];

        foreach ($majors as $major) {
            Major::create($major);
        }
    }
}
