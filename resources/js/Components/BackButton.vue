<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';

// Component props
const props = defineProps({
    // Target URL for back navigation
    href: {
        type: String,
        default: '/dashboard'
    },
    // Button text label
    text: {
        type: String,
        default: 'العودة للرئيسية'
    }
});
</script>

<template>
    <!-- Back button with hover effects -->
    <Link
        :href="href"
        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:shadow-md transition-all duration-200"
        dir="rtl"
    >
        <!-- Arrow icon -->
        <ArrowLeftIcon class="w-5 h-5" />
        <!-- Button text -->
        <span>{{ text }}</span>
    </Link>
</template>
