<template>
    <aside class="w-64 bg-gray-800 text-white h-screen px-4 py-6">
        <nav>
            <ul>
                <li class="mb-4">
                    <Link href="/properties" :class="{'bg-gray-700': currentRouteName === 'properties.index' || currentRouteName === 'properties.create'}" class="block px-4 py-2 hover:bg-gray-700 rounded">Manage Properties</Link>
                </li>
                <li class="mb-4">
                    <Link href="/rooms" :class="{'bg-gray-700': currentRouteName === 'rooms.index'}" class="block px-4 py-2 hover:bg-gray-700 rounded">Manage Rooms</Link>
                </li>
                <li class="mb-4">
                    <Link href="/reservations" :class="{'bg-gray-700': currentRouteName === 'reservations.index'}" class="block px-4 py-2 hover:bg-gray-700 rounded">Manage Reservations</Link>
                </li>
                <li class="mb-4">
                    <Link href="/guests" :class="{'bg-gray-700': currentRouteName === 'guests.index'}" class="block px-4 py-2 hover:bg-gray-700 rounded">Manage Guests</Link>
                </li>
            </ul>
        </nav>
    </aside>
</template>
<script setup>
import { Link, usePage } from '@inertiajs/vue3';
const currentRouteName = usePage().props.currentRouteName;
</script>
