<template>
    <Head title="Update Property" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Property</h2>
        </template>

        <div class="flex">
            <SidebarMenu />
            <main class="flex-1 p-6 bg-gray-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <form @submit.prevent="submit">
                            <!-- Name Field -->
                            <div class="mb-6">
                                <label for="name" class="block font-semibold text-gray-700 mb-2">Name</label>
                                <input v-model="form.name" id="name" type="text" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                <span v-if="form.errors.name" class="text-red-600 mt-1 block">{{ form.errors.name }}</span>
                            </div>

                            <!-- Location Field -->
                            <div class="mb-6">
                                <label for="location" class="block font-semibold text-gray-700 mb-2">Location</label>
                                <input v-model="form.location" id="location" type="text" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                <span v-if="form.errors.location" class="text-red-600 mt-1 block">{{ form.errors.location }}</span>
                            </div>

                            <!-- Description Field -->
                            <div class="mb-6">
                                <label for="description" class="block font-semibold text-gray-700 mb-2">Description</label>
                                <textarea v-model="form.description" id="description" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                <span v-if="form.errors.description" class="text-red-600 mt-1 block">{{ form.errors.description }}</span>
                            </div>

                            <!-- is_published Dropdown Field -->
                            <div class="mb-6">
                                <label for="is_published" class="block font-semibold text-gray-700 mb-2">Is Published</label>
                                <select v-model="form.is_published" id="is_published" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                <span v-if="form.errors.is_published" class="text-red-600 mt-1 block">{{ form.errors.is_published }}</span>
                            </div>

                            <!-- Submit and Cancel Buttons -->
                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Update Property</button>
                                <Link href="/properties" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Cancel</Link>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm, Link, Head, usePage } from '@inertiajs/vue3';
import { useToast } from "vue-toastification";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';

const { props } = usePage();
const toast = useToast();

// Pre-populate the form with the existing property data
const form = useForm({
    name: props.property.name,
    location: props.property.location,
    description: props.property.description,
    is_published: props.property.is_published ? '1' : '0', // Map to 1 or 0 for the dropdown
});

const submit = () => {
    form.put(`/properties/${props.property.id}`, {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
