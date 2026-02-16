<script setup>
import { computed } from 'vue';

const props = defineProps({
    errors: {
        type: Object,
        default: () => ({}),
    },
    field: {
        type: String,
        required: true,
    },
});

const message = computed(() => props.errors?.[props.field]);
</script>

<template>
    <div v-if="message" class="text-xs text-red-500">{{ message }}</div>
</template>
