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
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <!-- Properties Table -->
                        <h1 class="text-2xl font-bold mb-6">Manage Properties</h1>

                        <div v-if="!properties || properties.length === 0" class="text-red-600">
                            No properties found. Please add some properties.
                        </div>

                        <div v-else>
                            <div class="mb-4">
                                <Link href="/properties/create" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Add New Property</Link>
                            </div>
                            <table class="min-w-full bg-white shadow-md rounded-lg">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left">Property Name</th>
                                    <th class="px-6 py-3 text-left">Location</th>
                                    <th class="px-6 py-3 text-left">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="property in properties" :key="property.id">
                                    <td class="px-6 py-4">{{ property.name }}</td>
                                    <td class="px-6 py-4">{{ property.location }}</td>
                                    <td class="px-6 py-4">
                                        <Link :href="`/properties/${property.id}/edit`" class="text-blue-600 hover:text-blue-900">Edit</Link>
                                        <Link :href="`/properties/${property.id}/rooms`" class="ml-4 text-green-600 hover:text-green-900">Manage Rooms</Link>
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import SidebarMenu from "@/Components/SideMenu.vue";

// Access properties without using reactivity or refs
const properties = usePage().props.properties ?? [];
</script>
