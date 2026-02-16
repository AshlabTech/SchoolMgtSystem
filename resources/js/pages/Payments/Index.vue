<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';

const props = defineProps({
    definitions: Array,
    records: Array,
    classes: Array,
    classLevels: Array,
    years: Array,
});

const { can } = usePermissions();

const definitionForm = useForm({
    name: '',
    amount: '',
    class_id: null,
    description: '',
    academic_year_id: null,
});

const editingDefinitionId = ref(null);
const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const startEditDefinition = (record) => {
    editingDefinitionId.value = record.id;
    definitionForm.clearErrors();
    definitionForm.name = record.name ?? '';
    definitionForm.amount = record.amount ?? '';
    definitionForm.class_id = record.class_id ?? record.school_class?.id ?? null;
    definitionForm.description = record.description ?? '';
    definitionForm.academic_year_id = record.academic_year_id ?? record.academic_year?.id ?? null;
};

const cancelEditDefinition = () => {
    editingDefinitionId.value = null;
    definitionForm.reset();
    definitionForm.clearErrors();
};

const generateForm = useForm({
    class_id: null,
    academic_year_id: null,
});

const payForm = useForm({
    amount: '',
    record_id: null,
});

const createDefinition = () => {
    if (editingDefinitionId.value) {
        definitionForm.put(`/payments/definitions/${editingDefinitionId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingDefinitionId.value = null;
                definitionForm.reset();
            },
        });
        return;
    }

    definitionForm.post('/payments/definitions', {
        preserveScroll: true,
        onSuccess: () => {
            definitionForm.reset();
        },
    });
};

const generateRecords = () => {
    generateForm.post('/payments/records/generate', { preserveScroll: true });
};

const openPay = (record) => {
    payForm.record_id = record.id;
    payForm.amount = '';
};

const submitPay = () => {
    if (!payForm.record_id) return;
    payForm.post(`/payments/records/${payForm.record_id}/pay`, {
        preserveScroll: true,
        onSuccess: () => {
            payForm.reset('amount');
        },
    });
};

const resetRecord = (id) => {
    if (!confirm('Reset this payment record?')) return;
    router.post(`/payments/records/${id}/reset`, {}, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid gap-6">
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-[360px_1fr]">
                <PCard class="shadow-sm">
                    <template #title>Fee Definition</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <PInputText v-model="definitionForm.name" placeholder="Fee name" class="w-full" />
                                <FieldError :errors="definitionForm.errors" field="name" />
                            </div>
                            <div>
                                <PInputText v-model="definitionForm.amount" placeholder="Amount" class="w-full" />
                                <FieldError :errors="definitionForm.errors" field="amount" />
                            </div>
                            <div>
                                <ModelSelect
                                    v-model="definitionForm.class_id"
                                    :options="classes"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Class (optional)"
                                    :canCreate="can('manage.classes')"
                                    createTitle="Add Class"
                                    createEndpoint="/academics/classes"
                                    :createFields="[
                                        {
                                            name: 'class_level_id',
                                            label: 'Class level',
                                            type: 'model-select',
                                            options: classLevels,
                                            optionLabel: 'name',
                                            optionValue: 'id',
                                            canCreate: can('manage.classes'),
                                            createTitle: 'Add Class Level',
                                            createEndpoint: '/academics/class-levels',
                                            createFields: [
                                                { name: 'name', label: 'Level name', type: 'text' },
                                                { name: 'code', label: 'Code', type: 'text' },
                                                { name: 'description', label: 'Description', type: 'text' },
                                            ],
                                        },
                                        { name: 'name', label: 'Class name', type: 'text' },
                                        { name: 'code', label: 'Code', type: 'text' },
                                        { name: 'description', label: 'Description', type: 'text' },
                                    ]"
                                />
                                <FieldError :errors="definitionForm.errors" field="class_id" />
                            </div>
                            <div>
                                <ModelSelect
                                    v-model="definitionForm.academic_year_id"
                                    :options="years"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Academic year"
                                    :canCreate="can('manage.settings')"
                                    createTitle="Add Academic Year"
                                    createEndpoint="/academic-years"
                                    :createFields="[
                                        { name: 'name', label: 'Name', type: 'text', placeholder: '2025/2026' },
                                        { name: 'starts_at', label: 'Start date', type: 'date' },
                                        { name: 'ends_at', label: 'End date', type: 'date' },
                                        { name: 'is_current', label: 'Set as current', type: 'select', options: [{ name: 'No', id: 0 }, { name: 'Yes', id: 1 }] },
                                    ]"
                                />
                                <FieldError :errors="definitionForm.errors" field="academic_year_id" />
                            </div>
                            <div>
                                <PInputText v-model="definitionForm.description" placeholder="Description" class="w-full" />
                                <FieldError :errors="definitionForm.errors" field="description" />
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <PButton :label="editingDefinitionId ? 'Update Fee' : 'Create Fee'" icon="pi pi-plus" severity="success" @click="createDefinition" />
                                <PButton v-if="editingDefinitionId" label="Cancel" severity="secondary" text @click="cancelEditDefinition" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <PDataTable :value="definitions" stripedRows responsiveLayout="scroll" class="text-sm">
                                <PColumn field="name" header="Fee" />
                                <PColumn field="amount" header="Amount" />
                                <PColumn header="Class">
                                    <template #body="slotProps">
                                        {{ slotProps.data.school_class?.name ?? 'All' }}
                                    </template>
                                </PColumn>
                                <PColumn header="">
                                    <template #body="slotProps">
                                        <div class="flex gap-2">
                                            <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                            <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEditDefinition(slotProps.data)" />
                                        </div>
                                    </template>
                                </PColumn>
                            </PDataTable>
                        </div>
                    </template>
                </PCard>

                <div class="grid gap-6">
                    <PCard class="shadow-sm">
                        <template #title>Generate Payment Records</template>
                        <template #content>
                            <div class="space-y-3">
                                <div>
                                    <ModelSelect
                                        v-model="generateForm.class_id"
                                        :options="classes"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Class"
                                        :canCreate="can('manage.classes')"
                                        createTitle="Add Class"
                                        createEndpoint="/academics/classes"
                                        :createFields="[
                                            {
                                                name: 'class_level_id',
                                                label: 'Class level',
                                                type: 'model-select',
                                                options: classLevels,
                                                optionLabel: 'name',
                                                optionValue: 'id',
                                                canCreate: can('manage.classes'),
                                                createTitle: 'Add Class Level',
                                                createEndpoint: '/academics/class-levels',
                                                createFields: [
                                                    { name: 'name', label: 'Level name', type: 'text' },
                                                    { name: 'code', label: 'Code', type: 'text' },
                                                    { name: 'description', label: 'Description', type: 'text' },
                                                ],
                                            },
                                            { name: 'name', label: 'Class name', type: 'text' },
                                            { name: 'code', label: 'Code', type: 'text' },
                                            { name: 'description', label: 'Description', type: 'text' },
                                        ]"
                                    />
                                    <FieldError :errors="generateForm.errors" field="class_id" />
                                </div>
                                <div>
                                    <ModelSelect
                                        v-model="generateForm.academic_year_id"
                                        :options="years"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Academic year"
                                        :canCreate="can('manage.settings')"
                                        createTitle="Add Academic Year"
                                        createEndpoint="/academic-years"
                                        :createFields="[
                                            { name: 'name', label: 'Name', type: 'text', placeholder: '2025/2026' },
                                            { name: 'starts_at', label: 'Start date', type: 'date' },
                                            { name: 'ends_at', label: 'End date', type: 'date' },
                                            { name: 'is_current', label: 'Set as current', type: 'select', options: [{ name: 'No', id: 0 }, { name: 'Yes', id: 1 }] },
                                        ]"
                                    />
                                    <FieldError :errors="generateForm.errors" field="academic_year_id" />
                                </div>
                                <PButton label="Generate" icon="pi pi-sync" severity="secondary" class="w-full" @click="generateRecords" />
                            </div>
                        </template>
                    </PCard>

                    <PCard class="shadow-sm">
                        <template #title>Payment Records</template>
                        <template #content>
                            <PDataTable :value="records" stripedRows responsiveLayout="scroll" class="text-sm">
                                <PColumn header="Student">
                                    <template #body="slotProps">
                                        {{ slotProps.data.student?.user?.profile?.first_name ?? '' }} {{ slotProps.data.student?.user?.profile?.last_name ?? '' }}
                                    </template>
                                </PColumn>
                                <PColumn header="Fee">
                                    <template #body="slotProps">
                                        {{ slotProps.data.fee_definition?.name ?? '—' }}
                                    </template>
                                </PColumn>
                                <PColumn field="amount_paid" header="Paid" />
                                <PColumn field="balance" header="Balance" />
                                <PColumn header="">
                                    <template #body="slotProps">
                                        <div class="flex gap-2">
                                            <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                            <PButton icon="pi pi-wallet" severity="success" text rounded @click="openPay(slotProps.data)" />
                                            <PButton icon="pi pi-refresh" severity="warning" text rounded @click="resetRecord(slotProps.data.id)" />
                                            <PButton icon="pi pi-file" severity="secondary" text rounded :onclick="() => (window.location.href = `/payments/records/${slotProps.data.id}/receipts`)" />
                                        </div>
                                    </template>
                                </PColumn>
                            </PDataTable>
                        </template>
                    </PCard>
                </div>
            </section>

            <PCard class="shadow-sm">
                <template #title>Pay Now</template>
                <template #content>
                    <div class="flex items-center gap-3">
                        <div>
                            <PInputText v-model="payForm.amount" placeholder="Amount to pay" class="w-60" />
                            <FieldError :errors="payForm.errors" field="amount" />
                        </div>
                        <PButton label="Apply Payment" icon="pi pi-check" severity="success" @click="submitPay" />
                        <span class="text-xs text-slate-500">Select a record first.</span>
                    </div>
                </template>
            </PCard>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Payment Record" />
    </AppShell>
</template>
