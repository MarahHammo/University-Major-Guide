<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import {
    LockClosedIcon,
    EyeIcon,
    EyeSlashIcon,
    ShieldCheckIcon,
    CheckCircleIcon
} from '@heroicons/vue/24/outline';
import { ref } from 'vue';

// Form handling
const form = useForm({
    password: '',
});

// Password visibility toggle
const showPassword = ref(false);

// Submit form
const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="تأكيد كلمة المرور" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-violet-600 via-fuchsia-600 to-pink-600 p-4">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS1vcGFjaXR5PSIwLjA1IiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-20"></div>

        <!-- Card -->
        <div class="relative w-full max-w-md">
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 space-y-6">
                <!-- Icon & Title -->
                <div class="text-center space-y-4" dir="rtl">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-violet-500 to-fuchsia-600 rounded-2xl shadow-lg">
                        <ShieldCheckIcon class="w-12 h-12 text-white" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">منطقة آمنة</h1>
                        <p class="text-gray-600 mt-2 leading-relaxed">
                            هذه منطقة آمنة من التطبيق. يرجى تأكيد كلمة المرور قبل المتابعة.
                        </p>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-5">
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
                                autofocus
                                autocomplete="current-password"
                                dir="ltr"
                                class="w-full pl-12 pr-11 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all text-left"
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

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white py-3 px-4 rounded-xl font-bold text-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                        <span v-if="form.processing">جاري التأكيد...</span>
                        <span v-else class="flex items-center gap-2">
                            <CheckCircleIcon class="w-6 h-6" />
                            <span>تأكيد</span>
                        </span>
                    </button>
                </form>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-br from-violet-400 to-fuchsia-500 rounded-full blur-2xl opacity-20"></div>
            <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-gradient-to-br from-fuchsia-400 to-pink-500 rounded-full blur-2xl opacity-20"></div>
        </div>
    </div>
</template>

