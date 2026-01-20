<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BackButton from '@/Components/BackButton.vue';
import { Head, useForm } from '@inertiajs/vue3';
import {
    UserIcon,
    DocumentTextIcon,
    AcademicCapIcon,
    LightBulbIcon,
    WrenchScrewdriverIcon,
    CheckCircleIcon,
    ClockIcon
} from '@heroicons/vue/24/outline';

// Component props
const props = defineProps({
    student: Object,
});

// Available interests options
const availableInterests = [
    'التكنولوجيا', 'الطب', 'الهندسة', 'العلوم', 'الفنون', 'الأدب',
    'الرياضة', 'التاريخ', 'الجغرافيا', 'الموسيقى', 'التصميم', 'البرمجة'
];

// Available skills options
const availableSkills = [
    'حل المشكلات', 'التفكير النقدي', 'الإبداع', 'التواصل', 'القيادة',
    'العمل الجماعي', 'إدارة الوقت', 'التحليل', 'البحث', 'الكتابة'
];

// Form initialization with student data or defaults
const form = useForm({
    full_name_ar: props.student?.full_name_ar || '',
    full_name_en: props.student?.full_name_en || '',
    phone: props.student?.phone || '',
    school_name: props.student?.school_name || '',
    gender: props.student?.gender || '',
    birth_date: props.student?.birth_date || '',
    gpa: props.student?.gpa || '',
    interests: props.student?.interests || [],
    skills: props.student?.skills || [],
});

// Toggle interest selection
const toggleInterest = (interest) => {
    const index = form.interests.indexOf(interest);
    if (index > -1) {
        form.interests.splice(index, 1);
    } else {
        form.interests.push(interest);
    }
};

// Toggle skill selection
const toggleSkill = (skill) => {
    const index = form.skills.indexOf(skill);
    if (index > -1) {
        form.skills.splice(index, 1);
    } else {
        form.skills.push(skill);
    }
};

// Submit form (update or create)
const submit = () => {
    if (props.student) {
        form.patch(route('student.update', props.student.id));
    } else {
        form.post(route('student.store'));
    }
};
</script>

<template>
    <Head title="الملف الشخصي" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-sky-50 to-cyan-50 py-12">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Back Button -->
                <div class="mb-6">
                    <BackButton />
                </div>

                <!-- Header Section -->
                <div class="text-center mb-8" dir="rtl">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full mb-4">
                        <UserIcon class="w-12 h-12 text-white" />
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">
                        {{ student ? 'تحديث الملف الشخصي' : 'إنشاء الملف الشخصي' }}
                    </h1>
                    <p class="text-gray-600 text-lg">
                        أكمل معلوماتك للحصول على توصيات دقيقة
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Personal Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8 border border-blue-100">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-blue-100" dir="rtl">
                            <DocumentTextIcon class="w-8 h-8 text-blue-600" />
                            <h2 class="text-2xl font-bold text-gray-800">المعلومات الشخصية</h2>
                        </div>

                        <div class="space-y-5">
                            <!-- Full Name (Arabic) -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2 text-lg" dir="rtl">
                                    الاسم الكامل (بالعربية) <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.full_name_ar"
                                    type="text"
                                    required
                                    dir="rtl"
                                    class="w-full px-5 py-4 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all text-lg"
                                    placeholder="أدخل اسمك الكامل"
                                />
                                <div v-if="form.errors.full_name_ar" class="text-red-600 text-sm mt-2" dir="rtl">
                                    {{ form.errors.full_name_ar }}
                                </div>
                            </div>

                            <!-- Full Name (English) -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2 text-lg" dir="rtl">
                                    الاسم الكامل (بالإنجليزية)
                                </label>
                                <input
                                    v-model="form.full_name_en"
                                    type="text"
                                    dir="ltr"
                                    class="w-full px-5 py-4 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all text-lg"
                                    placeholder="Enter your full name"
                                />
                            </div>

                            <!-- Phone & Gender -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Phone -->
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-lg" dir="rtl">
                                        رقم الهاتف
                                    </label>
                                    <input
                                        v-model="form.phone"
                                        type="tel"
                                        dir="ltr"
                                        class="w-full px-5 py-4 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all text-lg"
                                        placeholder="05xxxxxxxx"
                                    />
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-lg" dir="rtl">
                                        الجنس
                                    </label>
                                    <select
                                        v-model="form.gender"
                                        dir="rtl"
                                        class="w-full px-5 py-4 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all text-lg"
                                    >
                                        <option value="">اختر</option>
                                        <option value="male">ذكر</option>
                                        <option value="female">أنثى</option>
                                    </select>
                                </div>
                            </div>

                            <!-- School & Birth Date -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- School Name -->
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-lg" dir="rtl">
                                        اسم المدرسة
                                    </label>
                                    <input
                                        v-model="form.school_name"
                                        type="text"
                                        dir="rtl"
                                        class="w-full px-5 py-4 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all text-lg"
                                        placeholder="اسم مدرستك"
                                    />
                                </div>

                                <!-- Birth Date -->
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-lg" dir="rtl">
                                        تاريخ الميلاد
                                    </label>
                                    <input
                                        v-model="form.birth_date"
                                        type="date"
                                        class="w-full px-5 py-4 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all text-lg"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8 border border-blue-100">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-blue-100" dir="rtl">
                            <AcademicCapIcon class="w-8 h-8 text-blue-600" />
                            <h2 class="text-2xl font-bold text-gray-800">المعلومات الأكاديمية</h2>
                        </div>

                        <!-- GPA Input -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2 text-lg" dir="rtl">
                                المعدل التراكمي (GPA) <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.gpa"
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                required
                                dir="ltr"
                                class="w-full px-5 py-4 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all text-lg"
                                placeholder="أدخل معدلك (من 0 إلى 100)"
                            />
                            <div v-if="form.errors.gpa" class="text-red-600 text-sm mt-2" dir="rtl">
                                {{ form.errors.gpa }}
                            </div>
                        </div>
                    </div>

                    <!-- Interests Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8 border border-blue-100">
                        <div class="flex items-center gap-3 mb-4 pb-4 border-b-2 border-blue-100" dir="rtl">
                            <LightBulbIcon class="w-8 h-8 text-blue-600" />
                            <h2 class="text-2xl font-bold text-gray-800">الاهتمامات</h2>
                        </div>
                        <p class="text-gray-600 mb-6 text-lg" dir="rtl">اختر المجالات التي تهتم بها</p>

                        <!-- Interest selection buttons -->
                        <div class="flex flex-wrap gap-3 justify-center">
                            <button
                                v-for="interest in availableInterests"
                                :key="interest"
                                type="button"
                                @click="toggleInterest(interest)"
                                :class="[
                                    'px-6 py-3 rounded-xl font-semibold transition-all duration-300 text-lg',
                                    form.interests.includes(interest)
                                        ? 'bg-gradient-to-r from-blue-400 to-cyan-400 text-white shadow-lg scale-105'
                                        : 'bg-blue-50 text-blue-700 hover:bg-blue-100 border-2 border-blue-200'
                                ]"
                            >
                                {{ interest }}
                            </button>
                        </div>
                    </div>

                    <!-- Skills Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8 border border-blue-100">
                        <div class="flex items-center gap-3 mb-4 pb-4 border-b-2 border-blue-100" dir="rtl">
                            <WrenchScrewdriverIcon class="w-8 h-8 text-blue-600" />
                            <h2 class="text-2xl font-bold text-gray-800">المهارات</h2>
                        </div>
                        <p class="text-gray-600 mb-6 text-lg" dir="rtl">اختر المهارات التي تمتلكها</p>

                        <!-- Skill selection buttons -->
                        <div class="flex flex-wrap gap-3 justify-center">
                            <button
                                v-for="skill in availableSkills"
                                :key="skill"
                                type="button"
                                @click="toggleSkill(skill)"
                                :class="[
                                    'px-6 py-3 rounded-xl font-semibold transition-all duration-300 text-lg',
                                    form.skills.includes(skill)
                                        ? 'bg-gradient-to-r from-cyan-400 to-sky-400 text-white shadow-lg scale-105'
                                        : 'bg-cyan-50 text-cyan-700 hover:bg-cyan-100 border-2 border-cyan-200'
                                ]"
                            >
                                {{ skill }}
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6 space-y-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-8 py-5 rounded-2xl hover:shadow-2xl hover:scale-[1.02] transition-all duration-300 font-bold text-2xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3"
                        >
                            <ClockIcon v-if="form.processing" class="w-7 h-7 animate-spin" />
                            <CheckCircleIcon v-else-if="student" class="w-7 h-7" />
                            <span v-if="form.processing">جاري الحفظ...</span>
                            <span v-else>
                                {{ student ? 'تحديث الملف الشخصي' : 'حفظ الملف الشخصي' }}
                            </span>
                        </button>

                        <!-- Success Message -->
                        <div v-if="form.recentlySuccessful" class="text-center text-green-600 font-semibold text-lg animate-pulse flex items-center justify-center gap-2">
                            <CheckCircleIcon class="w-6 h-6" />
                            <span>تم الحفظ بنجاح!</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
