<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    ticket: Object,
    permissions: Object,
});

// --- FORM FOR ADDING A REPLY ---
const replyForm = useForm({
    content: '',
});
const submitReply = () => {
    replyForm.post(route('tickets.replies.store', props.ticket.id), {
        preserveScroll: true,
        onSuccess: () => replyForm.reset(),
    });
};

// --- FORM FOR UPDATING TICKET DETAILS (Status/Agent) ---
const users = usePage().props.users;
const updateForm = useForm({
    status: props.ticket.status,
    agent_id: props.ticket.agent_id,
});
const submitUpdate = () => {
    updateForm.put(route('tickets.update', props.ticket.id));
};

// --- LOGIC FOR AI SUMMARY ---
const summary = ref(null);
const isLoadingSummary = ref(false);
const getSummary = async () => {
    isLoadingSummary.value = true;
    summary.value = null;
    try {
        const response = await axios.post(route('tickets.summarize', props.ticket.id));
        summary.value = response.data.summary;
    } catch (error) {
        console.error('Failed to get summary:', error);
        summary.value = 'Sorry, the AI summary could not be generated at this time.';
    } finally {
        isLoadingSummary.value = false;
    }
};
</script>

<template>
    <AppLayout :title="`Ticket #${ticket.id}`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ticket #{{ ticket.id }}: {{ ticket.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- MAIN CONTENT (Left Column) -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Main Ticket Details -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">
                        <div class="border-b pb-4 mb-4">
                            <p class="text-gray-600">
                                <strong>Status:</strong> <span class="capitalize">{{ ticket.status.replace('_', ' ') }}</span>
                            </p>
                            <p class="text-gray-600">
                                <strong>Department:</strong> {{ ticket.department.name }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Created by:</strong> {{ ticket.user.name }} on {{ new Date(ticket.created_at).toLocaleString() }}
                            </p>
                        </div>
                        <div class="prose max-w-none">
                            <p>{{ ticket.content }}</p>
                        </div>
                    </div>

                    <!-- Replies Section -->
                    <div class="space-y-6">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight px-1">
                            Conversation
                        </h3>
                        <!-- Loop through replies -->
                        <div v-for="reply in ticket.replies" :key="reply.id" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <p class="font-bold">{{ reply.user.name }}</p>
                            <p class="text-xs text-gray-500 mb-2">{{ new Date(reply.created_at).toLocaleString() }}</p>
                            <div class="prose max-w-none whitespace-pre-wrap">{{ reply.content }}</div>
                        </div>

                        <!-- No replies message -->
                        <div v-if="!ticket.replies.length" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <p>No replies yet.</p>
                        </div>
                    </div>

                    <!-- Reply Form -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">
                           Add a Reply
                       </h3>
                       <form @submit.prevent="submitReply">
                           <textarea
                               v-model="replyForm.content"
                               rows="5"
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                               placeholder="Type your reply..."
                           ></textarea>
                           <div v-if="replyForm.errors.content" class="text-sm text-red-600 mt-1">{{ replyForm.errors.content }}</div>

                           <div class="flex justify-end mt-4">
                                <button type="submit" :disabled="replyForm.processing" class="px-4 py-2 bg-gray-800 text-white rounded-md">
                                   Submit Reply
                               </button>
                           </div>
                       </form>
                   </div>
                </div>

                <!-- SIDEBAR (Right Column) -->
                <div class="space-y-6">
                    <!-- Agent Actions Form -->
                    <div v-if="permissions.can_update_ticket" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">
                            Ticket Details
                        </h3>
                        <form @submit.prevent="submitUpdate">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
                                <select id="status" v-model="updateForm.status" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="open">Open</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>

                            <!-- Assign Agent -->
                            <div class="mt-4">
                                <label for="agent" class="block font-medium text-sm text-gray-700">Assign Agent</label>
                                <select id="agent" v-model="updateForm.agent_id" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option :value="null">Unassigned</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">
                                        {{ user.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="flex justify-end mt-4">
                                <button type="submit" :disabled="updateForm.processing" class="px-4 py-2 bg-gray-800 text-white rounded-md">
                                    Update
                                </button>
                            </div>
                        </form>
                        
                        <!-- AI Summarize Button -->
                        <div class="border-t mt-6 pt-6">
                            <button @click="getSummary" :disabled="isLoadingSummary" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md flex items-center justify-center hover:bg-blue-700 disabled:opacity-50">
                                <svg v-if="isLoadingSummary" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span v-if="isLoadingSummary">Generating...</span>
                                <span v-else>AI Summarize</span>
                            </button>
                        </div>
                    </div>

                    <!-- AI Summary Display -->
                    <div v-if="summary" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">
                            AI Summary
                        </h3>
                        <div class="prose max-w-none whitespace-pre-wrap text-sm">{{ summary }}</div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>