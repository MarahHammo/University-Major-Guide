<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    EnvelopeIcon,
    PaperAirplaneIcon,
    ArrowLeftIcon,
    ShieldCheckIcon
} from '@heroicons/vue/24/outline';

// Define props
defineProps({
    status: String,
});

// Form handling
const form = useForm({
    email: '',
});

// Submit form
const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="نسيت كلمة المرور" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-cyan-600 via-blue-600 to-indigo-600 p-4">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS1vcGFjaXR5PSIwLjA1IiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-20"></div>

        <!-- Card -->
        <div class="relative w-full max-w-md">
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 space-y-6">
                <!-- Icon & Title -->
                <div class="text-center space-y-4" dir="rtl">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl shadow-lg">
                        <ShieldCheckIcon class="w-12 h-12 text-white" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">نسيت كلمة المرور؟</h1>
                        <p class="text-gray-600 mt-2 leading-relaxed">
                            لا مشكلة! أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور
                        </p>
                    </div>
                </div>

                <!-- Success Message -->
                <div v-if="status" class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm" dir="rtl">
                    {{ status }}
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2" dir="rtl">
                            البريد الإلكتروني
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <EnvelopeIcon class="w-5 h-5 text-gray-400" />
                            </div>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autofocus
                                dir="ltr"
                                class="w-full pl-4 pr-11 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all text-left"
                                placeholder="your@email.com"
                            />
                        </div>
                        <div v-if="form.errors.email" class="mt-2 text-sm text-red-600" dir="rtl">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 text-white py-3 px-4 rounded-xl font-bold text-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                        <span v-if="form.processing">جاري الإرسال...</span>
                        <span v-else class="flex items-center gap-2">
                            <PaperAirplaneIcon class="w-6 h-6" />
                            <span>إرسال رابط إعادة التعيين</span>
                        </span>
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="text-center pt-4">
                    <Link
                        :href="route('login')"
                        class="inline-flex items-center gap-2 text-cyan-600 hover:text-cyan-700 font-semibold"
                    >
                        <ArrowLeftIcon class="w-4 h-4" />
                        <span>العودة لتسجيل الدخول</span>
                    </Link>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-full blur-2xl opacity-20"></div>
            <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full blur-2xl opacity-20"></div>
        </div>
    </div>
</template>

