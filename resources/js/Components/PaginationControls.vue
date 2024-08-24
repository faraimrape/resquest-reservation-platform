<template>
    <div class="mt-6">
        <Link
            v-if="pagination.prev_page_url"
            :href="pagination.prev_page_url"
            @click.prevent="changePage(pagination.prev_page_url)"
            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 mr-2"
        >
            Previous Page
        </Link>
        <Link
            v-if="pagination.next_page_url"
            :href="pagination.next_page_url"
            @click.prevent="changePage(pagination.next_page_url)"
            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
        >
            Next Page
        </Link>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    pagination: {
        type: Object,
        required: true
    }
});
const emit = defineEmits(['page-changed']);
const changePage = (url) => {
    emit('page-changed', url);
};
</script>
