<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, null],
        default: null,
    },
    placeholder: {
        type: String,
        default: '',
    },
    showIcon: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['update:modelValue']);

const toDate = (value) => {
    if (!value) return null;
    const parts = String(value).split('-');
    if (parts.length !== 3) return null;
    const [year, month, day] = parts.map((part) => Number(part));
    if (!year || !month || !day) return null;
    return new Date(year, month - 1, day);
};

const toString = (value) => {
    if (!(value instanceof Date)) return '';
    const year = value.getFullYear();
    const month = String(value.getMonth() + 1).padStart(2, '0');
    const day = String(value.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

const model = computed({
    get: () => toDate(props.modelValue),
    set: (value) => emit('update:modelValue', value ? toString(value) : ''),
});
</script>

<template>
    <PDatePicker v-model="model" dateFormat="yy-mm-dd" :showIcon="showIcon" :placeholder="placeholder" class="w-full" />
</template>
