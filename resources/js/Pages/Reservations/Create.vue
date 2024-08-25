<template>
    <Head title="Create Reservation" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Reservation</h2>
        </template>

        <div class="flex">
            <SidebarMenu />
            <main class="flex-1 p-6 bg-gray-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow-md rounded-lg p-6 text-sm">
                        <form @submit.prevent="submit">
                            <!-- Property Selection -->
                            <div class="mb-4">
                                <label for="property_id" class="block font-bold text-gray-700">Property</label>
                                <select v-model="selectedPropertyId" id="property_id" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" @change="fetchRooms">
                                    <option value="">Select a property</option>
                                    <option v-for="property in properties" :key="property.id" :value="property.id">
                                        {{ property.name }}
                                    </option>
                                </select>
                                <span v-if="form.errors.property_id" class="text-red-600">{{ form.errors.property_id }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="room_id" class="block font-bold text-gray-700">Room</label>
                                <select v-model="form.room_id" id="room_id" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">Select a room</option>
                                    <option v-for="room in filteredRooms" :key="room.id" :value="room.id">
                                        {{ room.name }}
                                    </option>
                                </select>
                                <span v-if="form.errors.room_id" class="text-red-600">{{ form.errors.room_id }}</span>
                            </div>

                            <!-- Guest Details -->
                            <div class="mb-4">
                                <h3 class="font-bold text-gray-700 mb-2">Guest Details</h3>
                                <div v-for="(guest, index) in form.guest_details" :key="index" class="mb-4 border-b pb-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label :for="'guest_first_name_' + index" class="block font-bold text-gray-700">First Name</label>
                                            <input
                                                v-model="guest.first_name"
                                                :id="'guest_first_name_' + index"
                                                type="text"
                                                class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label :for="'guest_last_name_' + index" class="block font-bold text-gray-700">Last Name</label>
                                            <input
                                                v-model="guest.last_name"
                                                :id="'guest_last_name_' + index"
                                                type="text"
                                                class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label :for="'guest_email_' + index" class="block font-bold text-gray-700">Email</label>
                                            <input
                                                v-model="guest.email"
                                                :id="'guest_email_' + index"
                                                type="email"
                                                class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label :for="'guest_phone_' + index" class="block font-bold text-gray-700">Phone</label>
                                            <input
                                                v-model="guest.phone"
                                                :id="'guest_phone_' + index"
                                                type="text"
                                                class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <button @click.prevent="removeGuest(index)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700 mt-4">
                                        Remove Guest
                                    </button>
                                </div>
                                <button @click.prevent="addGuest" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Add Another Guest
                                </button>
                            </div>

                            <!-- Check-In Date -->
                            <div class="mb-4">
                                <label for="check_in" class="block font-bold text-gray-700">Check-In Date</label>
                                <input
                                    v-model="form.check_in"
                                    id="check_in"
                                    type="date"
                                    class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                />
                                <span v-if="form.errors.check_in" class="text-red-600">{{ form.errors.check_in }}</span>
                            </div>

                            <!-- Check-Out Date -->
                            <div class="mb-4">
                                <label for="check_out" class="block font-bold text-gray-700">Check-Out Date</label>
                                <input
                                    v-model="form.check_out"
                                    id="check_out"
                                    type="date"
                                    class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                />
                                <span v-if="form.errors.check_out" class="text-red-600">{{ form.errors.check_out }}</span>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Create Reservation</button>
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
import { ref, computed } from 'vue';
import { useForm,usePage, Link, Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';
import {useToast} from "vue-toastification";
const toast = useToast();

// Form data with dynamic guest details
const form = useForm({
    property_id: '',
    room_id: '',
    check_in: '',
    check_out: '',
    guest_details: [
        { first_name: '', last_name: '', email: '', phone: '' },
    ],
});
const { props } = usePage();
const selectedPropertyId = ref('');

const filteredRooms = computed(() => {
    if (!selectedPropertyId.value) return [];
    const selectedProperty = properties.find(property => property.id === parseInt(selectedPropertyId.value));
    return selectedProperty ? selectedProperty.rooms : [];
});

const properties = usePage().props.properties ?? [];
const rooms = ref([]);

const fetchRooms = async () => {
    if (form.property_id) {
        try {
            const response = await Inertia.get(`/properties/${form.property_id}/rooms`, {}, { preserveState: true });
            rooms.value = response.props.rooms;
        } catch (error) {
            console.error('Failed to fetch rooms:', error);
        }
    } else {
        rooms.value = [];
    }
};

const addGuest = () => {
    form.guest_details.push({ first_name: '', last_name: '', email: '', phone: '' });
};

const removeGuest = (index) => {
    if (form.guest_details.length > 1) {
        form.guest_details.splice(index, 1);
    }
};

const submit = () => {
    form.post('/reservations/backend', {
        onSuccess: () => {
            form.reset();
            toast.success('Reservations created successfully.');
        },
    });
};
</script>

