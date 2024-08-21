<template>
    <form @submit.prevent="submitForm">
        <div>
            <label for="check_in">Check-in Date:</label>
            <input type="date" v-model="form.check_in" required>
        </div>
        <div>
            <label for="check_out">Check-out Date:</label>
            <input type="date" v-model="form.check_out" required>
        </div>
        <div>
            <label for="room">Select Room:</label>
            <select v-model="form.room_id" required>
                <option v-for="room in rooms" :key="room.id" :value="room.id">{{ room.name }}</option>
            </select>
        </div>
        <button type="submit">Make Reservation</button>
    </form>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    check_in: '',
    check_out: '',
    room_id: '',
});

const rooms = [
    // Fetch available rooms from the backend
];

function submitForm() {
    form.post('/reservations');
}
</script>
