<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    public function run(): void
    {
        $majors = [
            [
                'name_ar' => 'هندسة نظم الحاسوب',
                'name_en' => 'Computer Systems Engineering',
                'description_ar' => 'تخصص يجمع بين الهندسة والبرمجة وتصميم الأنظمة الحاسوبية',
                'description_en' => 'A major that combines engineering, programming, and computer systems design',
                'category' => 'الهندسة',
                'min_gpa' => 85.0,
                'required_skills' => ['البرمجة', 'التفكير المنطقي', 'حل المشكلات', 'الرياضيات'],
                'career_prospects' => ['مهندس برمجيات', 'مطور تطبيقات', 'مهندس أنظمة'],
                'universities' => ['الجامعة الإسلامية', 'جامعة الأزهر', 'جامعة فلسطين'],
                'icon' => '💻',
                'color' => '#3b82f6',
            ],
            [
                'name_ar' => 'الطب البشري',
                'name_en' => 'Medicine',
                'description_ar' => 'دراسة جسم الإنسان والأمراض وطرق العلاج',
                'description_en' => 'Study of the human body, diseases, and treatment methods',
                'category' => 'العلوم الطبية',
                'min_gpa' => 95.0,
                'required_skills' => ['الدقة', 'التركيز', 'التعاطف', 'العلوم'],
                'career_prospects' => ['طبيب عام', 'طبيب مختص', 'جراح'],
                'universities' => ['الجامعة الإسلامية', 'جامعة الأزهر'],
                'icon' => '⚕️',
                'color' => '#ef4444',
            ],
            [
                'name_ar' => 'إدارة الأعمال',
                'name_en' => 'Business Administration',
                'description_ar' => 'دراسة إدارة المؤسسات والموارد البشرية والتسويق',
                'description_en' => 'Study of managing organizations, human resources, and marketing',
                'category' => 'الإدارة والاقتصاد',
                'min_gpa' => 75.0,
                'required_skills' => ['القيادة', 'التواصل', 'التخطيط', 'التحليل'],
                'career_prospects' => ['مدير أعمال', 'مستشار إداري', 'رائد أعمال'],
                'universities' => ['جامعة الأقصى', 'الجامعة الإسلامية', 'جامعة فلسطين'],
                'icon' => '💼',
                'color' => '#8b5cf6',
            ],
            [
                'name_ar' => 'الهندسة المدنية',
                'name_en' => 'Civil Engineering',
                'description_ar' => 'تصميم وبناء المشاريع الإنشائية والبنية التحتية',
                'description_en' => 'Design and construction of infrastructure projects',
                'category' => 'الهندسة',
                'min_gpa' => 80.0,
                'required_skills' => ['الرياضيات', 'الفيزياء', 'التصميم', 'التخطيط'],
                'career_prospects' => ['مهندس مدني', 'مهندس إنشائي', 'مدير مشاريع'],
                'universities' => ['الجامعة الإسلامية', 'جامعة الأزهر'],
                'icon' => '🏗️',
                'color' => '#f59e0b',
            ],
            [
                'name_ar' => 'الصيدلة',
                'name_en' => 'Pharmacy',
                'description_ar' => 'دراسة الأدوية وتركيبها وطرق استخدامها',
                'description_en' => 'Study of medications, their composition, and usage',
                'category' => 'العلوم الطبية',
                'min_gpa' => 90.0,
                'required_skills' => ['الكيمياء', 'الدقة', 'التركيز', 'العلوم'],
                'career_prospects' => ['صيدلي', 'باحث في الأدوية', 'مدير صيدلية'],
                'universities' => ['الجامعة الإسلامية', 'جامعة الأزهر'],
                'icon' => '💊',
                'color' => '#10b981',
            ],
            [
                'name_ar' => 'هندسة الكهرباء',
                'name_en' => 'Electrical Engineering',
                'description_ar' => 'دراسة الأنظمة الكهربائية والإلكترونية',
                'description_en' => 'Study of electrical and electronic systems',
                'category' => 'الهندسة',
                'min_gpa' => 85.0,
                'required_skills' => ['الفيزياء', 'الرياضيات', 'حل المشكلات', 'التصميم'],
                'career_prospects' => ['مهندس كهرباء', 'مهندس إلكترونيات', 'مهندس طاقة'],
                'universities' => ['الجامعة الإسلامية', 'جامعة الأزهر'],
                'icon' => '⚡',
                'color' => '#eab308',
            ],
            [
                'name_ar' => 'العلوم السياسية',
                'name_en' => 'Political Science',
                'description_ar' => 'دراسة الأنظمة السياسية والعلاقات الدولية',
                'description_en' => 'Study of political systems and international relations',
                'category' => 'العلوم الإنسانية',
                'min_gpa' => 70.0,
                'required_skills' => ['التحليل', 'التواصل', 'البحث', 'الكتابة'],
                'career_prospects' => ['محلل سياسي', 'دبلوماسي', 'باحث'],
                'universities' => ['الجامعة الإسلامية', 'جامعة الأزهر', 'جامعة الأقصى'],
                'icon' => '🏛️',
                'color' => '#6366f1',
            ],
            [
                'name_ar' => 'التصميم الجرافيكي',
                'name_en' => 'Graphic Design',
                'description_ar' => 'دراسة التصميم المرئي والإبداع الفني',
                'description_en' => 'Study of visual design and artistic creativity',
                'category' => 'الفنون',
                'min_gpa' => 70.0,
                'required_skills' => ['الإبداع', 'التصميم', 'الفنون', 'التواصل البصري'],
                'career_prospects' => ['مصمم جرافيك', 'مصمم UI/UX', 'مصمم إعلانات'],
                'universities' => ['جامعة فلسطين', 'الجامعة الإسلامية'],
                'icon' => '🎨',
                'color' => '#ec4899',
            ],
        ];

        foreach ($majors as $major) {
            Major::create($major);
        }
    }
}
