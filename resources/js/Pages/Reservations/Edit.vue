<template>
    <Head title="Edit Reservation" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Reservation</h2>
        </template>

        <div class="flex">
            <SidebarMenu />

            <main class="flex-1 p-6 bg-gray-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow-md rounded-lg p-6 text-sm">
                        <form @submit.prevent="submit">
                            <!-- Guest Name -->
                            <div class="mb-4">
                                <label for="guest_name" class="block font-bold text-gray-700">Guest Name</label>
                                <input type="text" id="guest_name" :value="guestName" disabled  class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                            </div>

                            <!-- Property Dropdown -->
                            <div class="mb-4">
                                <label for="property" class="block font-bold text-gray-700">Property</label>
                                <select v-model="selectedPropertyId" @change="filterRoomsByProperty" id="property"  class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option v-for="property in properties" :key="property.id" :value="property.id">{{ property.name }}</option>
                                </select>
                                <span v-if="form.errors.property_id" class="text-red-600">{{ form.errors.property_id }}</span>
                            </div>

                            <!-- Room Dropdown -->
                            <div class="mb-4">
                                <label for="room" class="block font-bold text-gray-700">Room</label>
                                <select v-model="form.room_id" id="room"  class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option v-for="room in filteredRooms" :key="room.id" :value="room.id">{{ room.name }}</option>
                                </select>
                                <span v-if="form.errors.room_id" class="text-red-600">{{ form.errors.room_id }}</span>
                            </div>

                            <!-- Check-In Date -->
                            <div class="mb-4">
                                <label for="check_in" class="block font-bold text-gray-700">Check-In Date</label>
                                <input type="date" v-model="form.check_in" id="check_in"  class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                                <span v-if="form.errors.check_in" class="text-red-600">{{ form.errors.check_in }}</span>
                            </div>

                            <!-- Check-Out Date -->
                            <div class="mb-4">
                                <label for="check_out" class="block font-bold text-gray-700">Check-Out Date</label>
                                <input type="date" v-model="form.check_out" id="check_out"  class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                <span v-if="form.errors.check_out" class="text-red-600">{{ form.errors.check_out }}</span>
                            </div>

                            <!-- Approval Status -->
                            <div class="mb-4">
                                <label for="is_approved" class="block font-bold text-gray-700">Approval Status</label>
                                <select v-model="form.is_approved" id="is_approved" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="1">Approved</option>
                                    <option value="0">Pending</option>
                                </select>
                                <span v-if="form.errors.is_approved" class="text-red-600">{{ form.errors.is_approved }}</span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Update Reservation</button>
                                <Link href="/reservations" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Cancel</Link>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { useToast } from "vue-toastification";

const { props } = usePage();
const reservation = props.reservation;
const properties = props.properties ?? [];
const rooms = props.rooms ?? [];

const toast = useToast();

// Handle guest name
const guestName = computed(() => reservation.guests?.length > 0 ? `${reservation.guests[0].first_name} ${reservation.guests[0].last_name}` : 'No Guests');

// Form setup
const form = useForm({
    room_id: reservation.room_id,
    check_in: reservation.check_in,
    check_out: reservation.check_out,
    is_approved: reservation.is_approved,
});

const selectedPropertyId = ref(reservation.room.property_id);
const filteredRooms = ref([]);

// Filter rooms based on selected property
const filterRoomsByProperty = () => {
    filteredRooms.value = rooms.filter(room => room.property_id === selectedPropertyId.value);
};

// Populate rooms for the initially selected property
filterRoomsByProperty();

// Submit form
const submit = () => {
    form.put(`/reservations/${reservation.id}`, {
        onSuccess: () => {
            toast.success('Reservation updated successfully.');
            form.reset();
        },
        onError: (errors) => {
            toast.error('Failed to update reservation.');
        }
    });
};
</script>
