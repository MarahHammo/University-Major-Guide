<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    response: Object,
    assessment: Object,
});

const completionRate = computed(() => {
    const answered = Object.values(props.response.responses).filter(r => r !== null).length;
    const total = props.assessment.questions.length;
    return Math.round((answered / total) * 100);
});

const getAssessmentColor = (type) => {
    const colors = {
        academic: 'from-blue-400 to-cyan-400',
        personality: 'from-purple-400 to-pink-400',
        interests: 'from-green-400 to-emerald-400',
        skills: 'from-orange-400 to-amber-400'
    };
    return colors[type] || 'from-gray-400 to-gray-500';
};

const getScoreColor = (score) => {
    if (score >= 80) return 'from-green-400 to-emerald-400';
    if (score >= 60) return 'from-blue-400 to-cyan-400';
    return 'from-orange-400 to-amber-400';
};

const getAnswerText = (questionId) => {
    const answer = props.response.responses[questionId];
    if (typeof answer === 'number') {
        return `${answer} / 5`;
    }
    return answer || 'لم يتم الإجابة';
};
</script>

<template>
    <Head title="نتائج الاختبار" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-sky-50 to-cyan-50 py-12">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Success Header -->
                <div :class="`bg-gradient-to-r ${getAssessmentColor(assessment.type)} rounded-3xl p-10 mb-10 text-white text-center shadow-2xl`">
                    <div class="text-8xl mb-4">🎉</div>
                    <h1 class="text-4xl font-bold mb-3">
                        تم إكمال الاختبار بنجاح!
                    </h1>
                    <p class="text-xl text-white/90">
                        {{ assessment.title_ar }}
                    </p>
                </div>

                <!-- Completion Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <!-- Completion Rate -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 text-center border-2 border-blue-200">
                        <div class="text-5xl mb-3">📊</div>
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ completionRate }}%</div>
                        <div class="text-gray-600 font-semibold">نسبة الإكمال</div>
                    </div>

                    <!-- Questions Answered -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 text-center border-2 border-green-200">
                        <div class="text-5xl mb-3">✅</div>
                        <div class="text-3xl font-bold text-green-600 mb-2">
                            {{ Object.values(response.responses).filter(r => r !== null).length }} / {{ assessment.questions.length }}
                        </div>
                        <div class="text-gray-600 font-semibold">الأسئلة المجابة</div>
                    </div>

                    <!-- Time Taken -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 text-center border-2 border-purple-200">
                        <div class="text-5xl mb-3">⏱️</div>
                        <div class="text-3xl font-bold text-purple-600 mb-2">{{ assessment.duration_minutes }}</div>
                        <div class="text-gray-600 font-semibold">دقيقة</div>
                    </div>
                </div>

                <!-- Detailed Answers -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8 mb-10 border-2 border-blue-200">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-blue-100">
                        <span class="text-4xl">📝</span>
                        <h2 class="text-3xl font-bold text-gray-800">إجاباتك التفصيلية</h2>
                    </div>

                    <div class="space-y-6">
                        <div
                            v-for="(question, index) in assessment.questions"
                            :key="question.id"
                            class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-6 border-2 border-blue-200"
                        >
                            <div class="flex items-start gap-4 mb-3">
                                <span class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ index + 1 }}
                                </span>
                                <h3 class="text-xl font-bold text-gray-800 leading-relaxed">
                                    {{ question.question_ar }}
                                </h3>
                            </div>

                            <div class="mr-14">
                                <div class="inline-block bg-white px-6 py-3 rounded-xl border-2 border-blue-300 shadow-sm">
                                    <span class="text-gray-600 font-semibold">إجابتك: </span>
                                    <span class="text-blue-600 font-bold text-lg">
                                        {{ getAnswerText(question.id) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Message -->
                <div class="bg-gradient-to-r from-green-400 to-emerald-400 rounded-2xl p-8 text-white text-center mb-10 shadow-xl">
                    <div class="text-5xl mb-4">💡</div>
                    <h3 class="text-2xl font-bold mb-3">ممتاز!</h3>
                    <p class="text-xl text-green-50 mb-2">
                        لقد أكملت هذا الاختبار بنجاح. استمر في إكمال باقي الاختبارات للحصول على توصيات دقيقة!
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <Link
                        :href="route('assessments.index')"
                        class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-6 py-4 rounded-xl hover:shadow-lg transition-all duration-300 font-bold text-lg text-center"
                    >
                        📝 العودة للاختبارات
                    </Link>

                    <Link
                        :href="route('recommendations.index')"
                        class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-xl hover:shadow-lg transition-all duration-300 font-bold text-lg text-center"
                    >
                        🎯 عرض التوصيات
                    </Link>

                    <button
                        @click="window.print()"
                        class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-4 rounded-xl hover:shadow-lg transition-all duration-300 font-bold text-lg text-center"
                    >
                        🖨️ طباعة النتيجة
                    </button>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
@media print {
    .bg-gradient-to-br,
    nav,
    button {
        display: none !important;
    }
}
</style>
