<template>
    <Head title="Manage Reservations" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Reservations</h2>
        </template>

        <div class="flex">
            <SidebarMenu />
            <main class="flex-1 p-6 bg-gray-100 text-sm">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h1 class="text-2xl font-bold mb-4">Manage Reservations</h1>

                        <!-- Search Box and Add Button in the same row -->
                        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                            <input
                                v-model="filters.search"
                                @input="debouncedSearch"
                                type="text"
                                placeholder="Search reservations..."
                                class="border border-gray-300 rounded px-4 py-2 mb-4 md:mb-0 w-full md:w-1/2"
                            />
                            <Link href="/reservations/create" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Add Reservation</Link>
                        </div>

                        <div v-if="!reservations.data || reservations.data.length === 0" class="text-red-600">
                            No reservations found.
                        </div>
                        <div class="overflow-x-auto">
                        <table v-if="reservations.data && reservations.data.length > 0" class="border min-w-full bg-white shadow-md rounded-lg text-sm rounded-md">
                            <thead>
                            <tr>
                                <th class="px-2 py-3 text-left">Name</th>
                                <th class="px-6 py-3 text-left">Room</th>
                                <th class="px-6 py-3 text-left">Property</th>
                                <th class="px-6 py-3 text-left">Checkin</th>
                                <th class="px-6 py-3 text-left">Checkout</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="reservation in reservations.data" :key="reservation.id">
                                <td class="px-2 py-4">
                                    {{ reservation.guests?.length > 0 ? `${reservation.guests[0].first_name} ${reservation.guests[0].last_name}` : 'No Guests' }}
                                </td>
                                <td class="px-6 py-4">{{ reservation.room.name }}</td>
                                <td class="px-6 py-4">{{ reservation.room.property.name }}</td>
                                <td class="px-6 py-4">{{ reservation.check_in }}</td>
                                <td class="px-6 py-4">{{ reservation.check_out }}</td>
                                <td class="px-6 py-4">
                                    <span :class="['px-2 py-1 rounded-full text-white', reservation.is_approved ? 'bg-green-500' : 'bg-red-500']">
                                        {{ reservation.is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-2 py-4 flex flex-col space-y-2 md:space-y-0 md:flex-row md:space-x-2">
                                    <Link :href="`/reservations/${reservation.id}/edit`" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Update</Link>
                                    <button @click.prevent="deleteReservation(reservation.id)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
                                    <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700" @click="approveReservation(reservation.id)">Approve</button>
                                    <button class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-700" @click="rejectReservation(reservation.id)">Reject</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        </div>

                        <!-- Pagination Controls -->
                        <div class="mt-6 text-sm">
                            <Link
                                v-if="reservations.prev_page_url"
                                :href="reservations.prev_page_url"
                                @click.prevent="changePage(reservations.prev_page_url)"
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700"
                            >
                                Previous Page
                            </Link>
                            <Link
                                v-if="reservations.next_page_url"
                                :href="reservations.next_page_url"
                                @click.prevent="changePage(reservations.next_page_url)"
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700"
                            >
                                Next Page
                            </Link>
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
import { useToast } from "vue-toastification";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';
import { debounce } from 'lodash';

const { props } = usePage();
const reservations = ref(props.reservations);
const filters = ref({
    search: props.filters.search || ''
});
const toast = useToast();

// Debounced search function to avoid unnecessary requests
const debouncedSearch = debounce(() => {
    Inertia.get('/reservations', filters.value, {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            reservations.value = page.props.reservations;
        }
    });
}, 900);

const changePage = (url) => {
    Inertia.get(url, {}, {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            reservations.value = page.props.reservations;
        }
    });
};

// Approve and reject reservation functions
const approveReservation = (id) => {
    Inertia.post(`/reservations/${id}/approve`, {}, {
        onSuccess: () => {
            toast.success('Reservation approved successfully.');
        },
        onError: () => {
            toast.error('Failed to approve reservation.');
        }
    });
};

const rejectReservation = (id) => {
    Inertia.post(`/reservations/${id}/reject`, {}, {
        onSuccess: () => {
            toast.success('Reservation rejected successfully.');
        },
        onError: () => {
            toast.error('Failed to reject reservation.');
        }
    });
};

const deleteReservation = (id) => {
    Inertia.delete(`/reservations/${id}`, {
        onSuccess: () => {
            toast.success('Reservation deleted successfully.');
        },
        onError: () => {
            toast.error('Failed to delete reservation.');
        }
    });
};

watch(filters, debouncedSearch, {deep: true});
</script>
