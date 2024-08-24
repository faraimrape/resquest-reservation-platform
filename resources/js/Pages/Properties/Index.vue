<template>
    <Head title="Manage Properties" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Properties</h2>
        </template>

        <div class="flex">
            <SidebarMenu />
            <main class="flex-1 p-6 bg-gray-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h1 class="text-2xl font-bold mb-6">Manage Properties</h1>

                        <!-- Search Box and Add Button in the same row -->
                        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                            <input
                                v-model="filters.search"
                                @input="debouncedSearch"
                                type="text"
                                placeholder="Search properties..."
                                class="border border-gray-300 rounded px-4 py-2 mb-4 md:mb-0 w-full md:w-1/2"
                            />
                            <Link href="/properties/create" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Add New Property</Link>
                        </div>

                        <div v-if="!properties.data || properties.data.length === 0" class="text-red-600 mb-4">
                            No properties found. Please add some properties.
                        </div>

                        <div v-if="properties.data && properties.data.length > 0">
                            <div class="overflow-x-auto">
                            <table class="min-w-full bg-white shadow-md rounded-lg text-sm">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left">Property Name</th>
                                    <th class="px-6 py-3 text-left">Location</th>
                                    <th class="px-6 py-3 text-left">Description</th>
                                    <th class="px-6 py-3 text-left">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="property in properties.data" :key="property.id">
                                    <td class="px-6 py-4">{{ property.name }}</td>
                                    <td class="px-6 py-4">{{ property.location }}</td>
                                    <td class="px-6 py-4">{{ property.description }}</td>
                                    <td class="px-2 py-4 flex flex-col space-y-2 md:space-y-0 md:flex-row md:space-x-2">
                                        <Link :href="`/properties/${property.id}/edit`" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Update</Link>
                                        <Link :href="`/rooms`" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">See Rooms</Link>
                                        <button type="button" @click.prevent="deleteProperty(property.id)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                            <!-- Pagination Controls -->
                            <div class="mt-6 text-sm">
                                <Link
                                    v-if="properties.prev_page_url"
                                    :href="properties.prev_page_url"
                                    @click.prevent="changePage(properties.prev_page_url)"
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700"
                                >
                                    Previous Page
                                </Link>
                                <Link
                                    v-if="properties.next_page_url"
                                    :href="properties.next_page_url"
                                    @click.prevent="changePage(properties.next_page_url)"
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700"
                                >
                                    Next Page
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { usePage, Link, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';
import { useToast } from "vue-toastification";
import { debounce } from 'lodash';

const { props } = usePage();
const properties = ref(props.properties);
const filters = ref({
    search: props.filters.search || ''
});
const toast = useToast();

const deleteProperty = (id) => {
    Inertia.delete(`/properties/${id}`, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            if (page.props.flash?.success) {
                toast.success(page.props.flash.success);
            }
        },
    });
};

const searchProperties = () => {
    Inertia.get('/properties', filters.value, {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            properties.value = page.props.properties;
        }
    });
};

const debouncedSearch = debounce(searchProperties, 900);
const changePage = (url) => {
    Inertia.get(url, {}, {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            properties.value = page.props.properties;
        }
    });
};
</script>
