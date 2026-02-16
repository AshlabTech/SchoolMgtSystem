<script setup>
import { computed, ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import DateField from './DateField.vue';

defineOptions({ name: 'ModelSelect' });

const props = defineProps({
    modelValue: {
        type: [String, Number, null],
        default: null,
    },
    options: {
        type: Array,
        default: () => [],
    },
    optionLabel: {
        type: String,
        default: 'name',
    },
    optionValue: {
        type: String,
        default: 'id',
    },
    placeholder: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    canCreate: {
        type: Boolean,
        default: false,
    },
    createTitle: {
        type: String,
        default: 'Add New',
    },
    createEndpoint: {
        type: String,
        default: '',
    },
    createFields: {
        type: Array,
        default: () => [],
    },
    createSubmitLabel: {
        type: String,
        default: 'Save',
    },
});

const emit = defineEmits(['update:modelValue', 'created']);

const showModal = ref(false);
const dropdownRef = ref(null);
const buildDefaults = (fields) =>
    (fields || []).reduce((acc, field) => {
        acc[field.name] = field.default ?? '';
        return acc;
    }, {});

const form = useForm(buildDefaults(props.createFields));

const hasCreate = computed(() =>
    props.canCreate && props.createEndpoint && props.createFields.length > 0,
);

watch(
    () => props.createFields,
    (fields) => {
        const defaults = buildDefaults(fields);
        if (typeof form.defaults === 'function') {
            form.defaults(defaults);
        }
        Object.keys(defaults).forEach((key) => {
            form[key] = defaults[key];
        });
    },
    { deep: true }
);

const openModal = () => {
    const defaults = buildDefaults(props.createFields);
    if (typeof form.defaults === 'function') {
        form.defaults(defaults);
        form.reset();
    } else {
        Object.keys(defaults).forEach((key) => {
            form[key] = defaults[key];
        });
    }
    form.clearErrors();
    dropdownRef.value?.hide?.();
    showModal.value = true;
};

const submit = () => {
    if (!props.createEndpoint) return;
    form.post(props.createEndpoint, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
            emit('created');
            router.reload({ preserveScroll: true });
        },
    });
};
</script>

<template>
    <div>
        <PDropdown
            ref="dropdownRef"
            :model-value="modelValue"
            :options="options"
            :optionLabel="optionLabel"
            :optionValue="optionValue"
            :placeholder="placeholder"
            class="w-full"
            :disabled="disabled"
            @update:modelValue="(value) => emit('update:modelValue', value)"
        >
            <template v-if="hasCreate" #footer>
                <div class="border-t border-slate-200 p-2 text-xs">
                    <button type="button" class="text-teal-700 hover:underline" @click="openModal">+ Add new</button>
                </div>
            </template>
        </PDropdown>

        <PDialog v-model:visible="showModal" modal :header="createTitle" class="w-full max-w-xl">
            <div class="grid gap-3 md:grid-cols-2">
                <div v-for="field in createFields" :key="field.name" class="space-y-1 md:col-span-1">
                    <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                        {{ field.label }}
                    </label>

                    <PInputText
                        v-if="field.type === 'text'"
                        v-model="form[field.name]"
                        :placeholder="field.placeholder || ''"
                        class="w-full"
                    />

                    <PInputNumber
                        v-else-if="field.type === 'number'"
                        v-model="form[field.name]"
                        :placeholder="field.placeholder || ''"
                        class="w-full"
                    />

                    <DateField
                        v-else-if="field.type === 'date'"
                        v-model="form[field.name]"
                        :placeholder="field.placeholder || ''"
                    />

                    <PDropdown
                        v-else-if="field.type === 'select'"
                        v-model="form[field.name]"
                        :options="field.options || []"
                        :optionLabel="field.optionLabel || 'name'"
                        :optionValue="field.optionValue || 'id'"
                        :placeholder="field.placeholder || ''"
                        class="w-full"
                    />

                    <ModelSelect
                        v-else-if="field.type === 'model-select'"
                        v-model="form[field.name]"
                        :options="field.options || []"
                        :optionLabel="field.optionLabel || 'name'"
                        :optionValue="field.optionValue || 'id'"
                        :placeholder="field.placeholder || ''"
                        :canCreate="field.canCreate || false"
                        :createTitle="field.createTitle || 'Add New'"
                        :createEndpoint="field.createEndpoint || ''"
                        :createFields="field.createFields || []"
                        :createSubmitLabel="field.createSubmitLabel || 'Save'"
                    />

                    <textarea
                        v-else-if="field.type === 'textarea'"
                        v-model="form[field.name]"
                        rows="3"
                        class="w-full rounded-md border border-slate-200 p-2 text-sm"
                        :placeholder="field.placeholder || ''"
                    ></textarea>

                    <PInputText
                        v-else
                        v-model="form[field.name]"
                        :placeholder="field.placeholder || ''"
                        class="w-full"
                    />

                    <div v-if="form.errors[field.name]" class="text-xs text-red-500">{{ form.errors[field.name] }}</div>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <PButton label="Cancel" severity="secondary" text @click="showModal = false" />
                    <PButton :label="createSubmitLabel" icon="pi pi-check" severity="success" :loading="form.processing" @click="submit" />
                </div>
            </template>
        </PDialog>
    </div>
</template>
