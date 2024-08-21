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
                <table v-else class="min-w-full bg-white shadow-md rounded">
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
                        <td class="px-6 py-4">
                            <button class="text-blue-500 mr-2" @click="approveReservation(reservation.id)">Approve</button>
                            <button class="text-red-500" @click="rejectReservation(reservation.id)">Reject</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';
import { Head } from '@inertiajs/vue3';

// Access reservations directly from Inertia props
const reservations = usePage().props.reservations ?? [];

// Approve and reject reservation functions
const approveReservation = (id) => {
    Inertia.post(`/reservations/${id}/approve`);
}

const rejectReservation = (id) => {
    Inertia.post(`/reservations/${id}/reject`);
}
</script>

