<template>
    <!-- Top Bar for Mobile -->
    <div class="lg:hidden flex justify-between items-center bg-gray-800 text-white p-4">
        <h2 class="text-lg font-semibold">Dashboard</h2>
        <button @click="toggleSidebar" class="focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Wrapper that contains sidebar and main content -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside :class="['lg:w-64 w-full lg:block', { 'block': isSidebarOpen, 'hidden': !isSidebarOpen }]" class="bg-gray-800 text-white flex-shrink-0 lg:h-auto px-4 py-6 text-sm fixed lg:relative top-0 left-0 lg:left-auto z-50 lg:z-auto">
            <nav>
                <ul class="space-y-4">
                    <li>
                        <Link
                            href="/properties"
                            :class="{'bg-gray-700': route().current('properties.*')}"
                            class="block px-4 py-2 hover:bg-gray-700 rounded"
                            @click="closeSidebar"
                        >
                            Manage Properties
                        </Link>
                    </li>
                    <li>
                        <Link
                            href="/rooms"
                            :class="{'bg-gray-700': route().current('rooms.*')}"
                            class="block px-4 py-2 hover:bg-gray-700 rounded"
                            @click="closeSidebar"
                        >
                            Manage Rooms
                        </Link>
                    </li>
                    <li>
                        <Link
                            href="/reservations"
                            :class="{'bg-gray-700': route().current('reservations.*')}"
                            class="block px-4 py-2 hover:bg-gray-700 rounded"
                            @click="closeSidebar"
                        >
                            Manage Reservations
                        </Link>
                    </li>
                    <li>
                        <Link
                            href="/guests"
                            :class="{'bg-gray-700': route().current('guests.*')}"
                            class="block px-4 py-2 hover:bg-gray-700 rounded"
                            @click="closeSidebar"
                        >
                            Manage Guests
                        </Link>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-gray-100 overflow-y-auto">
            <!-- Place your content here -->
            <slot />
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const isSidebarOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const closeSidebar = () => {
    isSidebarOpen.value = false;
};
</script>
