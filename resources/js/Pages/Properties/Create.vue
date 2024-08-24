<template>
    <Head title="Create Property" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Property</h2>
        </template>

        <div class="flex">
            <SidebarMenu />
            <main class="flex-1 p-6 bg-gray-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow-md rounded-lg p-6 text-sm">
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <!-- Name Field -->
                            <div class="mb-6">
                                <label for="name" class="block font-semibold text-gray-700 mb-2">Property name</label>
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

                            <!-- Image Upload Field -->
                            <div class="mb-6">
                                <label for="image" class="block font-semibold text-gray-700 mb-2">Property Image</label>
                                <input @change="handleFileChange" id="image" type="file" accept="image/*" class="border-gray-300 border rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <span v-if="form.errors.image" class="text-red-600 mt-1 block">{{ form.errors.image }}</span>
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
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Create Property</button>
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
import { useForm, Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';

const form = useForm({
    name: '',
    location: '',
    description: '',
    image: null,
    is_published: '1',
});

const handleFileChange = (event) => {
    form.image = event.target.files[0];
};

const submit = () => {
    form.post('/properties', {
        onSuccess: () => form.reset(),
    });
};
</script>
