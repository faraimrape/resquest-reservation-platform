<template>
    <Head title="Manage Properties" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Properties</h2>
        </template>

        <div class="flex">
            <!-- Sidebar Menu -->
            <SidebarMenu />
            <!-- Main Content Area -->
            <main class="flex-1 p-6 bg-gray-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h1 class="text-2xl font-bold mb-6">Manage Properties</h1>

                        <!-- Add Property Button -->
                        <div v-if="!properties || properties.length === 0" class="text-red-600 mb-4">
                            No properties found. Please add some properties.
                        </div>

                        <div class="mb-4">
                            <!-- Button to Add New Property -->
                            <Link :href="`/properties/create`" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Add New Property</Link>
                        </div>

                        <!-- Properties Table -->
                        <div v-if="properties.length > 0">
                            <table class="min-w-full bg-white shadow-md rounded-lg">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left">Property Name</th>
                                    <th class="px-6 py-3 text-left">Location</th>
                                    <th class="px-6 py-3 text-left">Description</th>
                                    <th class="px-6 py-3 text-left">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="property in properties" :key="property.id">
                                    <td class="px-6 py-4">{{ property.name }}</td>
                                    <td class="px-6 py-4">{{ property.location }}</td>
                                    <td class="px-6 py-4">{{ property.description }}</td>
                                    <td class="px-6 py-4 flex items-center space-x-4">
                                        <!-- Edit Property Button -->
                                        <Link :href="`/properties/${property.id}/edit`" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Update</Link>
                                        <!-- Manage Rooms Button -->
                                        <Link :href="`/properties/${property.id}/rooms`" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">See Rooms</Link>
                                        <!-- Delete Property Button -->
                                        <button @click="deleteProperty(property.id)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import {usePage, Link, Head} from '@inertiajs/vue3'; // Ensure the correct import of Link
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';

// Access properties data
const properties = usePage().props.properties ?? [];
const deleteProperty = (id) => {
    if (confirm('Are you sure you want to delete this property?')) {
        Inertia.delete(`/properties/${id}`);
    }
}
</script>
