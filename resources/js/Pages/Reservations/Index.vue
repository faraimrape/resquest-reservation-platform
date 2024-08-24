<template>
    <Head title="Manage Reservations" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Reservations</h2>
        </template>

        <div class="flex">
            <!-- Sidebar Menu -->
            <SidebarMenu />

            <!-- Main Content Area -->
            <div class="container mx-auto p-6">
                <h1 class="text-3xl font-bold mb-4">Reservations</h1>

                <!-- Check if there are any reservations -->
                <div v-if="!reservations || reservations.length === 0" class="text-red-600">
                    No reservations found.
                </div>

                <!-- Reservations Table -->
                <table v-else class="min-w-full bg-white shadow-md rounded text-sm">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 text-left">Guest Name</th>
                        <th class="px-6 py-3 text-left">Room</th>
                        <th class="px-6 py-3 text-left">Property</th>
                        <th class="px-6 py-3 text-left">Check-In</th>
                        <th class="px-6 py-3 text-left">Check-Out</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="reservation in reservations" :key="reservation.id">
                        <td class="px-6 py-4">
                            <!-- Safely check if guests array exists and has at least one element -->
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
                        <td class="px-6 py-4 flex items-center space-x-4">
                            <Link :href="`/reservations/${reservation.id}/edit`" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</Link>
                            <button @click.prevent="deleteReservation(reservation.id)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
                            <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700" @click="approveReservation(reservation.id)">Approve</button>
                            <button class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-700" @click="rejectReservation(reservation.id)">Reject</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { usePage, Link, Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { onMounted } from 'vue';
import SidebarMenu from '@/Components/SideMenu.vue';
import { useToast } from "vue-toastification";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
const reservations = props.reservations ?? [];
const toast = useToast();

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
}

const rejectReservation = (id) => {
    Inertia.post(`/reservations/${id}/reject`, {}, {
        onSuccess: () => {
            toast.success('Reservation rejected successfully.');
        },
        onError: () => {
            toast.error('Failed to reject reservation.');
        }
    });
}

// Delete reservation function
const deleteReservation = (id) => {
    if (confirm('Are you sure you want to delete this reservation?')) {
        Inertia.delete(`/reservations/${id}`, {
            onSuccess: () => {
                toast.success('Reservation deleted successfully.');
            },
            onError: () => {
                toast.error('Failed to delete reservation.');
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
