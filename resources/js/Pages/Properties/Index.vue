<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Manage Properties</h1>

        <div v-if="!properties.length" class="text-red-600">
            No properties available. Please add some properties.
        </div>

        <div v-else>
            <div class="mb-4">
                <inertia-link href="/properties/create" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Add New Property</inertia-link>
            </div>
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <!-- Table headers -->
                <thead>
                <tr>
                    <th class="px-6 py-3 text-left">Property Name</th>
                    <th class="px-6 py-3 text-left">Location</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
                </thead>
                <!-- Table rows -->
                <tbody>
                <tr v-for="property in properties" :key="property.id">
                    <td class="px-6 py-4">{{ property.name }}</td>
                    <td class="px-6 py-4">{{ property.location }}</td>
                    <td class="px-6 py-4">
                        <inertia-link :href="`/properties/${property.id}/edit`" class="text-blue-600 hover:text-blue-900">Edit</inertia-link>
                        <inertia-link :href="`/properties/${property.id}/rooms`" class="ml-4 text-green-600 hover:text-green-900">Manage Rooms</inertia-link>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const properties = ref(usePage().props.value.properties || []);
</script>
