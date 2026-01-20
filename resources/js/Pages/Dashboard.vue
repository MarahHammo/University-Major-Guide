<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    UserIcon,
    DocumentTextIcon,
    BeakerIcon,
    CheckCircleIcon,
    HandRaisedIcon,
    ChartBarIcon,
    MapIcon
} from '@heroicons/vue/24/outline';

const page = usePage();

// Quick action cards configuration
const quickActions = [
    {
        title: 'إكمال الملف الشخصي',
        description: 'أضف معلوماتك الأكاديمية والشخصية',
        icon: 'user',
        route: 'student.index',
        color: 'from-blue-400 to-cyan-400',
        bgColor: 'bg-blue-50',
        borderColor: 'border-blue-200'
    },
    {
        title: 'إجراء الاختبارات',
        description: 'أكمل الاختبارات لتحديد مهاراتك',
        icon: 'document',
        route: 'assessments.index',
        color: 'from-purple-400 to-pink-400',
        bgColor: 'bg-purple-50',
        borderColor: 'border-purple-200'
    },
    {
        title: 'عرض التوصيات',
        description: 'اطلع على التخصصات المقترحة لك',
        icon: 'beaker',
        route: 'recommendations.index',
        color: 'from-green-400 to-emerald-400',
        bgColor: 'bg-green-50',
        borderColor: 'border-green-200'
    }
];

// Check if student profile is complete
const profileComplete = computed(() => {
    const student = page.props.auth.student;
    return student &&
           student.full_name_ar &&
           student.gpa > 0 &&
           (student.interests?.length > 0) &&
           (student.skills?.length > 0);
});

// Get completed assessments count
const completedAssessments = computed(() => {
    return page.props.completedAssessments || 0;
});

// Get total assessments count
const totalAssessments = computed(() => {
    return page.props.totalAssessments || 4;
});

// Get recommendations count
const recommendationsCount = computed(() => {
    return page.props.recommendationsCount || 0;
});

// Calculate assessment progress percentage
const progressPercentage = computed(() => {
    return Math.round((completedAssessments.value / totalAssessments.value) * 100);
});

// Get appropriate icon component for quick actions
const getIconComponent = (iconName) => {
    const icons = {
        user: UserIcon,
        document: DocumentTextIcon,
        beaker: BeakerIcon
    };
    return icons[iconName] || UserIcon;
};
</script>

<template>
    <Head title="لوحة التحكم" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-sky-50 to-cyan-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Welcome Section -->
                <div class="bg-gradient-to-r from-blue-400 to-cyan-400 rounded-3xl p-10 mb-10 text-white shadow-2xl">
                    <div class="flex items-center gap-4 mb-4" dir="rtl">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                            <HandRaisedIcon class="w-10 h-10 text-white" />
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold mb-2">
                                مرحباً، {{ $page.props.auth.user.name }}!
                            </h1>
                            <p class="text-blue-50 text-xl">
                                ابدأ رحلتك نحو اختيار التخصص الجامعي المناسب
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <!-- Profile Status Card -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-2 border-blue-200 hover:shadow-2xl transition-all">
                        <div class="flex items-center justify-between" dir="rtl">
                            <div>
                                <p class="text-gray-600 text-sm mb-2 font-semibold">حالة الملف الشخصي</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ profileComplete ? 'مكتمل' : 'غير مكتمل' }}
                                </p>
                            </div>
                            <div>
                                <CheckCircleIcon v-if="profileComplete" class="w-12 h-12 text-green-500" />
                                <UserIcon v-else class="w-12 h-12 text-blue-500" />
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t-2 border-blue-100" dir="rtl">
                            <Link
                                v-if="!profileComplete"
                                :href="route('student.index')"
                                class="text-blue-600 hover:text-blue-700 font-semibold text-sm"
                            >
                                إكمال الآن ←
                            </Link>
                            <span v-else class="text-green-600 font-semibold text-sm flex items-center gap-1">
                                <CheckCircleIcon class="w-4 h-4" />
                                <span>ممتاز!</span>
                            </span>
                        </div>
                    </div>

                    <!-- Assessments Progress Card -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-2 border-purple-200 hover:shadow-2xl transition-all">
                        <div class="flex items-center justify-between" dir="rtl">
                            <div>
                                <p class="text-gray-600 text-sm mb-2 font-semibold">الاختبارات المكتملة</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ completedAssessments }} / {{ totalAssessments }}
                                </p>
                            </div>
                            <div>
                                <ChartBarIcon class="w-12 h-12 text-purple-500" />
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t-2 border-purple-100">
                            <!-- Progress bar -->
                            <div class="w-full bg-purple-100 rounded-full h-3 overflow-hidden">
                                <div
                                    class="bg-gradient-to-r from-purple-400 to-pink-400 h-3 rounded-full transition-all duration-500"
                                    :style="{ width: progressPercentage + '%' }"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <!-- Recommendations Count Card -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-2 border-green-200 hover:shadow-2xl transition-all">
                        <div class="flex items-center justify-between" dir="rtl">
                            <div>
                                <p class="text-gray-600 text-sm mb-2 font-semibold">التوصيات المتاحة</p>
                                <p class="text-2xl font-bold text-gray-800">{{ recommendationsCount }}</p>
                            </div>
                            <div>
                                <BeakerIcon class="w-12 h-12 text-green-500" />
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t-2 border-green-100" dir="rtl">
                            <Link
                                :href="route('recommendations.index')"
                                class="text-green-600 hover:text-green-700 font-semibold text-sm"
                            >
                                عرض التوصيات ←
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Section -->
                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center" dir="rtl">إجراءات سريعة</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <Link
                            v-for="(action, index) in quickActions"
                            :key="index"
                            :href="route(action.route)"
                            :class="[
                                action.bgColor,
                                action.borderColor,
                                'rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group border-2 hover:scale-105'
                            ]"
                        >
                            <!-- Action icon header -->
                            <div :class="`bg-gradient-to-r ${action.color} p-8 text-center`">
                                <component :is="getIconComponent(action.icon)" class="w-16 h-16 mx-auto text-white" />
                            </div>
                            <!-- Action details -->
                            <div class="p-6 text-center" dir="rtl">
                                <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition-colors">
                                    {{ action.title }}
                                </h3>
                                <p class="text-gray-600 text-lg">{{ action.description }}</p>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Progress Guide Section -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8 border-2 border-blue-200">
                    <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center gap-3" dir="rtl">
                        <MapIcon class="w-10 h-10 text-blue-600" />
                        <span>دليل التقدم</span>
                    </h2>
                    <div class="space-y-6">
                        <!-- Step 1: Complete Profile -->
                        <div class="flex items-start gap-6 p-6 bg-blue-50 rounded-xl border-2 border-blue-200" dir="rtl">
                            <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-xl">1</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-800 mb-2 text-xl">إكمال الملف الشخصي</h3>
                                <p class="text-gray-600 mb-3 text-lg">أضف معلوماتك الأساسية والأكاديمية</p>
                                <Link
                                    :href="route('student.index')"
                                    class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-bold text-lg"
                                >
                                    <span v-if="!profileComplete">انتقل الآن</span>
                                    <span v-else class="flex items-center gap-1">
                                        <CheckCircleIcon class="w-5 h-5" />
                                        <span>تم!</span>
                                    </span>
                                    <span>←</span>
                                </Link>
                            </div>
                        </div>

                        <!-- Step 2: Take Assessments -->
                        <div class="flex items-start gap-6 p-6 rounded-xl border-2"
                             :class="profileComplete ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200 opacity-60'"
                             dir="rtl">
                            <div class="flex-shrink-0 w-14 h-14 rounded-full flex items-center justify-center shadow-lg"
                                 :class="profileComplete ? 'bg-gradient-to-br from-blue-400 to-cyan-400' : 'bg-gray-300'">
                                <span class="text-white font-bold text-xl">2</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-800 mb-2 text-xl">إجراء الاختبارات</h3>
                                <p class="text-gray-600 text-lg">أكمل الاختبارات لتحديد قدراتك</p>
                                <Link
                                    v-if="profileComplete"
                                    :href="route('assessments.index')"
                                    class="text-blue-600 font-bold text-lg"
                                >
                                    انتقل الآن ←
                                </Link>
                            </div>
                        </div>

                        <!-- Step 3: Get Recommendations -->
                        <div class="flex items-start gap-6 p-6 rounded-xl border-2"
                             :class="completedAssessments === totalAssessments ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200 opacity-60'"
                             dir="rtl">
                            <div class="flex-shrink-0 w-14 h-14 rounded-full flex items-center justify-center shadow-lg"
                                 :class="completedAssessments === totalAssessments ? 'bg-gradient-to-br from-blue-400 to-cyan-400' : 'bg-gray-300'">
                                <span class="text-white font-bold text-xl">3</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-800 mb-2 text-xl">الحصول على التوصيات</h3>
                                <p class="text-gray-600 text-lg">اطلع على التخصصات المناسبة</p>
                                <Link
                                    v-if="completedAssessments === totalAssessments"
                                    :href="route('recommendations.index')"
                                    class="text-blue-600 font-bold text-lg"
                                >
                                    عرض الآن ←
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
