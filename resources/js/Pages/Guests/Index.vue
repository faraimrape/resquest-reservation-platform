<template>
    <Head title="Manage Guests" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Guests</h2>
        </template>
        <div class="flex">
            <SidebarMenu />
            <main class="flex-1 p-6 bg-gray-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h2 class="text-2xl font-bold mb-4">Manage Guests</h2>

                        <!-- Search Box -->
                        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                            <input
                                v-model="filters.search"
                                @input="debouncedSearch"
                                type="text"
                                placeholder="Search guests..."
                                class="border border-gray-300 rounded px-4 py-2 mb-4 md:mb-0 w-full md:w-1/2"
                            />
                        </div>
                        <div v-if="!guests.data || guests.data.length === 0" class="text-red-600">
                            No guests found.
                        </div>

                        <!-- Guests Table -->
                        <div class="overflow-x-auto">
                        <table v-if="guests.data && guests.data.length > 0" class="border rounded-md min-w-full max-w-full w-full bg-white shadow-md rounded-lg text-sm">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 text-left">First Name</th>
                                <th class="px-6 py-3 text-left">Last Name</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-left">Phone</th>
                                <th class="px-6 py-3 text-left">Reservation ID</th>
                                <th class="px-6 py-3 text-left">Room</th>
                                <th class="px-6 py-3 text-left">Property</th>
                                <th class="px-6 py-3 text-left">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="guest in guests.data" :key="guest.id">
                                <td class="px-6 py-4">{{ guest.first_name }}</td>
                                <td class="px-6 py-4">{{ guest.last_name }}</td>
                                <td class="px-6 py-4">{{ guest.email }}</td>
                                <td class="px-6 py-4">{{ guest.phone }}</td>
                                <td class="px-6 py-4">{{ guest.reservation_id }}</td>
                                <td class="px-6 py-4">{{ guest.reservation.room.name }}</td>
                                <td class="px-6 py-4">{{ guest.reservation.room.property.name }}</td>
                                <td class="px-2 py-4 flex flex-col space-y-2 md:space-y-0 md:flex-row md:space-x-2">
                                    <Link :href="`/guests/${guest.id}/edit`" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Update</Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        </div>

                        <!-- Pagination Controls -->
                        <div class="mt-6 text-sm">
                            <Link
                                v-if="guests.prev_page_url"
                                :href="guests.prev_page_url"
                                @click.prevent="changePage(guests.prev_page_url)"
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700"
                            >
                                Previous Page
                            </Link>
                            <Link
                                v-if="guests.next_page_url"
                                :href="guests.next_page_url"
                                @click.prevent="changePage(guests.next_page_url)"
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700"
                            >
                                Next Page
                            </Link>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { usePage, Link, Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { debounce } from 'lodash';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';

const { props } = usePage();
const guests = ref(props.guests);
const filters = ref({
    search: props.filters.search || ''
});

// Debounced search function to avoid unnecessary requests
const debouncedSearch = debounce(() => {
    Inertia.get('/guests', filters.value, {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            guests.value = page.props.guests;
        }
    });
}, 900);

const changePage = (url) => {
    Inertia.get(url, {}, {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            guests.value = page.props.guests;
        }
    });
};

// Automatically trigger search when the input changes
watch(filters, debouncedSearch, { deep: true });
</script>
