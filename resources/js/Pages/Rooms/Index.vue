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
                        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                            <input
                                v-model="filters.search"
                                @input="debouncedSearch"
                                type="text"
                                placeholder="Search rooms..."
                                class="border border-gray-300 rounded px-4 py-2 mb-4 md:mb-0 w-full md:w-1/2"
                            />
                            <Link href="/rooms/create"  class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Add New Room</Link>
                        </div>

                        <div v-if="!rooms.data || rooms.data.length === 0" class="text-red-600 mb-4">
                            No rooms found. Please add some rooms.
                        </div>


                        <div v-if="rooms.data && rooms.data.length > 0">
                            <div class="overflow-x-auto">
                            <table class="border min-w-full bg-white shadow-md rounded-lg text-sm rounded-md">
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
                                <tr v-for="room in rooms.data" :key="room.id">
                                    <td class="px-6 py-4">{{ room.name }}</td>
                                    <td class="px-6 py-4">{{ room.property.name }}</td>
                                    <td class="px-6 py-4">{{ room.capacity }}</td>
                                    <td class="px-6 py-4">{{ room.price_per_night }}</td>
                                    <td class="px-2 py-4 flex flex-col space-y-2 md:space-y-0 md:flex-row md:space-x-2">
                                        <Link :href="`/rooms/${room.id}/edit`" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Update</Link>
                                        <button @click.prevent="deleteRoom(room.id)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>

                            <!-- Pagination Controls -->
                            <div class="mt-6 text-sm">
                                <Link
                                    v-if="rooms.prev_page_url"
                                    :href="rooms.prev_page_url"
                                    @click.prevent="changePage(rooms.prev_page_url)"
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700"
                                >
                                    Previous Page
                                </Link>
                                <Link
                                    v-if="rooms.next_page_url"
                                    :href="rooms.next_page_url"
                                    @click.prevent="changePage(rooms.next_page_url)"
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700"
                                >
                                    Next Page
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { usePage, Link, Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';
import { useToast } from "vue-toastification";
import debounce from 'lodash/debounce';

const { props } = usePage();
const rooms = ref(props.rooms);
const filters = ref({
    search: props.filters.search || ''
});
const toast = useToast();

// Handle room deletion
const deleteRoom = (id) => {
    if (confirm('Are you sure you want to delete this room?')) {
        Inertia.delete(`/rooms/${id}`, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            onSuccess: () => {
                toast.success('Room deleted successfully.');
            },
            onError: () => {
                toast.error('Failed to delete room.');
            }
        });
    }
};

// Debounce the search functionality to prevent excessive requests
const debouncedSearch = debounce(() => {
    Inertia.get('/rooms', filters.value, {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            rooms.value = page.props.rooms;
        }
    });
}, 900); // Adjust the debounce time as needed

// Handle page changes for pagination
const changePage = (url) => {
    Inertia.get(url, {}, {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            rooms.value = page.props.rooms;
        }
    });
};

// Automatically trigger search when the input changes
watch(filters, debouncedSearch, { deep: true });
</script>
