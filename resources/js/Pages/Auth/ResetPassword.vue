<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import {
    EnvelopeIcon,
    LockClosedIcon,
    EyeIcon,
    EyeSlashIcon,
    CheckCircleIcon,
    KeyIcon
} from '@heroicons/vue/24/outline';
import { ref } from 'vue';

// Define props
const props = defineProps({
    email: String,
    token: String,
});

// Form handling
const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

// Password visibility toggles
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

// Submit form
const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="إعادة تعيين كلمة المرور" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 p-4">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS1vcGFjaXR5PSIwLjA1IiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-20"></div>

        <!-- Card -->
        <div class="relative w-full max-w-md">
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 space-y-6">
                <!-- Icon & Title -->
                <div class="text-center space-y-4" dir="rtl">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg">
                        <KeyIcon class="w-12 h-12 text-white" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">إعادة تعيين كلمة المرور</h1>
                        <p class="text-gray-600 mt-2">أدخل كلمة المرور الجديدة لحسابك</p>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email Input (readonly) -->
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
                                readonly
                                dir="ltr"
                                class="w-full pl-4 pr-11 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-600 cursor-not-allowed text-left"
                            />
                        </div>
                        <div v-if="form.errors.email" class="mt-2 text-sm text-red-600" dir="rtl">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2" dir="rtl">
                            كلمة المرور الجديدة
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
                                autofocus
                                autocomplete="new-password"
                                dir="ltr"
                                class="w-full pl-12 pr-11 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-left"
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

                    <!-- Password Confirmation Input -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2" dir="rtl">
                            تأكيد كلمة المرور
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <LockClosedIcon class="w-5 h-5 text-gray-400" />
                            </div>
                            <input
                                id="password_confirmation"
                                :type="showPasswordConfirmation ? 'text' : 'password'"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                dir="ltr"
                                class="w-full pl-12 pr-11 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-left"
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                @click="showPasswordConfirmation = !showPasswordConfirmation"
                                class="absolute inset-y-0 left-0 flex items-center pl-3"
                            >
                                <EyeIcon v-if="!showPasswordConfirmation" class="w-5 h-5 text-gray-400 hover:text-gray-600" />
                                <EyeSlashIcon v-else class="w-5 h-5 text-gray-400 hover:text-gray-600" />
                            </button>
                        </div>
                        <div v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600" dir="rtl">
                            {{ form.errors.password_confirmation }}
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white py-3 px-4 rounded-xl font-bold text-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                        <span v-if="form.processing">جاري التحديث...</span>
                        <span v-else class="flex items-center gap-2">
                            <CheckCircleIcon class="w-6 h-6" />
                            <span>تحديث كلمة المرور</span>
                        </span>
                    </button>
                </form>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full blur-2xl opacity-20"></div>
            <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full blur-2xl opacity-20"></div>
        </div>
    </div>
</template>

