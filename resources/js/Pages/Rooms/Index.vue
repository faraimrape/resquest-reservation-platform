
<template>
    <Head title="Manage Rooms" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Rooms</h2>
        </template>
        <div class="flex">
            <!-- Sidebar Menu -->
            <SidebarMenu />
            <!-- Main Content Area -->
            <main class="flex-1 p-6 bg-gray-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <!-- Rooms Table -->
                        <h1 class="text-2xl font-bold mb-6">Manage Rooms</h1>

                        <div v-if="rooms.length === 0" class="text-red-600">
                            No rooms found. Please add some rooms.
                        </div>

                        <div v-else>
                            <div class="mb-4">
                                <Link href="/rooms/create" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Add New Room</Link>
                            </div>
                            <table  aria-label="Rooms view" class="min-w-full bg-white shadow-md rounded-lg">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left">Room Name</th>
                                    <th class="px-6 py-3 text-left">Property</th>
                                    <th class="px-6 py-3 text-left">Capacity</th>
                                    <th class="px-6 py-3 text-left">Price Per Night</th>
                                    <th class="px-6 py-3 text-left">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="room in rooms" :key="room.id">
                                    <td class="px-6 py-4">{{ room.name }}</td>
                                    <td class="px-6 py-4">{{ room.property.name }}</td>
                                    <td class="px-6 py-4">{{ room.capacity }}</td>
                                    <td class="px-6 py-4">${{ room.price_per_night }}</td>
                                    <td class="px-6 py-4">
                                        <Link :href="`/rooms/${room.id}/edit`" class="text-blue-600 hover:text-blue-900">Edit</Link>
                                        <Link :href="`/rooms/${room.id}/delete`" class="ml-4 text-red-600 hover:text-red-900">Delete</Link>
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
import SidebarMenu from '@/Components/SideMenu.vue';
// Access rooms data directly from the Inertia props
const rooms = usePage().props.rooms ?? [];
</script>
