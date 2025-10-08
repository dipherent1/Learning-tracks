<script setup>
import { computed, defineProps } from 'vue';

const props = defineProps({
    status: String,
});

const statusClasses = computed(() => {
    const base = 'inline-flex items-center gap-2 text-xs font-semibold rounded-full px-2 py-0.5 whitespace-nowrap';

    switch ((props.status || '').toLowerCase()) {
        case 'open':
            return `${base} bg-green-50 text-green-700`;
        case 'in_progress':
        case 'in-progress':
            return `${base} bg-yellow-50 text-yellow-800`;
        case 'closed':
            return `${base} bg-red-50 text-red-700`;
        case 'pending':
            return `${base} bg-primary-50 text-primary-700`;
        default:
            return `${base} bg-gray-50 text-slate-700`;
    }
});

const statusText = computed(() => {
    if (!props.status) return 'Unknown';
    return props.status.replace(/[_-]/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());
});

const dotClass = computed(() => {
    switch ((props.status || '').toLowerCase()) {
        case 'open':
            return 'w-2.5 h-2.5 rounded-full bg-green-500';
        case 'in_progress':
        case 'in-progress':
            return 'w-2.5 h-2.5 rounded-full bg-yellow-500';
        case 'closed':
            return 'w-2.5 h-2.5 rounded-full bg-red-500';
        case 'pending':
            return 'w-2.5 h-2.5 rounded-full bg-primary-600';
        default:
            return 'w-2.5 h-2.5 rounded-full bg-gray-400';
    }
});
</script>

<template>
    <span :class="statusClasses">
        {{ statusText }}
    </span>
</template>