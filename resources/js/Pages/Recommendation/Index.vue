<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BackButton from '@/Components/BackButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch, nextTick } from 'vue';
import axios from 'axios';
import {
    BeakerIcon,
    SparklesIcon,
    ArrowPathIcon,
    ChatBubbleLeftRightIcon,
    PaperAirplaneIcon,
    CheckBadgeIcon,
    ChartBarIcon,
    TagIcon,
    LightBulbIcon,
    BriefcaseIcon,
    AcademicCapIcon,
    ComputerDesktopIcon,
    PaintBrushIcon,
    CpuChipIcon,
    HeartIcon,
    ScaleIcon,
    CalculatorIcon,
    BeakerIcon as ScienceIcon,
    BookOpenIcon,
    BuildingLibraryIcon,
    CurrencyDollarIcon
} from '@heroicons/vue/24/outline';

// Component props
const props = defineProps({
    recommendations: Array,
});

// Chat state management
const showChat = ref(false);
const chatMessages = ref([
    {
        type: 'bot',
        text: 'مرحباً! أنا معك للمساعدة في تبديد مخاوفك لاختيار التخصص الأفضل. يمكنني مساعدتك في فهم التوصيات والإجابة على أي أسئلة لديك!',
        timestamp: new Date()
    }
]);
const currentMessage = ref('');
const isLoading = ref(false);

// Check if recommendations exist
const hasRecommendations = computed(() => {
    return props.recommendations && props.recommendations.length > 0;
});

// Get score color styling based on match percentage
const getScoreColor = (score) => {
    if (score >= 80) return 'text-green-600 bg-green-50 border-green-200';
    if (score >= 60) return 'text-yellow-600 bg-yellow-50 border-yellow-200';
    return 'text-orange-600 bg-orange-50 border-orange-200';
};

// Get score label in Arabic
const getScoreLabel = (score) => {
    if (score >= 80) return 'توافق ممتاز';
    if (score >= 60) return 'توافق متوسط';
    return 'توافق منخفض';
};

// Trigger recommendations generation
const generateRecommendations = () => {
    router.post(route('recommendations.generate'));
};

// Toggle chat panel visibility
const toggleChat = () => {
    showChat.value = !showChat.value;
};

// Send message to AI chatbot
const sendMessage = async () => {
    if (!currentMessage.value.trim() || isLoading.value) return;

    const userMessage = currentMessage.value.trim();
    currentMessage.value = '';

    // Add user message to chat
    chatMessages.value.push({
        type: 'user',
        text: userMessage,
        timestamp: new Date()
    });

    isLoading.value = true;

    try {
        // Format conversation history for API
        const formattedHistory = chatMessages.value
            .slice(-10)
            .filter(msg => msg.text && msg.text.trim())
            .map(msg => ({
                role: msg.type === 'user' ? 'user' : 'assistant',
                content: msg.text
            }));

        // Send request to backend
        const response = await axios.post(route('chat.message'), {
            message: userMessage,
            conversation_history: formattedHistory
        });

        // Add bot response to chat
        chatMessages.value.push({
            type: 'bot',
            text: response.data.message,
            timestamp: new Date()
        });
    } catch (error) {
        console.error('Chat error:', error);
        console.error('Error details:', error.response);

        // Handle different error types
        let errorMessage = 'عذراً، حدث خطأ في معالجة رسالتك.';

        if (error.response?.status === 500) {
            errorMessage = 'عذراً، هناك مشكلة في الخادم. يرجى التحقق من إعدادات Gemini API Key في ملف .env';
        } else if (error.response?.status === 404) {
            errorMessage = 'عذراً، خدمة المحادثة غير متوفرة. تأكد من وجود ChatController';
        } else if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
        }

        chatMessages.value.push({
            type: 'bot',
            text: errorMessage,
            timestamp: new Date()
        });
    } finally {
        isLoading.value = false;
    }
};

// Auto-scroll chat to bottom
const scrollToBottom = () => {
    nextTick(() => {
        const chatContainer = document.getElementById('chat-messages');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
};

// Watch for new messages to trigger scroll
watch(() => chatMessages.value.length, () => {
    scrollToBottom();
});

// Get appropriate icon component for major category
const getMajorIcon = (majorName, category) => {
    // Map major names or categories to icons
    const iconMap = {
        // Technology & Engineering
        'هندسة الحاسوب': ComputerDesktopIcon,
        'علوم الحاسوب': CpuChipIcon,
        'هندسة البرمجيات': ComputerDesktopIcon,
        'تقنية المعلومات': CpuChipIcon,
        'الهندسة': CpuChipIcon,

        // Arts & Design
        'التصميم': PaintBrushIcon,
        'الفنون': PaintBrushIcon,
        'العمارة': BuildingLibraryIcon,

        // Medical & Health
        'الطب': HeartIcon,
        'التمريض': HeartIcon,
        'الصيدلة': BeakerIcon,
        'العلوم الصحية': HeartIcon,

        // Law & Business
        'القانون': ScaleIcon,
        'الحقوق': ScaleIcon,
        'إدارة الأعمال': CurrencyDollarIcon,
        'الاقتصاد': CurrencyDollarIcon,

        // Sciences
        'الرياضيات': CalculatorIcon,
        'الفيزياء': ScienceIcon,
        'الكيمياء': ScienceIcon,
        'العلوم': ScienceIcon,

        // Literature & Languages
        'الأدب': BookOpenIcon,
        'اللغات': BookOpenIcon,
    };

    // Check major name first
    for (const [key, icon] of Object.entries(iconMap)) {
        if (majorName && majorName.includes(key)) {
            return icon;
        }
    }

    // Check category as fallback
    if (category) {
        for (const [key, icon] of Object.entries(iconMap)) {
            if (category.includes(key)) {
                return icon;
            }
        }
    }

    // Default icon
    return AcademicCapIcon;
};
</script>

<template>
    <Head title="التوصيات المخصصة" />

    <AuthenticatedLayout>
        <div class="ml-6">
            <!-- Back Button -->
            <div class="mb-6">
                <BackButton />
            </div>

            <!-- Header Section -->
            <div class="mb-8 text-center" dir="rtl">
                <div class="inline-flex items-center justify-center w-20 h-20 mb-4 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full">
                    <BeakerIcon class="w-12 h-12 text-white" />
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">التوصيات المخصصة</h1>
                <p class="text-gray-600">التخصصات الجامعية المناسبة لك بناءً على ملفك الشخصي واختباراتك</p>
            </div>

            <!-- No Recommendations - Generate Button -->
            <div v-if="!hasRecommendations" class="text-center mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-8 border-2 border-blue-200 max-w-2xl mx-auto">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <SparklesIcon class="w-14 h-14 text-white" />
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3" dir="rtl">جاهز لاكتشاف مسارك؟</h3>
                    <p class="text-gray-600 mb-6" dir="rtl">اضغط على الزر أدناه للحصول على توصيات ذكية بالتخصصات المناسبة لك</p>
                    <button
                        @click="generateRecommendations"
                        class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:shadow-xl hover:scale-105 transition-all duration-200"
                    >
                        <SparklesIcon class="w-5 h-5" />
                        تحديث التوصيات
                    </button>
                </div>
            </div>

            <!-- Recommendations with Chat Layout -->
            <div v-else class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <!-- Chat Side Panel (Right - 2/5 width) -->
                <div class="lg:col-span-2 order-1 lg:order-1">
                    <div class="sticky top-24 bg-white rounded-2xl shadow-lg border-2 border-purple-200 flex flex-col h-[calc(100vh-8rem)]">
                        <!-- Chat Header -->
                        <div class="bg-gradient-to-r from-purple-500 to-pink-600 text-white p-4 rounded-t-2xl">
                            <div class="flex items-center gap-3 mb-2" dir="rtl">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                    <ChatBubbleLeftRightIcon class="w-7 h-7" />
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">المساعد الذكي</h3>
                                    <p class="text-xs opacity-90">متصل الآن</p>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Messages Container -->
                        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
                            <div
                                v-for="(message, index) in chatMessages"
                                :key="index"
                                :class="['flex', message.type === 'user' ? 'justify-end' : 'justify-start']"
                            >
                                <div
                                    :class="[
                                        'max-w-[85%] rounded-2xl p-3 shadow-sm',
                                        message.type === 'user'
                                            ? 'bg-gradient-to-br from-purple-500 to-pink-600 text-white'
                                            : 'bg-white text-gray-900 border border-gray-200'
                                    ]"
                                    dir="rtl"
                                >
                                    <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ message.text }}</p>
                                </div>
                            </div>

                            <!-- Loading Indicator -->
                            <div v-if="isLoading" class="flex justify-start">
                                <div class="bg-white border border-gray-200 rounded-2xl p-3 shadow-sm">
                                    <div class="flex gap-1">
                                        <span class="w-2 h-2 bg-purple-400 rounded-full animate-bounce"></span>
                                        <span class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                                        <span class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Input -->
                        <div class="p-4 border-t border-gray-200 bg-white rounded-b-2xl">
                            <form @submit.prevent="sendMessage" class="flex gap-2">
                                <input
                                    v-model="currentMessage"
                                    type="text"
                                    placeholder="اكتب رسالتك..."
                                    dir="rtl"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm"
                                    :disabled="isLoading"
                                />
                                <button
                                    type="submit"
                                    :disabled="!currentMessage.trim() || isLoading"
                                    class="px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <PaperAirplaneIcon class="w-5 h-5" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Recommendations Column (Left - 3/5 width) -->
                <div class="lg:col-span-3 order-2 lg:order-2 space-y-6">
                    <!-- Update Recommendations Button -->
                    <div class="flex justify-end">
                        <button
                            @click="generateRecommendations"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-purple-200 text-purple-700 font-semibold rounded-xl hover:bg-purple-50 hover:shadow-lg transition-all duration-200"
                        >
                            <ArrowPathIcon class="w-5 h-5" />
                            <span dir="rtl">تحديث التوصيات</span>
                        </button>
                    </div>

                    <!-- Recommendation Cards -->
                    <div
                        v-for="(rec, index) in recommendations"
                        :key="rec.id"
                        class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200"
                    >
                        <div class="p-6">
                            <!-- Card Header -->
                            <div class="flex items-start justify-between mb-6" dir="rtl">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <component :is="getMajorIcon(rec.major?.name_ar, rec.major?.category)" class="w-10 h-10 text-white" />
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="px-3 py-1 text-xs font-bold bg-purple-100 text-purple-800 rounded-full">
                                                #{{ index + 1 }}
                                            </span>
                                            <span :class="['px-3 py-1 text-xs font-bold rounded-full border', getScoreColor(rec.match_score)]">
                                                {{ getScoreLabel(rec.match_score) }}
                                            </span>
                                        </div>
                                        <h2 class="text-2xl font-bold text-gray-900">{{ rec.major?.name_ar }}</h2>
                                        <p class="text-sm text-gray-600 mt-1">{{ rec.major?.category }}</p>
                                    </div>
                                </div>

                                <!-- Score Circle Badge -->
                                <div class="flex flex-col items-center">
                                    <div class="relative">
                                        <svg class="w-24 h-24 transform -rotate-90">
                                            <circle cx="48" cy="48" r="40" stroke="#e5e7eb" stroke-width="8" fill="none" />
                                            <circle
                                                cx="48"
                                                cy="48"
                                                r="40"
                                                :stroke="rec.match_score >= 80 ? '#10b981' : rec.match_score >= 60 ? '#f59e0b' : '#f97316'"
                                                stroke-width="8"
                                                fill="none"
                                                :stroke-dasharray="`${(rec.match_score / 100) * 251.2} 251.2`"
                                                class="transition-all duration-1000"
                                            />
                                        </svg>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                                            <span class="text-xl font-bold text-gray-900">{{ rec.match_score }}%</span>
                                            <span class="text-xs text-gray-500">توافق</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Why This Major Section -->
                            <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <div class="flex items-start gap-2 mb-2" dir="rtl">
                                    <LightBulbIcon class="w-5 h-5 text-blue-600" />
                                    <h3 class="font-bold text-blue-900">لماذا هذا التخصص مناسب لك؟</h3>
                                </div>
                                <p class="text-gray-700 leading-relaxed" dir="rtl">{{ rec.reasoning_ar }}</p>
                            </div>

                            <!-- Details Grid -->
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <!-- Min GPA -->
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                    <div class="flex items-center gap-2 mb-2" dir="rtl">
                                        <ChartBarIcon class="w-5 h-5 text-blue-500" />
                                        <span class="text-sm font-semibold text-gray-700">الحد الأدنى للمعدل</span>
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900" dir="rtl">{{ rec.major?.min_gpa }}</p>
                                </div>

                                <!-- Category -->
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                    <div class="flex items-center gap-2 mb-2" dir="rtl">
                                        <TagIcon class="w-5 h-5 text-purple-500" />
                                        <span class="text-sm font-semibold text-gray-700">الفئة</span>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900" dir="rtl">{{ rec.major?.category }}</p>
                                </div>
                            </div>

                            <!-- Required Skills -->
                            <div v-if="rec.major?.required_skills" class="mb-6">
                                <div class="flex items-center gap-2 mb-3" dir="rtl">
                                    <CheckBadgeIcon class="w-5 h-5 text-purple-600" />
                                    <h3 class="font-bold text-gray-900">المهارات المطلوبة</h3>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="skill in rec.major.required_skills"
                                        :key="skill"
                                        class="px-4 py-2 bg-purple-100 text-purple-800 rounded-lg text-sm font-medium border border-purple-200"
                                        dir="rtl"
                                    >
                                        {{ skill }}
                                    </span>
                                </div>
                            </div>

                            <!-- Career Prospects -->
                            <div v-if="rec.major?.career_prospects" class="mb-6">
                                <div class="flex items-center gap-2 mb-3" dir="rtl">
                                    <BriefcaseIcon class="w-5 h-5 text-green-600" />
                                    <h3 class="font-bold text-gray-900">الفرص الوظيفية</h3>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="career in rec.major.career_prospects"
                                        :key="career"
                                        class="px-4 py-2 bg-green-100 text-green-800 rounded-lg text-sm font-medium border border-green-200"
                                        dir="rtl"
                                    >
                                        {{ career }}
                                    </span>
                                </div>
                            </div>

                            <!-- Universities -->
                            <div v-if="rec.major?.universities">
                                <div class="flex items-center gap-2 mb-3" dir="rtl">
                                    <AcademicCapIcon class="w-5 h-5 text-indigo-600" />
                                    <h3 class="font-bold text-gray-900">الجامعات المتاحة</h3>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="university in rec.major.universities"
                                        :key="university"
                                        class="px-4 py-2 bg-indigo-100 text-indigo-800 rounded-lg text-sm font-medium border border-indigo-200"
                                        dir="rtl"
                                    >
                                        {{ university }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
