<script setup>
import { reactive, watch, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import DateField from '../../components/DateField.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { useToast } from '../../composables/useToast';

const props = defineProps({
    settings: Array,
});

const { showSuccess, showError } = useToast();

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

const updateSetting = (setting) => {
    // Convert boolean to 0 or 1
    let valueToSend = setting.value;
    if (setting.type === 'boolean') {
        valueToSend = setting.value ? 1 : 0;
    }
    
    router.put(
        `/settings/${setting.id}`,
        {
            group: setting.group,
            value: valueToSend,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                showSuccess('Setting updated successfully');
            },
            onError: (errors) => {
                showError('Failed to update setting', Object.values(errors).join(', '));
            },
        }
    );
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
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
