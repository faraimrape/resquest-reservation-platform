<template>
    <Head title="Manage Guests" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Guests</h2>
        </template>

        <div class="flex">
            <!-- Sidebar Menu -->
            <SidebarMenu />

            <!-- Main Content Area -->
            <div class="container mx-auto p-6">
                <h1 class="text-3xl font-bold mb-4">Guests</h1>

                <!-- Check if there are any guests -->
                <div v-if="!guests || guests.length === 0" class="text-red-600">
                    No guests found.
                </div>

                <!-- Guests Table -->
                <table v-else class="min-w-full bg-white shadow-md rounded">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 text-left">First Name</th>
                        <th class="px-6 py-3 text-left">Last Name</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Phone</th>
                        <th class="px-6 py-3 text-left">Reservation ID</th>
                        <th class="px-6 py-3 text-left">Room</th>
                        <th class="px-6 py-3 text-left">Property</th>
                        <th class="px-6 py-3 text-left">Check-In</th>
                        <th class="px-6 py-3 text-left">Check-Out</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="guest in guests" :key="guest.id">
                        <td class="px-6 py-4">{{ guest.first_name }}</td>
                        <td class="px-6 py-4">{{ guest.last_name }}</td>
                        <td class="px-6 py-4">{{ guest.email }}</td>
                        <td class="px-6 py-4">{{ guest.phone }}</td>
                        <td class="px-6 py-4">{{ guest.reservation_id }}</td>
                        <td class="px-6 py-4">{{ guest.reservation.room.name }}</td>
                        <td class="px-6 py-4">{{ guest.reservation.room.property.name }}</td>
                        <td class="px-6 py-4">{{ guest.reservation.check_in }}</td>
                        <td class="px-6 py-4">{{ guest.reservation.check_out }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';
import { Head } from '@inertiajs/vue3';
const { guests } = usePage().props;
</script>
