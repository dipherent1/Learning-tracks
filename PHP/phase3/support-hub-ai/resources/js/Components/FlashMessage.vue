<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const show = ref(false);
const message = ref('');
const variant = ref('info');
let timer = null;

const display = (msg, type = 'info') => {
    message.value = msg;
    variant.value = type;
    show.value = true;
    if (timer) clearTimeout(timer);
    timer = setTimeout(() => (show.value = false), 3500);
};

watch(() => page.props.flash, (flashes) => {
    if (!flashes) return;
    if (flashes.success) display(flashes.success, 'success');
    else if (flashes.error) display(flashes.error, 'error');
    else if (flashes.info) display(flashes.info, 'info');
}, { immediate: true });
</script>

<template>
    <!-- Transition component for smooth slide-in and slide-out -->
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="transform opacity-0 translate-x-full"
        enter-to-class="transform opacity-100 translate-x-0"
        leave-active-class="transition ease-in duration-300"
        leave-from-class="transform opacity-100 translate-x-0"
        leave-to-class="transform opacity-0 translate-x-full"
    >
        <!-- The actual banner, which is shown/hidden by the 'show' ref -->
        <div v-if="show" class="fixed top-5 right-5 z-50 max-w-sm" role="status" aria-live="polite">
            <div :class="['p-4 rounded-lg shadow-md', variant === 'success' ? 'bg-green-50 border-l-4 border-green-400 text-green-800' : variant === 'error' ? 'bg-red-50 border-l-4 border-red-400 text-red-800' : 'bg-surface border border-gray-100 text-slate-800']">
                <div class="flex items-start gap-3">
                    <div class="shrink-0">
                        <svg v-if="variant === 'success'" class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <svg v-else-if="variant === 'error'" class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <svg v-else class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 17v.01"/></svg>
                    </div>
                    <div class="text-sm leading-tight">{{ message }}</div>
                </div>
            </div>
        </div>
    </Transition>
</template>