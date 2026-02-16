<script setup>
import { computed } from 'vue';

const props = defineProps({
    visible: {
        type: Boolean,
        default: false,
    },
    record: {
        type: Object,
        default: null,
    },
    title: {
        type: String,
        default: 'Record Details',
    },
    excludeKeys: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['update:visible']);

const defaultExclude = ['pivot'];
const nestedExclude = ['id', 'user_id', 'created_at', 'updated_at', 'deleted_at', 'pivot'];

const isPlainObject = (value) =>
    Object.prototype.toString.call(value) === '[object Object]';

const labelize = (key) =>
    key
        .replace(/_/g, ' ')
        .replace(/([a-z0-9])([A-Z])/g, '$1 $2')
        .replace(/\b\w/g, (m) => m.toUpperCase());

const formatDateTime = (value) => {
    if (typeof value !== 'string') return value;
    if (!value.includes('T')) return value;
    return value.replace('T', ' ').replace(/\.\d+Z?$/, '');
};

const summarizeArray = (arr) => {
    if (!arr.length) return '—';
    if (arr.every((item) => typeof item !== 'object')) {
        return arr.join(', ');
    }
    if (arr.every((item) => item && typeof item === 'object' && item.name)) {
        return arr.map((item) => item.name).join(', ');
    }
    if (arr.every((item) => item && typeof item === 'object' && item.title)) {
        return arr.map((item) => item.title).join(', ');
    }
    if (arr.every((item) => item && typeof item === 'object' && item.label)) {
        return arr.map((item) => item.label).join(', ');
    }
    return `${arr.length} item${arr.length > 1 ? 's' : ''}`;
};

const formatValue = (value) => {
    if (value === null || value === undefined || value === '') return '—';
    if (typeof value === 'boolean') return value ? 'Yes' : 'No';
    if (Array.isArray(value)) return summarizeArray(value);
    if (isPlainObject(value)) {
        if (value?.name) return value.name;
        if (value?.label) return value.label;
        if (value?.title) return value.title;
        return '—';
    }
    return formatDateTime(String(value));
};

const objectEntries = (obj, excluded) => {
    if (!obj || !isPlainObject(obj)) return [];
    return Object.entries(obj)
        .filter(([key]) => !excluded.includes(key))
        .map(([key, value]) => [labelize(key), formatValue(value)]);
};

const sections = computed(() => {
    if (!props.record) return { general: [], groups: [] };
    const excluded = [...defaultExclude, ...props.excludeKeys];
    const general = [];
    const groups = [];

    Object.entries(props.record)
        .filter(([key]) => !excluded.includes(key))
        .forEach(([key, value]) => {
            if (isPlainObject(value)) {
                const details = objectEntries(value, nestedExclude);
                if (details.length) {
                    groups.push({ title: labelize(key), entries: details });
                }
                return;
            }

            general.push([labelize(key), formatValue(value)]);
        });

    return { general, groups };
});
</script>

<template>
    <PDialog :visible="visible" modal :header="title" class="w-full max-w-2xl" @update:visible="(value) => emit('update:visible', value)">
        <div v-if="record" class="space-y-4 text-sm">
            <div v-if="sections.general.length" class="divide-y divide-slate-100">
                <div v-for="[key, value] in sections.general" :key="key" class="grid grid-cols-[160px_1fr] gap-4 py-2">
                    <div class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">{{ key }}</div>
                    <div class="text-slate-700">{{ value }}</div>
                </div>
            </div>
            <div v-for="group in sections.groups" :key="group.title" class="rounded-xl border border-slate-200">
                <div class="border-b border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                    {{ group.title }}
                </div>
                <div class="divide-y divide-slate-100 px-4 py-2">
                    <div v-for="[key, value] in group.entries" :key="key" class="grid grid-cols-[160px_1fr] gap-4 py-2">
                        <div class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">{{ key }}</div>
                        <div class="text-slate-700">{{ value }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="text-sm text-slate-500">No record selected.</div>
    </PDialog>
</template>
