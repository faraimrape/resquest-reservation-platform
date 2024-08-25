<template>
    <Head title="Home" />
    <div class="max-w-7xl mx-auto p-6 bg-gray-100">
        <div class="flex justify-between items-center mb-6 mt-4">
            <h2 class="font-semibold text-xl text-gray-800">Search and book your property</h2>
            <div class="flex space-x-4">
                <Link
                    href="/login"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                    Administration
                </Link>
                <Link
                    href="/"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700"
                >
                    Home
                </Link>
            </div>
        </div>

        <div class="mb-6 flex flex-col md:flex-row justify-between items-center">
            <input
                v-model="searchQuery"
                @input="debouncedSearch"
                type="text"
                placeholder="Search properties..."
                class="border-gray-300 rounded px-4 py-2 w-full md:w-1/2 mb-4 md:mb-0"
            />
        </div>

        <!-- Property Listings -->
        <div v-if="filteredProperties.length > 0">
            <div
                v-for="property in filteredProperties"
                :key="property.id"
                class="bg-white shadow-md rounded-lg mb-6 p-4"
            >
                <div class="flex flex-col md:flex-row">
                    <div class="w-full md:w-1/3">
                        <img
                            :src="property.image_url ? `/storage/${property.image_url}` : '/images/default-property.jpg'"
                            alt="Property Image"
                            class="rounded-lg mb-4 md:mb-0"
                        />
                    </div>
                    <div class="w-full md:w-2/3 md:pl-6">
                        <h2 class="text-2xl font-bold">{{ property.name }}</h2>
                        <p class="text-gray-600">{{ property.location }}</p>
                        <p class="mt-2">{{ property.description }}</p>

                        <!-- Room Listings -->
                        <div class="mt-4">
                            <h3 class="text-xl font-semibold mb-2">Available Rooms</h3>
                            <div
                                v-for="room in property.rooms"
                                :key="room.id"
                                class="bg-gray-100 rounded-lg p-4 mb-4"
                            >
                                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                                    <div class="flex items-center">
                                        <img
                                            :src="room.image_url ? `/storage/${room.image_url}` : '/images/default-room.png'"
                                            alt="Room Image"
                                            class="w-20 h-20 rounded-lg object-cover mr-4"
                                        />
                                        <div>
                                            <h4 class="text-lg font-semibold">{{ room.name }}</h4>
                                            <p>Capacity: {{ room.capacity }}</p>
                                            <p>Price/Night: ${{ room.price_per_night }}</p>
                                        </div>
                                    </div>
                                    <Link
                                        :href="`/reservations/book?property_id=${property.id}&room_id=${room.id}`"
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4 md:mt-0"
                                    >
                                        Reserve Room
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- No Properties Found -->
        <div v-else class="text-red-600">No properties found.</div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { debounce } from 'lodash';
import { Head, usePage, Link } from '@inertiajs/vue3';

const { props } = usePage();

const properties = ref(props.properties?.data ?? []);
const searchQuery = ref('');

// Computed property to filter the listings based on search query
const filteredProperties = computed(() =>
    properties.value.filter((property) =>
        property.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        property.location.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        property.rooms.some((room) =>
            room.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
    )
);

const searchProperties = () => {
    Inertia.get('/', { search: searchQuery.value }, { preserveState: true });
};

const debouncedSearch = debounce(() => {
    searchProperties();
}, 300);

const makeReservation = (propertyId, roomId) => {
    Inertia.get('/reservations/book', { property_id: propertyId, room_id: roomId });
};
</script>

<style scoped>
@media (max-width: 768px) {
    .property-image {
        width: 100%;
    }
}
</style>
