<template>
    <Head title="Edit Guest" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Guest</h2>
        </template>
        <div class="flex">
            <SidebarMenu />
            <main class="flex-1 p-6 bg-gray-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="first_name" class="block font-bold text-gray-700">First Name</label>
                                <input v-model="form.first_name" id="first_name" type="text" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                <span v-if="form.errors.first_name" class="text-red-600">{{ form.errors.first_name }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="last_name" class="block font-bold text-gray-700">Last Name</label>
                                <input v-model="form.last_name" id="last_name" type="text" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                <span v-if="form.errors.last_name" class="text-red-600">{{ form.errors.last_name }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block font-bold text-gray-700">Email</label>
                                <input v-model="form.email" id="email" type="email" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"required>
                                <span v-if="form.errors.email" class="text-red-600">{{ form.errors.email }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="block font-bold text-gray-700">Phone</label>
                                <input v-model="form.phone" id="phone" type="text" class="border-gray-300 rounded-lg shadow-sm w-full px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                <span v-if="form.errors.phone" class="text-red-600">{{ form.errors.phone }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Update Guest</button>
                                <Link href="/guests" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Cancel</Link>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm, Link, Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarMenu from '@/Components/SideMenu.vue';

const guest = usePage().props.guest;

const form = useForm({
    first_name: guest.first_name,
    last_name: guest.last_name,
    email: guest.email,
    phone: guest.phone,
});

const submit = () => {
    form.put(`/guests/${guest.id}`, {
        onSuccess: () => {
            form.reset();
        },
    });
}
</script>
