<template>
    <Head title="Update Room" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Room</h2>
        </template>

        <div class="flex">
            <SidebarMenu />
            <main class="flex-1 p-6 bg-gray-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow-md rounded-lg p-6 text-sm">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="name" class="block font-bold text-gray-700">Room Name</label>
                                <input v-model="form.name" id="name" type="text" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"  required>
                                <span v-if="form.errors.name" class="text-red-600">{{ form.errors.name }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="property_id" class="block font-bold text-gray-700">Property</label>
                                <select v-model="form.property_id" id="property_id" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"  required>
                                    <option value="" disabled>Select a property</option>
                                    <option v-for="property in properties" :key="property.id" :value="property.id">
                                        {{ property.name }}
                                    </option>
                                </select>
                                <span v-if="form.errors.property_id" class="text-red-600">{{ form.errors.property_id }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="capacity" class="block font-bold text-gray-700">Capacity</label>
                                <input v-model="form.capacity" id="capacity" type="number"class=" border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"  required>
                                <span v-if="form.errors.capacity" class="text-red-600">{{ form.errors.capacity }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="price_per_night" class="block font-bold text-gray-700">Price Per Night</label>
                                <input v-model="form.price_per_night" id="price_per_night" type="number" step="0.01" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"  required>
                                <span v-if="form.errors.price_per_night" class="text-red-600">{{ form.errors.price_per_night }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Update Room</button>
                                <Link href="/rooms" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Cancel</Link>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import SidebarMenu from '@/Components/SideMenu.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { useToast } from "vue-toastification";

const { props } = usePage();
const properties = props.properties ?? [];
const room = props.room;

// Initialize the form with the room's current data
const form = useForm({
    name: room.name,
    property_id: room.property_id,
    capacity: room.capacity,
    price_per_night: room.price_per_night,
});

const toast = useToast();

const submit = () => {
    form.put(`/rooms/${room.id}`, {
        onSuccess: () => {
            toast.success('Room updated successfully.');
        },
    });
};
</script>
