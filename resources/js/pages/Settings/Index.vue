<script setup>
import { reactive, watch, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import DateField from '../../components/DateField.vue';
import FieldError from '../../components/FieldError.vue';
import RecordViewer from '../../components/RecordViewer.vue';

const props = defineProps({
    settings: Array,
});

const normalizeSetting = (setting) => {
    const normalized = { ...setting };
    normalized.type = normalized.type || 'text';
    normalized.label = normalized.label || normalized.key;
    if (normalized.type === 'boolean') {
        normalized.value = ['1', 1, true, 'true'].includes(normalized.value);
    }
    if (normalized.type === 'number' && normalized.value !== null && normalized.value !== '') {
        normalized.value = Number(normalized.value);
    }
    return normalized;
};

const localSettings = reactive((props.settings || []).map((setting) => normalizeSetting(setting)));

const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

watch(
    () => props.settings,
    (value) => {
        localSettings.splice(0, localSettings.length, ...(value || []).map((setting) => normalizeSetting(setting)));
    }
);

const form = useForm({
    key: '',
    label: '',
    group: '',
    type: 'text',
    value: '',
});

const typeOptions = [
    { label: 'Text', value: 'text' },
    { label: 'Number', value: 'number' },
    { label: 'Boolean', value: 'boolean' },
    { label: 'Select', value: 'select' },
    { label: 'Date', value: 'date' },
];

const submit = () => {
    form.post('/settings', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const updateSetting = (setting) => {
    router.put(`/settings/${setting.id}`, {
        group: setting.group,
        value: setting.type === 'boolean' ? (setting.value ? 1 : 0) : setting.value,
    }, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <PCard class="shadow-sm">
                <template #title>Add Setting</template>
                <template #content>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                        <div>
                            <PInputText v-model="form.key" placeholder="Key" class="w-full" />
                            <FieldError :errors="form.errors" field="key" />
                        </div>
                        <div>
                            <PInputText v-model="form.label" placeholder="Label" class="w-full" />
                            <FieldError :errors="form.errors" field="label" />
                        </div>
                        <div>
                            <PInputText v-model="form.group" placeholder="Group" class="w-full" />
                            <FieldError :errors="form.errors" field="group" />
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-3">
                        <div>
                            <PDropdown v-model="form.type" :options="typeOptions" optionLabel="label" optionValue="value" placeholder="Type" class="w-full" />
                            <FieldError :errors="form.errors" field="type" />
                        </div>
                        <div>
                            <PInputText v-model="form.value" placeholder="Value" class="w-full" />
                            <FieldError :errors="form.errors" field="value" />
                        </div>
                    </div>
                    <PButton label="Save" icon="pi pi-plus" severity="success" class="mt-4" @click="submit" />
                </template>
            </PCard>

            <PCard class="shadow-sm">
                <template #title>Settings</template>
                <template #content>
                    <PDataTable :value="localSettings" stripedRows responsiveLayout="scroll" class="text-sm">
                        <PColumn field="label" header="Label" />
                        <PColumn field="key" header="Key" />
                        <PColumn header="Group">
                            <template #body="slotProps">
                                <PInputText v-model="slotProps.data.group" class="w-full" />
                            </template>
                        </PColumn>
                        <PColumn header="Value">
                            <template #body="slotProps">
                                <PDropdown
                                    v-if="slotProps.data.type === 'select'"
                                    v-model="slotProps.data.value"
                                    :options="slotProps.data.options || []"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Select value"
                                    class="w-full"
                                />
                                <PInputNumber
                                    v-else-if="slotProps.data.type === 'number'"
                                    v-model="slotProps.data.value"
                                    class="w-full"
                                />
                                <DateField
                                    v-else-if="slotProps.data.type === 'date'"
                                    v-model="slotProps.data.value"
                                    placeholder="Select date"
                                />
                                <PInputSwitch
                                    v-else-if="slotProps.data.type === 'boolean'"
                                    v-model="slotProps.data.value"
                                />
                                <PInputText v-else v-model="slotProps.data.value" class="w-full" />
                            </template>
                        </PColumn>
                        <PColumn field="type" header="Type" />
                        <PColumn header="">
                            <template #body="slotProps">
                                <div class="flex gap-2">
                                    <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                    <PButton icon="pi pi-save" severity="success" text rounded @click="updateSetting(slotProps.data)" />
                                </div>
                            </template>
                        </PColumn>
                    </PDataTable>
                </template>
            </PCard>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Setting Record" />
    </AppShell>
</template>
