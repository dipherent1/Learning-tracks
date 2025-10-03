<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps } from 'vue';

// The controller is passing a 'tickets' prop, so we need to define it
const props = defineProps({
    tickets: Object,
});
</script>

<template>
    <AppLayout title="All Tickets">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Support Tickets
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- This will be our tickets table -->
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="p-4">ID</th>
                                    <th class="p-4">Title</th>
                                    <th class="p-4">User</th>
                                    <th class="p-4">Department</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Created On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="ticket in tickets.data" :key="ticket.id" class="border-b">
                                    <td class="p-4">{{ ticket.id }}</td>
                                    <td class="p-4">{{ ticket.title }}</td>
                                    <td class="p-4">{{ ticket.user.name }}</td>
                                    <td class="p-4">{{ ticket.department.name }}</td>
                                    <td class="p-4">{{ ticket.status }}</td>
                                    <td class="p-4">{{ new Date(ticket.created_at).toLocaleString() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>