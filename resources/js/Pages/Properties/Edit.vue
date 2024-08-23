<template>
    <Head title="Edit Property" />
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
                            <div class="mb-4">
                                <label for="name" class="block font-bold text-gray-700">Name</label>
                                <input v-model="form.name" id="name" type="text" class="mt-1 block w-full" required>
                                <span v-if="form.errors.name" class="text-red-600">{{ form.errors.name }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="location" class="block font-bold text-gray-700">Location</label>
                                <input v-model="form.location" id="location" type="text" class="mt-1 block w-full" required>
                                <span v-if="form.errors.location" class="text-red-600">{{ form.errors.location }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block font-bold text-gray-700">Description</label>
                                <textarea v-model="form.description" id="description" class="mt-1 block w-full"></textarea>
                                <span v-if="form.errors.description" class="text-red-600">{{ form.errors.description }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update Property</button>
                                <Link href="/properties" class="text-gray-600 hover:underline">Cancel</Link>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import {useForm, Link, usePage, Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';

const { property } = usePage().props;

const form = useForm({
    name: property.name,
    location: property.location,
    description: property.description || '',
});

const submit = () => {
    form.put(`/properties/${property.id}`, {
        onSuccess: () => form.reset(), // Reset form after success
    });
}
</script>
