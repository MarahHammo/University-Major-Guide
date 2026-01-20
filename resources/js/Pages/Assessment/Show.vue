<script setup>
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BackButton from '@/Components/BackButton.vue';
import {
    CheckCircleIcon,
    XMarkIcon,
    ArrowRightIcon,
    ArrowLeftIcon,
    ClockIcon,
    QuestionMarkCircleIcon
} from '@heroicons/vue/24/outline';

// Props
const props = defineProps({
    assessment: Object,
    questions: Array,
});

// State management
const currentQuestionIndex = ref(0);
const answers = ref({});
const startTime = ref(Date.now());
const showConfirmDialog = ref(false);

// Computed properties
const currentQuestion = computed(() => props.questions[currentQuestionIndex.value]);
const progress = computed(() => ((currentQuestionIndex.value + 1) / props.questions.length) * 100);
const answeredCount = computed(() => Object.keys(answers.value).length);
const isLastQuestion = computed(() => currentQuestionIndex.value === props.questions.length - 1);
const isFirstQuestion = computed(() => currentQuestionIndex.value === 0);
const canProceed = computed(() => answers.value[currentQuestion.value.id] !== undefined);

// Time tracking
const elapsedTime = computed(() => {
    const seconds = Math.floor((Date.now() - startTime.value) / 1000);
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
});

// Select answer
const selectAnswer = (optionValue) => {
    answers.value[currentQuestion.value.id] = optionValue;
};

// Navigation
const nextQuestion = () => {
    if (currentQuestionIndex.value < props.questions.length - 1) {
        currentQuestionIndex.value++;
    }
};

const previousQuestion = () => {
    if (currentQuestionIndex.value > 0) {
        currentQuestionIndex.value--;
    }
};

const goToQuestion = (index) => {
    currentQuestionIndex.value = index;
};

// Submit assessment
const submitAssessment = () => {
    const totalTime = Math.floor((Date.now() - startTime.value) / 1000);

    router.post(route('assessments.submit', props.assessment.id), {
        answers: answers.value,
        time_taken: totalTime,
    });
};

const confirmSubmit = () => {
    if (answeredCount.value < props.questions.length) {
        showConfirmDialog.value = true;
    } else {
        submitAssessment();
    }
};
</script>

<template>
    <Head :title="assessment.title_ar" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-8">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Back Button -->
                <div class="mb-6">
                    <BackButton :href="route('assessments.index')" text="العودة للاختبارات" />
                </div>

                <!-- Header -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 mb-6 border-2 border-blue-200">
                    <div class="flex items-center justify-between flex-wrap gap-4" dir="rtl">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ assessment.title_ar }}</h1>
                            <p class="text-gray-600">{{ assessment.description_ar }}</p>
                        </div>
                        <div class="flex items-center gap-6">
                            <!-- Time -->
                            <div class="text-center">
                                <div class="flex items-center gap-2 text-blue-600">
                                    <ClockIcon class="w-5 h-5" />
                                    <span class="text-xl font-bold">{{ elapsedTime }}</span>
                                </div>
                                <p class="text-xs text-gray-500">الوقت المنقضي</p>
                            </div>
                            <!-- Progress -->
                            <div class="text-center">
                                <div class="flex items-center gap-2 text-purple-600">
                                    <QuestionMarkCircleIcon class="w-5 h-5" />
                                    <span class="text-xl font-bold">{{ answeredCount }} / {{ questions.length }}</span>
                                </div>
                                <p class="text-xs text-gray-500">الأسئلة المجابة</p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div
                                class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-300"
                                :style="{ width: progress + '%' }"
                            ></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Question Navigation Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-4 border-2 border-purple-200 sticky top-24">
                            <h3 class="font-bold text-gray-900 mb-3 text-center" dir="rtl">خريطة الأسئلة</h3>
                            <div class="grid grid-cols-5 lg:grid-cols-5 gap-2">
                                <button
                                    v-for="(question, index) in questions"
                                    :key="question.id"
                                    @click="goToQuestion(index)"
                                    :class="[
                                        'w-10 h-10 rounded-lg font-bold text-sm transition-all duration-200',
                                        currentQuestionIndex === index
                                            ? 'bg-gradient-to-br from-blue-500 to-purple-500 text-white scale-110 shadow-lg'
                                            : answers[question.id] !== undefined
                                            ? 'bg-green-100 text-green-700 border-2 border-green-300'
                                            : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                    ]"
                                >
                                    {{ index + 1 }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Question Content -->
                    <div class="lg:col-span-3">
                        <!-- Question Card -->
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8 border-2 border-blue-200 mb-6">
                            <!-- Question Number & Category -->
                            <div class="flex items-center justify-between mb-6" dir="rtl">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                        {{ currentQuestionIndex + 1 }}
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">القسم</p>
                                        <p class="font-bold text-gray-900">{{ currentQuestion.category }}</p>
                                    </div>
                                </div>
                                <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold">
                                    السؤال {{ currentQuestionIndex + 1 }} من {{ questions.length }}
                                </span>
                            </div>

                            <!-- Question Text -->
                            <div class="mb-8" dir="rtl">
                                <h2 class="text-2xl font-bold text-gray-900 leading-relaxed">
                                    {{ currentQuestion.question_text }}
                                </h2>
                            </div>

                            <!-- Answer Options -->
                            <div class="space-y-4">
                                <button
                                    v-for="(option, index) in currentQuestion.options"
                                    :key="index"
                                    @click="selectAnswer(option.value)"
                                    :class="[
                                        'w-full p-5 rounded-xl border-2 text-right transition-all duration-200 flex items-center justify-between group',
                                        answers[currentQuestion.id] === option.value
                                            ? 'bg-gradient-to-r from-blue-500 to-purple-500 border-blue-500 text-white shadow-lg scale-[1.02]'
                                            : 'bg-white border-gray-200 hover:border-blue-300 hover:bg-blue-50'
                                    ]"
                                    dir="rtl"
                                >
                                    <span :class="[
                                        'text-lg font-semibold',
                                        answers[currentQuestion.id] === option.value ? 'text-white' : 'text-gray-900'
                                    ]">
                                        {{ option.text }}
                                    </span>
                                    <div v-if="answers[currentQuestion.id] === option.value" class="flex items-center justify-center w-8 h-8 bg-white rounded-full">
                                        <CheckCircleIcon class="w-6 h-6 text-blue-500" />
                                    </div>
                                    <div v-else class="w-6 h-6 rounded-full border-2 border-gray-300 group-hover:border-blue-400"></div>
                                </button>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex items-center justify-between gap-4">
                            <!-- Previous Button -->
                            <button
                                @click="previousQuestion"
                                :disabled="isFirstQuestion"
                                :class="[
                                    'px-6 py-3 rounded-xl font-bold text-lg transition-all duration-200 flex items-center gap-2',
                                    isFirstQuestion
                                        ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                        : 'bg-white border-2 border-gray-300 text-gray-700 hover:bg-gray-50 hover:shadow-lg'
                                ]"
                            >
                                <ArrowRightIcon class="w-5 h-5" />
                                <span>السابق</span>
                            </button>

                            <!-- Next or Submit Button -->
                            <button
                                v-if="!isLastQuestion"
                                @click="nextQuestion"
                                :disabled="!canProceed"
                                :class="[
                                    'px-6 py-3 rounded-xl font-bold text-lg transition-all duration-200 flex items-center gap-2',
                                    canProceed
                                        ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white hover:shadow-xl hover:scale-[1.02]'
                                        : 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                ]"
                            >
                                <span>التالي</span>
                                <ArrowLeftIcon class="w-5 h-5" />
                            </button>

                            <button
                                v-else
                                @click="confirmSubmit"
                                class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl font-bold text-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-200 flex items-center gap-2"
                            >
                                <CheckCircleIcon class="w-6 h-6" />
                                <span>إنهاء الاختبار</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Dialog -->
        <div v-if="showConfirmDialog" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
                <div class="text-center mb-6" dir="rtl">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <QuestionMarkCircleIcon class="w-10 h-10 text-yellow-600" />
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">تنبيه!</h3>
                    <p class="text-gray-600 leading-relaxed">
                        لم تجب على جميع الأسئلة. لقد أجبت على {{ answeredCount }} من أصل {{ questions.length }} سؤال.
                        هل تريد المتابعة؟
                    </p>
                </div>
                <div class="flex gap-3">
                    <button
                        @click="showConfirmDialog = false"
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-all"
                    >
                        إلغاء
                    </button>
                    <button
                        @click="submitAssessment"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl font-bold hover:shadow-lg transition-all"
                    >
                        متابعة
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
