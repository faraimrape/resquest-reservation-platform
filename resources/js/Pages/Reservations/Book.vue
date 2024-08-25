<template>
    <Head title="Create Reservation" />
    <div class="max-w-7xl mx-auto p-6 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6 mt-4">
                <h2 class="font-semibold text-xl text-gray-800">Complete your reservation</h2>
                <div class="flex space-x-4">
                    <button
                        @click="Inertia.get('/login')"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        Administration
                    </button>
                    <button
                        @click="Inertia.get('/')"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700"
                    >
                        Home
                    </button>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6 text-sm">

                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">Create Reservation</h2>
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

                    <!-- Display Property Image -->
                    <div v-if="selectedPropertyId" class="mb-4">
                        <img
                            :src="getImageUrl(getSelectedProperty()?.image_url, 'property')"
                            alt="Property Image"
                            class=" h-60 w-90 object-cover rounded-lg"
                        />
                    </div>

                    <!-- Room Selection -->
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

                    <!-- Display Room Image -->
                    <div v-if="form.room_id" class="mb-4">
                        <img
                            :src="getImageUrl(getSelectedRoom()?.image_url, 'room')"
                            alt="Room Image"
                            class="h-60 w-90 object-cover rounded-lg"
                        />
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
                        <Link href="/" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Go Back</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useForm, usePage, Link, Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

// Placeholder images for properties and rooms without images
const placeholderPropertyImage = '/images/default-property.jpg';
const placeholderRoomImage = '/images/default-room.png';

const form = useForm({
    property_id: '',
    room_id: '',
    check_in: '',
    check_out: '',
    guest_details: [
        {first_name: '', last_name: '', email: '', phone: ''},
    ],
});

const {props} = usePage();
const properties = ref(props.properties ?? []);
const selectedPropertyId = ref('');

// Auto-populate dropdowns based on query parameters
onMounted(() => {
    const queryParams = new URLSearchParams(window.location.search);
    const propertyId = queryParams.get('property_id');
    const roomId = queryParams.get('room_id');

    if (propertyId) {
        selectedPropertyId.value = propertyId;
        form.property_id = propertyId;
        fetchRooms().then(() => {
            if (roomId) {
                form.room_id = roomId;
            }
        });
    }
});

const filteredRooms = computed(() => {
    const selectedProperty = properties.value.find(property => property.id === parseInt(selectedPropertyId.value));
    return selectedProperty ? selectedProperty.rooms : [];
});

// Fetch rooms when a property is selected
const fetchRooms = async () => {
    const selectedProperty = properties.value.find(property => property.id === parseInt(selectedPropertyId.value));
    if (selectedProperty && selectedProperty.rooms) {
        form.room_id = ''; // Reset the room selection when a property changes
        return;
    }
};

// Helper to get the selected property
const getSelectedProperty = () => {
    return properties.value.find(property => property.id === parseInt(selectedPropertyId.value));
};

// Helper to get the selected room
const getSelectedRoom = () => {
    return filteredRooms.value.find(room => room.id === parseInt(form.room_id));
};

const getImageUrl = (imageUrl, type) => {
    if (imageUrl) {
        return `/storage/${imageUrl}`;
    }
    return type === 'property' ? placeholderPropertyImage : placeholderRoomImage;
};

const addGuest = () => {
    form.guest_details.push({first_name: '', last_name: '', email: '', phone: ''});
};

const removeGuest = (index) => {
    if (form.guest_details.length > 1) {
        form.guest_details.splice(index, 1);
    }
};

const submit = () => {
    form.post('/reservations/book', {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
