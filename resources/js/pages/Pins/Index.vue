<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import RecordViewer from '../../components/RecordViewer.vue';

const props = defineProps({
    pins: Array,
});

const form = useForm({
    quantity: 10,
});

const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const generate = () => {
    form.post('/pins', {
        preserveScroll: true,
    });
};

const remove = (id) => {
    if (!confirm('Delete this pin?')) return;
    router.delete(`/pins/${id}`, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid gap-6">
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-[320px_1fr]">
                <PCard class="shadow-sm">
                    <template #title>Generate Pins</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <PInputText v-model="form.quantity" placeholder="Quantity" class="w-full" />
                                <FieldError :errors="form.errors" field="quantity" />
                            </div>
                            <PButton label="Generate" icon="pi pi-key" severity="success" class="w-full" @click="generate" />
                        </div>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Pins</template>
                    <template #content>
                        <PDataTable :value="pins" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn field="code" header="Code" />
                            <PColumn field="times_used" header="Used" />
                            <PColumn header="Status">
                                <template #body="slotProps">
                                    <PTag :value="slotProps.data.is_used ? 'Used' : 'Available'" :severity="slotProps.data.is_used ? 'warning' : 'success'" />
                                </template>
                            </PColumn>
                            <PColumn header="">
                                <template #body="slotProps">
                                    <div class="flex gap-2">
                                        <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                        <PButton icon="pi pi-trash" severity="danger" text rounded @click="remove(slotProps.data.id)" />
                                    </div>
                                </template>
                            </PColumn>
                        </PDataTable>
                    </template>
                </PCard>
            </section>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Pin Record" />
    </AppShell>
</template>
