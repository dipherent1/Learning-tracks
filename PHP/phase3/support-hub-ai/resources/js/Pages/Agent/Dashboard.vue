<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps, reactive, watch } from 'vue'; // <-- Import reactive and watch
import { Link, router } from '@inertiajs/vue3'; // <-- Import router
import StatusBadge from '@/Components/StatusBadge.vue';

const props = defineProps({
    tickets: Object,
    filters: Object, // <-- The current filters
    departments: Array, // <-- The list of all departments
    pageTitle: { // <-- Define the new prop
        type: String,
        default: 'Agent Dashboard', // Provide a default value
    },

});

// Create a reactive form object to hold the filter values.
// Initialize it with the values passed from the controller.
const filterForm = reactive({
    status: props.filters.status,
    department_id: props.filters.department_id,
});

// Use a watcher to automatically submit the form when a filter changes.
watch(filterForm, () => {
    router.get(route('agent.dashboard'), filterForm, {
        preserveState: true, // Keep component state (like scroll position)
        replace: true,       // Don't create new browser history entries
    });
}, { deep: true }); // 'deep' is needed to watch for changes inside the object

</script>

<template>
    <AppLayout title="pageTitle">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ pageTitle }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- FILTER SECTION (NEW) -->
                    <div class="p-6 bg-gray-50 border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
                                <select id="status" v-model="filterForm.status" class="block w-full mt-1">
                                    <option :value="null">All</option>
                                    <option value="open">Open</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            <div>
                                <label for="department" class="block font-medium text-sm text-gray-700">Department</label>
                                <select id="department" v-model="filterForm.department_id" class="block w-full mt-1">
                                    <option :value="null">All</option>
                                    <option v-for="department in departments" :key="department.id" :value="department.id">
                                        {{ department.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- TABLE SECTION -->
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <table class="w-full text-left">
                            <!-- ... thead is unchanged ... -->
                            <tbody>
                                <Link as="tr" v-for="ticket in tickets.data" :key="ticket.id" :href="route('tickets.show', ticket.id)" class="border-b hover:bg-gray-100 cursor-pointer">
                                    <td class="p-4">{{ ticket.id }}</td>
                                    <td class="p-4">{{ ticket.title }}</td>
                                    <td class="p-4">{{ ticket.team.name }}</td>
                                    <td class="p-4">{{ ticket.user.name }}</td>
                                    <td class="p-4">
                                        <StatusBadge :status="ticket.status" />
                                    </td>
                                    <td class="p-4">{{ new Date(ticket.created_at).toLocaleString() }}</td>
                                </Link>
                                <!-- No results message -->
                                <tr v-if="tickets.data.length === 0">
                                    <td colspan="6" class="p-4 text-center text-gray-500">No tickets found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- PAGINATION (We need to add this) -->
                    <div v-if="tickets.links.length > 3" class="p-6 bg-gray-50 border-t">
                        <div class="flex flex-wrap -mb-1">
                            <template v-for="(link, key) in tickets.links" :key="key">
                                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="link.label" />
                                <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-white': link.active }" :href="link.url" v-html="link.label" />
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>