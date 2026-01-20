<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    EnvelopeIcon,
    LockClosedIcon,
    EyeIcon,
    EyeSlashIcon,
    AcademicCapIcon,
    ArrowRightOnRectangleIcon
} from '@heroicons/vue/24/outline';
import { ref } from 'vue';

// Define props
defineProps({
    canResetPassword: Boolean,
    status: String,
});

// Form handling
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

// Password visibility toggle
const showPassword = ref(false);

// Submit form
const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="تسجيل الدخول" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 p-4">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS1vcGFjaXR5PSIwLjA1IiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-20"></div>

        <!-- Login Card -->
        <div class="relative w-full max-w-md">
            <!-- Card -->
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 space-y-6">
                <!-- Logo & Title -->
                <div class="text-center space-y-4" dir="rtl">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-lg">
                        <AcademicCapIcon class="w-12 h-12 text-white" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">مرحباً بعودتك!</h1>
                        <p class="text-gray-600 mt-2">سجّل دخولك لمتابعة رحلتك الجامعية</p>
                    </div>
                </div>

                <!-- Status Message -->
                <div v-if="status" class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm text-center" dir="rtl">
                    {{ status }}
                </div>

                <!-- Login Form -->
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
                                autocomplete="username"
                                dir="ltr"
                                class="w-full pl-4 pr-11 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-left"
                                placeholder="your@email.com"
                            />
                        </div>
                        <div v-if="form.errors.email" class="mt-2 text-sm text-red-600" dir="rtl">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2" dir="rtl">
                            كلمة المرور
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <LockClosedIcon class="w-5 h-5 text-gray-400" />
                            </div>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                dir="ltr"
                                class="w-full pl-12 pr-11 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-left"
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 left-0 flex items-center pl-3"
                            >
                                <EyeIcon v-if="!showPassword" class="w-5 h-5 text-gray-400 hover:text-gray-600" />
                                <EyeSlashIcon v-else class="w-5 h-5 text-gray-400 hover:text-gray-600" />
                            </button>
                        </div>
                        <div v-if="form.errors.password" class="mt-2 text-sm text-red-600" dir="rtl">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between" dir="rtl">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                v-model="form.remember"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            />
                            <span class="text-sm text-gray-700">تذكرني</span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-blue-600 hover:text-blue-700 font-semibold"
                        >
                            نسيت كلمة المرور؟
                        </Link>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-xl font-bold text-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                        <span v-if="form.processing">جاري تسجيل الدخول...</span>
                        <span v-else class="flex items-center gap-2">
                            <ArrowRightOnRectangleIcon class="w-6 h-6" />
                            <span>تسجيل الدخول</span>
                        </span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">أو</span>
                    </div>
                </div>

                <!-- Register Link -->
                <div class="text-center" dir="rtl">
                    <p class="text-gray-600">
                        ليس لديك حساب؟
                        <Link
                            :href="route('register')"
                            class="text-blue-600 hover:text-blue-700 font-bold"
                        >
                            سجّل الآن
                        </Link>
                    </p>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full blur-2xl opacity-20"></div>
            <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-full blur-2xl opacity-20"></div>
        </div>
    </div>
</template>

