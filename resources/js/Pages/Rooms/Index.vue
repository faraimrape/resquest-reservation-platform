<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Manage Rooms</h1>

        <!-- Handle cases where no properties are available -->
        <div v-if="!properties.length" class="mb-4 text-red-600">
            No properties available. Please add some properties first.
        </div>

        <div v-else>
            <!-- Dropdown to filter rooms by property -->
            <div class="mb-4">
                <label for="property" class="block text-gray-700">Filter by Property:</label>
                <select id="property" v-model="selectedProperty" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option v-for="property in properties" :key="property.id" :value="property.id">{{ property.name }}</option>
                </select>
            </div>

            <!-- Room management table -->
            <table v-if="filteredRooms.length" class="min-w-full bg-white shadow-md rounded-lg">
                <!-- Table headers -->
                <thead>
                <tr>
                    <th class="px-6 py-3 text-left">Room Name</th>
                    <th class="px-6 py-3 text-left">Capacity</th>
                    <th class="px-6 py-3 text-left">Price per Night</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
                </thead>
                <!-- Table rows -->
                <tbody>
                <!-- Loop through rooms filtered by property -->
                <tr v-for="room in filteredRooms" :key="room.id">
                    <td class="px-6 py-4">{{ room.name }}</td>
                    <td class="px-6 py-4">{{ room.capacity }}</td>
                    <td class="px-6 py-4">${{ room.price_per_night }}</td>
                    <td class="px-6 py-4">
                        <inertia-link :href="`/rooms/${room.id}/edit`" class="text-blue-600 hover:text-blue-900">Edit</inertia-link>
                        <inertia-link :href="`/rooms/${room.id}/delete`" class="ml-4 text-red-600 hover:text-red-900">Delete</inertia-link>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- No rooms available message -->
            <div v-else class="mt-4 text-red-600">
                No rooms available for this property.
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const properties = ref(usePage().props.value.properties || []);
const rooms = ref(usePage().props.value.rooms || []);
const selectedProperty = ref(properties.value[0]?.id || null); // Default to the first property, if available

const filteredRooms = computed(() => {
    if (!selectedProperty.value) return rooms.value;
    return rooms.value.filter(room => room.property_id === selectedProperty.value);
});
</script>
