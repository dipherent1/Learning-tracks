<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3'; // <-- Add this import


// The controller is passing a 'tickets' prop, so we need to define it
const props = defineProps({
    tickets: Object,
});
</script>

<template>
    <AppLayout title="All Tickets">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-slate-900 leading-tight">Support Tickets</h2>

                <div class="flex items-center gap-2">
                    <Link :href="route('tickets.create')">
                        <button class="btn-primary">Create Ticket</button>
                    </Link>
                </div>
            </div>
        </template>
        

        <div class="py-6">
            <div class="container-pro">
                <div class="card overflow-hidden">
                    <div class="p-4 border-b border-gray-100">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-full">
                                <thead>
                                    <tr class="text-sm text-slate-600">
                                        <th class="px-4 py-3">#</th>
                                        <th class="px-4 py-3">Title</th>
                                        <th class="px-4 py-3">Requester</th>
                                        <th class="px-4 py-3">Department</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Created</th>
                                        <th class="px-4 py-3">Action</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-surface">
                                    <tr v-for="ticket in tickets.data" :key="ticket.id" class="border-t hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-slate-700">{{ ticket.id }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-slate-900">
                                            <div class="truncate-title" :title="ticket.title">{{ ticket.title }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-700">{{ ticket.user?.name ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-700">{{ ticket.department?.name ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <StatusBadge :status="ticket.status" />
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-500">{{ new Date(ticket.created_at).toLocaleString() }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <Link :href="route('tickets.show', ticket.id)" class="text-primary-600 hover:underline">View</Link>
                                        </td>
                                    </tr>

                                    <tr v-if="tickets.data.length === 0">
                                        <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">No tickets found. Create a new one.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>