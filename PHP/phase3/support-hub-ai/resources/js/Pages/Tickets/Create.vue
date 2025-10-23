<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

// The controller is passing a 'departments' prop
const props = defineProps({
    departments: Array,
});

// Inertia's form helper. It wraps our form data and provides methods.
const form = useForm({
    title: '',
    department_id: null,
    content: '',
});

// The function to call when the form is submitted
const submit = () => {
    form.post(route('tickets.store'), {
        onSuccess: () => form.reset(), // Reset form fields on success
    });
};
</script>

<template>
    <AppLayout title="Create Ticket">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create a New Support Ticket
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block font-medium text-sm text-gray-700">Title</label>
                                <input id="title" v-model="form.title" type="text" class="block w-full mt-1" required>
                                <div v-if="form.errors.title" class="text-sm text-red-600 mt-1">{{ form.errors.title }}</div>
                            </div>

                            <!-- Department -->
                            <div class="mt-4">
                                <label for="department" class="block font-medium text-sm text-gray-700">Department</label>
                                <select id="department" v-model="form.department_id" class="block w-full mt-1" required>
                                    <option :value="null" disabled>Select a department</option>
                                    <option v-for="department in departments" :key="department.id" :value="department.id">
                                        {{ department.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.department_id" class="text-sm text-red-600 mt-1">{{ form.errors.department_id }}</div>
                            </div>

                            <!-- Content -->
                            <div class="mt-4">
                                <label for="content" class="block font-medium text-sm text-gray-700">Details</label>
                                <textarea id="content" v-model="form.content" rows="5" class="block w-full mt-1" required></textarea>
                                <div v-if="form.errors.content" class="text-sm text-red-600 mt-1">{{ form.errors.content }}</div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end mt-4">
                                <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-gray-800 text-white rounded-md">
                                    Create Ticket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>