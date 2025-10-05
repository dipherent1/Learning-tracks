<script setup>
import { computed, defineProps } from 'vue';

const props = defineProps({
    status: String,
});

const statusClasses = computed(() => {
    const baseClasses = 'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full';
    
    switch (props.status) {
        case 'open':
            return `${baseClasses} bg-green-100 text-green-800`;
        case 'in_progress':
            return `${baseClasses} bg-yellow-100 text-yellow-800`;
        case 'closed':
            return `${baseClasses} bg-red-100 text-red-800`;
        default:
            return `${baseClasses} bg-gray-100 text-gray-800`;
    }
});

const statusText = computed(() => props.status.replace('_', ' '));
</script>

<template>
    <span :class="statusClasses">
        {{ statusText }}
    </span>
</template>