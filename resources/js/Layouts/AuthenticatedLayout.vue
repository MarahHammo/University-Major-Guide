<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    AcademicCapIcon,
    HomeIcon,
    UserIcon,
    DocumentTextIcon,
    BeakerIcon,
    Cog6ToothIcon,
    ArrowRightOnRectangleIcon,
    Bars3Icon,
    XMarkIcon
} from '@heroicons/vue/24/outline';

const showingNavigationDropdown = ref(false);
const page = usePage();

const user = page.props.auth.user;
const student = page.props.auth.student;
const userInitial = user.name ? user.name.charAt(0).toUpperCase() : 'U';

// Get user gender from student data
const userGender = student?.gender || 'unknown';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo & Brand -->
                    <div class="flex items-center">
                        <Link :href="route('dashboard')" class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <AcademicCapIcon class="w-6 h-6 text-white" />
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent hidden sm:block">
                                دليل التخصصات
                            </span>
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center gap-1">
                        <Link
                            :href="route('dashboard')"
                            :class="[
                                'flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200',
                                route().current('dashboard')
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'text-gray-600 hover:bg-gray-100'
                            ]"
                        >
                            <HomeIcon class="w-5 h-5" />
                            <span>الرئيسية</span>
                        </Link>

                        <Link
                            :href="route('student.index')"
                            :class="[
                                'flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200',
                                route().current('student.*')
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'text-gray-600 hover:bg-gray-100'
                            ]"
                        >
                            <UserIcon class="w-5 h-5" />
                            <span>الملف الشخصي</span>
                        </Link>

                        <Link
                            :href="route('assessments.index')"
                            :class="[
                                'flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200',
                                route().current('assessments.*')
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'text-gray-600 hover:bg-gray-100'
                            ]"
                        >
                            <DocumentTextIcon class="w-5 h-5" />
                            <span>الاختبارات</span>
                        </Link>

                        <Link
                            :href="route('recommendations.index')"
                            :class="[
                                'flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200',
                                route().current('recommendations.*')
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'text-gray-600 hover:bg-gray-100'
                            ]"
                        >
                            <BeakerIcon class="w-5 h-5" />
                            <span>التوصيات</span>
                        </Link>
                    </div>

                    <!-- User Menu -->
                    <div class="hidden md:flex items-center gap-3">
                        <div class="relative group">
                            <button class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <!-- User Avatar with Gender Icon -->
                                <div :class="[
                                    'w-10 h-10 rounded-full flex items-center justify-center font-bold',
                                    userGender === 'female' ? 'bg-gradient-to-br from-pink-400 to-purple-500' : 'bg-gradient-to-br from-blue-500 to-cyan-500'
                                ]">
                                    <UserIcon class="w-6 h-6 text-white" />
                                </div>
                                <div class="text-right hidden lg:block" dir="rtl">
                                    <div class="text-sm font-semibold text-gray-900">{{ user.name }}</div>
                                    <div class="text-xs text-gray-500">{{ user.email }}</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <Link
                                    :href="route('profile.edit')"
                                    class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-t-lg transition-colors"
                                >
                                    <Cog6ToothIcon class="w-5 h-5" />
                                    <span>الإعدادات</span>
                                </Link>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-b-lg transition-colors"
                                >
                                    <ArrowRightOnRectangleIcon class="w-5 h-5" />
                                    <span>تسجيل الخروج</span>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors"
                        >
                            <Bars3Icon v-if="!showingNavigationDropdown" class="w-6 h-6" />
                            <XMarkIcon v-else class="w-6 h-6" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div
                v-show="showingNavigationDropdown"
                class="md:hidden border-t border-gray-200 bg-white"
            >
                <div class="px-4 py-3 space-y-1">
                    <Link
                        :href="route('dashboard')"
                        :class="[
                            'flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors',
                            route().current('dashboard')
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-gray-50'
                        ]"
                    >
                        <HomeIcon class="w-5 h-5" />
                        <span>الرئيسية</span>
                    </Link>

                    <Link
                        :href="route('student.index')"
                        :class="[
                            'flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors',
                            route().current('student.*')
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-gray-50'
                        ]"
                    >
                        <UserIcon class="w-5 h-5" />
                        <span>الملف الشخصي</span>
                    </Link>

                    <Link
                        :href="route('assessments.index')"
                        :class="[
                            'flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors',
                            route().current('assessments.*')
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-gray-50'
                        ]"
                    >
                        <DocumentTextIcon class="w-5 h-5" />
                        <span>الاختبارات</span>
                    </Link>

                    <Link
                        :href="route('recommendations.index')"
                        :class="[
                            'flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors',
                            route().current('recommendations.*')
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-gray-50'
                        ]"
                    >
                        <BeakerIcon class="w-5 h-5" />
                        <span>التوصيات</span>
                    </Link>
                </div>

                <!-- Mobile User Menu -->
                <div class="border-t border-gray-200 px-4 py-3">
                    <div class="flex items-center gap-3 mb-3" dir="rtl">
                        <!-- Mobile User Avatar with Gender Icon -->
                        <div :class="[
                            'w-12 h-12 rounded-full flex items-center justify-center',
                            userGender === 'female' ? 'bg-gradient-to-br from-pink-400 to-purple-500' : 'bg-gradient-to-br from-blue-500 to-cyan-500'
                        ]">
                            <UserIcon class="w-7 h-7 text-white" />
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ user.name }}</div>
                            <div class="text-sm text-gray-500">{{ user.email }}</div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <Link
                            :href="route('profile.edit')"
                            class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
                        >
                            <Cog6ToothIcon class="w-5 h-5" />
                            <span>الإعدادات</span>
                        </Link>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                        >
                            <ArrowRightOnRectangleIcon class="w-5 h-5" />
                            <span>تسجيل الخروج</span>
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>
    </div>
</template>

