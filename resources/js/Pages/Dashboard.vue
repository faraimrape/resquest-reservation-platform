<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="flex">
            <!-- Sidebar Menu -->
            <aside class="w-64 bg-gray-800 text-white h-screen px-4 py-6">
                <nav>
                    <ul>
                        <li class="mb-4">
                            <Link href="/properties" class="block px-4 py-2 hover:bg-gray-700 rounded">Manage Properties</Link>
                        </li>
                        <li class="mb-4">
                            <Link href="/rooms" class="block px-4 py-2 hover:bg-gray-700 rounded">Manage Rooms</Link>
                        </li>
                        <li class="mb-4">
                            <Link href="/reservations" class="block px-4 py-2 hover:bg-gray-700 rounded">Manage Reservations</Link>
                        </li>
                        <li class="mb-4">
                            <Link href="/guests" class="block px-4 py-2 hover:bg-gray-700 rounded">Manage Guests</Link>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 p-6 bg-gray-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">You're logged in!</div>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

