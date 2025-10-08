<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import StatusBadge from '@/Components/StatusBadge.vue';

defineProps({
    stats: Object,
    recentTickets: Array,
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stat Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-xl">
                        <h3 class="text-gray-500 text-sm font-medium">Open Tickets</h3>
                        <p class="text-3xl font-semibold text-gray-900">{{ stats.open_tickets }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-xl">
                        <h3 class="text-gray-500 text-sm font-medium">Total Tickets</h3>
                        <p class="text-3xl font-semibold text-gray-900">{{ stats.total_tickets }}</p>
                    </div>
                </div>

                <!-- Recent Tickets -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">
                            Your Most Recent Tickets
                        </h3>
                        <div v-if="recentTickets.length > 0">
                            <ul role="list" class="divide-y divide-gray-200">
                                <li v-for="ticket in recentTickets" :key="ticket.id" class="py-4">
                                    <Link :href="route('tickets.show', ticket.id)" class="flex space-x-3 group">
                                        <div class="flex-1 space-y-1">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-sm font-medium group-hover:text-indigo-600">{{ ticket.title }}</h3>
                                                <p class="text-sm text-gray-500">{{ new Date(ticket.created_at).toLocaleDateString() }}</p>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <p class="text-gray-500">#{{ ticket.id }} in {{ ticket.department.name }}</p>
                                                <StatusBadge :status="ticket.status" />
                                            </div>
                                        </div>
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <div v-else class="text-center py-8">
                            <p class="text-gray-500">You haven't created any tickets yet.</p>
                            <Link :href="route('tickets.create')" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                                Create Your First Ticket
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>