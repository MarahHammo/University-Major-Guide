<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    EnvelopeIcon,
    CheckBadgeIcon,
    PaperAirplaneIcon,
    ArrowRightOnRectangleIcon
} from '@heroicons/vue/24/outline';

// Define props
const props = defineProps({
    status: String,
});

// Form handling
const form = useForm({});

// Submit resend email
const submit = () => {
    form.post(route('verification.send'));
};

// Computed property for success message
const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Head title="تأكيد البريد الإلكتروني" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-amber-600 via-orange-600 to-red-600 p-4">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS1vcGFjaXR5PSIwLjA1IiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-20"></div>

        <!-- Card -->
        <div class="relative w-full max-w-md">
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 space-y-6">
                <!-- Icon & Title -->
                <div class="text-center space-y-4" dir="rtl">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-lg">
                        <EnvelopeIcon class="w-12 h-12 text-white" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">تأكيد بريدك الإلكتروني</h1>
                        <p class="text-gray-600 mt-2 leading-relaxed">
                            شكراً للتسجيل! قبل البدء، يرجى تأكيد بريدك الإلكتروني من خلال النقر على الرابط المرسل إليك.
                        </p>
                    </div>
                </div>

                <!-- Success Message -->
                <div v-if="verificationLinkSent" class="p-4 bg-green-50 border border-green-200 rounded-xl" dir="rtl">
                    <div class="flex items-center gap-2 text-green-700">
                        <CheckBadgeIcon class="w-5 h-5" />
                        <p class="text-sm font-medium">
                            تم إرسال رابط تأكيد جديد إلى بريدك الإلكتروني!
                        </p>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl" dir="rtl">
                    <p class="text-sm text-amber-800">
                        إذا لم تستلم البريد الإلكتروني، يمكنك طلب إرسال رابط جديد.
                    </p>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <!-- Resend Button -->
                    <button
                        @click="submit"
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white py-3 px-4 rounded-xl font-bold text-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                        <span v-if="form.processing">جاري الإرسال...</span>
                        <span v-else class="flex items-center gap-2">
                            <PaperAirplaneIcon class="w-6 h-6" />
                            <span>إعادة إرسال رابط التأكيد</span>
                        </span>
                    </button>

                    <!-- Logout Button -->
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="w-full bg-white border-2 border-gray-300 text-gray-700 py-3 px-4 rounded-xl font-bold text-lg hover:bg-gray-50 transition-all duration-200 flex items-center justify-center gap-2"
                    >
                        <ArrowRightOnRectangleIcon class="w-6 h-6" />
                        <span>تسجيل الخروج</span>
                    </Link>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full blur-2xl opacity-20"></div>
            <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-gradient-to-br from-orange-400 to-red-500 rounded-full blur-2xl opacity-20"></div>
        </div>
    </div>
</template>

