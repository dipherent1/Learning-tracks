<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const show = ref(false);
const message = ref('');
let timer = null;

// Watch for changes to the 'success' flash message property
watch(() => page.props.flash?.success, (newMessage) => {
    if (newMessage) {
        message.value = newMessage;
        show.value = true;
        
        // If a timer is already running, clear it before starting a new one
        if (timer) {
            clearTimeout(timer);
        }
        
        // Set a timer to automatically hide the message after 3 seconds
        timer = setTimeout(() => {
            show.value = false;
        }, 3000);
    }
}, {
    // 'immediate: true' ensures the watcher runs once when the component is first created
    immediate: true 
});
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
        <div v-if="show" class="fixed top-5 right-5 z-50 max-w-sm">
            <div class="bg-green-500 border-l-4 border-green-700 text-white p-4 rounded-lg shadow-lg">
                <p>{{ message }}</p>
            </div>
        </div>
    </Transition>
</template>