
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    tickets: Object,
});
</script>

<template>
    <AppLayout title="Agent Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Agent Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="p-4">ID</th>
                                    <th class="p-4">Title</th>
                                    <th class="p-4">Organization</th>
                                    <th class="p-4">User</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Created On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <Link as="tr" v-for="ticket in tickets.data" :key="ticket.id" :href="route('tickets.show', ticket.id)" class="border-b hover:bg-gray-100 cursor-pointer">
                                    <td class="p-4">{{ ticket.id }}</td>
                                    <td class="p-4">{{ ticket.title }}</td>
                                    <td class="p-4">{{ ticket.team.name }}</td>
                                    <td class="p-4">{{ ticket.user.name }}</td>
                                    <td class="p-4 capitalize">{{ ticket.status.replace('_', ' ') }}</td>
                                    <td class="p-4">{{ new Date(ticket.created_at).toLocaleString() }}</td>
                                </Link>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>