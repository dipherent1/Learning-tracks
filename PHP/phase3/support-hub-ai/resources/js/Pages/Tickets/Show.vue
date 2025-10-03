<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps } from 'vue';

const props = defineProps({
    ticket: Object,
});
</script>

<template>
    <AppLayout :title="`Ticket #${ticket.id}`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ticket #{{ ticket.id }}: {{ ticket.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Main Ticket Details -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">
                    <div class="border-b pb-4 mb-4">
                        <p class="text-gray-600">
                            <strong>Status:</strong> <span class="capitalize">{{ ticket.status }}</span>
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
                <div class="mt-8">
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">
                        Conversation
                    </h3>
                    <div class="space-y-6">
                        <!-- Loop through replies -->
                        <div v-for="reply in ticket.replies" :key="reply.id" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <p class="font-bold">{{ reply.user.name }}</p>
                            <p class="text-xs text-gray-500 mb-2">{{ new Date(reply.created_at).toLocaleString() }}</p>
                            <div class="prose max-w-none">
                                <p>{{ reply.content }}</p>
                            </div>
                        </div>

                        <!-- No replies message -->
                        <div v-if="!ticket.replies.length" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <p>No replies yet.</p>
                        </div>
                    </div>
                </div>

                <!-- Reply Form (we will make this functional later) -->
                <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                     <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">
                        Add a Reply
                    </h3>
                    <form>
                        <textarea rows="5" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Type your reply..."></textarea>
                        <div class="flex justify-end mt-4">
                             <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md">
                                Submit Reply
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </AppLayout>
</template>