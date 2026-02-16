<script setup>
import { reactive, watch, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import RecordViewer from '../../components/RecordViewer.vue';

const props = defineProps({
    settings: Array,
});

const localSettings = reactive((props.settings || []).map((setting) => ({ ...setting })));

const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

watch(
    () => props.settings,
    (value) => {
        localSettings.splice(0, localSettings.length, ...(value || []).map((setting) => ({ ...setting })));
    }
);

const form = useForm({
    key: '',
    group: '',
    value: '',
});

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
        value: setting.value,
    }, { preserveScroll: true });
};

const deleteSetting = (id) => {
    if (!confirm('Delete this setting?')) return;
    router.delete(`/settings/${id}`, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid gap-6">
            <PCard class="shadow-sm">
                <template #title>Add Setting</template>
                <template #content>
                    <div class="grid gap-3 md:grid-cols-3">
                        <div>
                            <PInputText v-model="form.key" placeholder="Key" class="w-full" />
                            <FieldError :errors="form.errors" field="key" />
                        </div>
                        <div>
                            <PInputText v-model="form.group" placeholder="Group" class="w-full" />
                            <FieldError :errors="form.errors" field="group" />
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
                        <PColumn field="key" header="Key" />
                        <PColumn header="Group">
                            <template #body="slotProps">
                                <PInputText v-model="slotProps.data.group" class="w-full" />
                            </template>
                        </PColumn>
                        <PColumn header="Value">
                            <template #body="slotProps">
                                <PInputText v-model="slotProps.data.value" class="w-full" />
                            </template>
                        </PColumn>
                        <PColumn header="">
                            <template #body="slotProps">
                                <div class="flex gap-2">
                                    <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                    <PButton icon="pi pi-save" severity="success" text rounded @click="updateSetting(slotProps.data)" />
                                    <PButton icon="pi pi-trash" severity="danger" text rounded @click="deleteSetting(slotProps.data.id)" />
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
