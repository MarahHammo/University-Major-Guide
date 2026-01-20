<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    DocumentTextIcon,
    ClockIcon,
    QuestionMarkCircleIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    AcademicCapIcon,
    UserIcon,
    LightBulbIcon,
    WrenchScrewdriverIcon
} from '@heroicons/vue/24/outline';

// Component props
const props = defineProps({
    assessments: Array,
    assessmentAttempts: Object,
    maxAttempts: Number,
});

// Calculate completed assessments count
const completedCount = computed(() => {
    return Object.values(props.assessmentAttempts || {}).filter(a => a.isCompleted).length;
});

// Get total assessments count
const totalCount = computed(() => {
    return props.assessments?.length || 0;
});

// Calculate overall progress percentage
const progressPercentage = computed(() => {
    if (totalCount.value === 0) return 0;
    return Math.round((completedCount.value / totalCount.value) * 100);
});

// Get icon component for assessment type
const getAssessmentIconComponent = (type) => {
    const icons = {
        academic: AcademicCapIcon,
        personality: UserIcon,
        interests: LightBulbIcon,
        skills: WrenchScrewdriverIcon
    };
    return icons[type] || DocumentTextIcon;
};

// Get gradient color for assessment type
const getAssessmentColor = (type) => {
    const colors = {
        academic: 'from-blue-400 to-cyan-400',
        personality: 'from-purple-400 to-pink-400',
        interests: 'from-green-400 to-emerald-400',
        skills: 'from-orange-400 to-amber-400'
    };
    return colors[type] || 'from-gray-400 to-gray-500';
};

// Get background color for assessment type
const getBgColor = (type) => {
    const colors = {
        academic: 'bg-blue-50',
        personality: 'bg-purple-50',
        interests: 'bg-green-50',
        skills: 'bg-orange-50'
    };
    return colors[type] || 'bg-gray-50';
};

// Get border color for assessment type
const getBorderColor = (type) => {
    const colors = {
        academic: 'border-blue-200',
        personality: 'border-purple-200',
        interests: 'border-green-200',
        skills: 'border-orange-200'
    };
    return colors[type] || 'border-gray-200';
};

// Get Arabic label for assessment type
const getTypeLabel = (type) => {
    const labels = {
        academic: 'أكاديمي',
        personality: 'شخصية',
        interests: 'اهتمامات',
        skills: 'مهارات'
    };
    return labels[type] || type;
};

// Get status badge styling
const getStatusBadge = (assessmentId) => {
    const attempt = props.assessmentAttempts[assessmentId];
    if (!attempt) return { text: '', color: '' };

    if (attempt.isCompleted) {
        return {
            text: 'مكتمل',
            color: 'bg-green-100 text-green-800 border-green-200'
        };
    }
    return {
        text: 'جديد',
        color: 'bg-yellow-100 text-yellow-800 border-yellow-200'
    };
};

// Get button text based on assessment status
const getButtonText = (assessmentId) => {
    const attempt = props.assessmentAttempts[assessmentId];
    if (!attempt || !attempt.isCompleted) {
        return 'بدء الاختبار';
    }
    if (attempt.canRetake) {
        return `إعادة المحاولة (${attempt.attempts}/${props.maxAttempts})`;
    }
    return 'تم إكمال الاختبار';
};

// Check if student can take assessment
const canTakeAssessment = (assessmentId) => {
    const attempt = props.assessmentAttempts[assessmentId];
    return !attempt || attempt.canRetake;
};
</script>

<template>
    <Head title="الاختبارات المتاحة" />

    <AuthenticatedLayout>
        <!-- Header Section -->
        <div class="mb-8 text-center" dir="rtl">
            <div class="inline-flex items-center justify-center w-20 h-20 mb-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full">
                <DocumentTextIcon class="w-10 h-10 text-white" />
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">الاختبارات المتاحة</h1>
            <p class="text-gray-600">أكمل جميع الاختبارات للحصول على توصيات دقيقة</p>
        </div>

        <!-- Overall Progress Bar -->
        <div class="mb-8 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-3" dir="rtl">
                <span class="text-sm font-semibold text-gray-700">التقدم الكلي</span>
                <span class="text-lg font-bold text-blue-600">{{ completedCount }} / {{ totalCount }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                <div
                    class="h-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full transition-all duration-500"
                    :style="{ width: progressPercentage + '%' }"
                ></div>
            </div>
            <div class="mt-2 text-right" dir="rtl">
                <span class="text-xs font-medium text-gray-600">{{ progressPercentage }}%</span>
            </div>
        </div>

        <!-- Assessments Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
                v-for="assessment in assessments"
                :key="assessment.id"
                :class="[
                    'relative overflow-hidden rounded-2xl border-2 transition-all duration-300 hover:shadow-xl',
                    getBorderColor(assessment.type)
                ]"
            >
                <!-- Assessment Header with Gradient -->
                <div :class="['p-6 bg-gradient-to-br', getAssessmentColor(assessment.type)]">
                    <div class="flex items-start justify-between" dir="rtl">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <!-- Assessment type icon -->
                                <component :is="getAssessmentIconComponent(assessment.type)" class="w-10 h-10 text-white" />
                                <!-- Assessment type badge -->
                                <span class="px-3 py-1 text-xs font-semibold text-white bg-white/20 backdrop-blur-sm rounded-full border border-white/30">
                                    {{ getTypeLabel(assessment.type) }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-1">{{ assessment.title_ar }}</h3>
                        </div>
                        <!-- Status badge for completed assessments -->
                        <span
                            v-if="assessmentAttempts[assessment.id]?.isCompleted"
                            :class="['px-3 py-1 text-xs font-semibold rounded-full border', getStatusBadge(assessment.id).color]"
                        >
                            {{ getStatusBadge(assessment.id).text }}
                        </span>
                    </div>
                </div>

                <!-- Assessment Content -->
                <div :class="['p-6', getBgColor(assessment.type)]">
                    <p class="text-gray-700 mb-4 line-clamp-2" dir="rtl">{{ assessment.description_ar }}</p>

                    <!-- Assessment metadata -->
                    <div class="flex items-center gap-4 mb-4 text-sm text-gray-600" dir="rtl">
                        <!-- Duration -->
                        <div class="flex items-center gap-1">
                            <ClockIcon class="w-4 h-4" />
                            <span>{{ assessment.duration_minutes }} دقيقة</span>
                        </div>
                        <!-- Questions count -->
                        <div class="flex items-center gap-1">
                            <QuestionMarkCircleIcon class="w-4 h-4" />
                            <span>{{ assessment.questions?.length || 0 }} سؤال</span>
                        </div>
                    </div>

                    <!-- Attempts Information -->
                    <div
                        v-if="assessmentAttempts[assessment.id]?.isCompleted"
                        class="mb-4 p-3 bg-white rounded-lg border border-gray-200"
                    >
                        <div class="flex items-center justify-between text-sm" dir="rtl">
                            <span class="text-gray-600">عدد المحاولات:</span>
                            <span class="font-semibold text-gray-900">
                                {{ assessmentAttempts[assessment.id].attempts }} / {{ maxAttempts }}
                            </span>
                        </div>
                        <!-- Max attempts warning -->
                        <div
                            v-if="!assessmentAttempts[assessment.id].canRetake"
                            class="mt-2 text-xs text-amber-600 flex items-center gap-1"
                            dir="rtl"
                        >
                            <ExclamationTriangleIcon class="w-4 h-4" />
                            <span>لقد وصلت للحد الأقصى من المحاولات</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <Link
                        v-if="canTakeAssessment(assessment.id)"
                        :href="route('assessments.show', assessment.id)"
                        :class="[
                            'block w-full text-center px-6 py-3 rounded-xl font-semibold transition-all duration-200',
                            assessmentAttempts[assessment.id]?.isCompleted
                                ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white hover:shadow-lg hover:scale-105'
                                : 'bg-gradient-to-r from-blue-500 to-purple-600 text-white hover:shadow-lg hover:scale-105'
                        ]"
                    >
                        {{ getButtonText(assessment.id) }}
                    </Link>
                    <!-- Disabled button for completed assessments -->
                    <button
                        v-else
                        disabled
                        class="block w-full text-center px-6 py-3 rounded-xl font-semibold bg-gray-300 text-gray-500 cursor-not-allowed"
                    >
                        تم إكمال الاختبار
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="!assessments || assessments.length === 0" class="text-center py-12">
            <div class="inline-flex items-center justify-center w-24 h-24 mb-4 bg-gray-100 rounded-full">
                <DocumentTextIcon class="w-16 h-16 text-gray-400" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2" dir="rtl">لا توجد اختبارات متاحة حالياً</h3>
            <p class="text-gray-600" dir="rtl">سيتم إضافة اختبارات جديدة قريباً</p>
        </div>
    </AuthenticatedLayout>
</template>
