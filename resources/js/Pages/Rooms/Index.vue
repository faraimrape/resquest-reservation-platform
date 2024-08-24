<template>
    <Head title="Manage Rooms" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Rooms</h2>
        </template>

        <div class="flex">
            <SidebarMenu />
            <main class="flex-1 p-6 bg-gray-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h1 class="text-2xl font-bold mb-6">Manage Rooms</h1>
                        <div v-if="!rooms || rooms.length === 0" class="text-red-600 mb-4">
                            No rooms found. Please add some rooms.
                        </div>

                        <div class="mb-4">
                            <Link :href="`/rooms/create`" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Add New Room</Link>
                        </div>

                        <div v-if="rooms.length > 0">
                            <table class="min-w-full bg-white shadow-md rounded-lg text-sm">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left">Room Name</th>
                                    <th class="px-6 py-3 text-left">Property</th>
                                    <th class="px-6 py-3 text-left">Capacity</th>
                                    <th class="px-6 py-3 text-left">Price/Night</th>
                                    <th class="px-6 py-3 text-left">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="room in rooms" :key="room.id">
                                    <td class="px-6 py-4">{{ room.name }}</td>
                                    <td class="px-6 py-4">{{ room.property.name }}</td>
                                    <td class="px-6 py-4">{{ room.capacity }}</td>
                                    <td class="px-6 py-4">{{ room.price_per_night }}</td>
                                    <td class="px-6 py-4 flex items-center space-x-4">
                                        <Link :href="`/rooms/${room.id}/edit`" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Update</Link>
                                        <button @click.prevent="deleteRoom(room.id)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
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
import { usePage, Link, Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';
import { onMounted } from 'vue';
import { useToast } from "vue-toastification";


const { props } = usePage();
const rooms = props.rooms ?? [];
const toast = useToast();

const deleteRoom = (id) => {
    if (confirm('Are you sure you want to delete this room?')) {
        Inertia.delete(`/rooms/${id}`, {
            onSuccess: () => {
                toast.success('Room deleted successfully.');
            },
            onError: (error) => {
                toast.error('Failed to delete room.');
            }
        });
    }
};

// Safely check if the flash message exists
onMounted(() => {
    if (props.flash?.success) {
        toast.success(props.flash.success);
    }
});
</script>
