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
    sections: Array,
    years: Array,
    terms: Array,
    paymentCategories: Array,
    invoiceTypes: Array,
});

const { can } = usePermissions();

const definitionForm = useForm({
    name: '',
    amount: '',
    payment_category_id: null,
    section_id: null,
    class_id: null,
    academic_year_id: null,
    term_id: null,
    gender: null,
});

const editingDefinitionId = ref(null);
const showView = ref(false);
const viewRecord = ref(null);
const showDefinitionForm = ref(false);
const genderOptions = [
    { label: 'Male', value: 'male' },
    { label: 'Female', value: 'female' },
];

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const openCreateDefinition = () => {
    editingDefinitionId.value = null;
    definitionForm.reset();
    definitionForm.clearErrors();
    showDefinitionForm.value = true;
};

const startEditDefinition = (record) => {
    editingDefinitionId.value = record.id;
    definitionForm.clearErrors();
    definitionForm.name = record.name ?? '';
    definitionForm.amount = record.amount ?? '';
    definitionForm.payment_category_id = record.payment_category_id ?? record.payment_category?.id ?? null;
    definitionForm.section_id = record.section_id ?? record.section?.id ?? null;
    definitionForm.class_id = record.class_id ?? record.school_class?.id ?? null;
    definitionForm.academic_year_id = record.academic_year_id ?? record.academic_year?.id ?? null;
    definitionForm.term_id = record.term_id ?? record.term?.id ?? null;
    definitionForm.gender = record.gender ?? null;
    showDefinitionForm.value = true;
};

const cancelEditDefinition = () => {
    editingDefinitionId.value = null;
    definitionForm.reset();
    definitionForm.clearErrors();
    showDefinitionForm.value = false;
};

const generateForm = useForm({
    invoice_type_id: null,
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
                showDefinitionForm.value = false;
            },
        });
        return;
    }

    definitionForm.post('/payments/definitions', {
        preserveScroll: true,
        onSuccess: () => {
            definitionForm.reset();
            showDefinitionForm.value = false;
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

const printReceipts = (id) => {
    window.open(`/payments/records/${id}/receipts`, '_blank', 'noopener');
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
        <div class="grid grid-cols-1 gap-6">
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_320px]">
                <PCard class="shadow-sm">
                    <template #title>
                        <div class="flex items-center justify-between">
                            <span>Fee Definitions</span>
                            <PButton label="New Fee" icon="pi pi-plus" severity="success" @click="openCreateDefinition" />
                        </div>
                    </template>
                    <template #content>
                        <PDataTable :value="definitions" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn field="name" header="Fee" />
                            <PColumn header="Category">
                                <template #body="slotProps">
                                    {{ slotProps.data.payment_category?.name ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn field="amount" header="Amount" />
                            <PColumn header="Class/Section">
                                <template #body="slotProps">
                                    <span>{{ slotProps.data.school_class?.name ?? 'All' }}</span>
                                    <span class="text-xs text-slate-400"> / {{ slotProps.data.section?.name ?? 'All' }}</span>
                                </template>
                            </PColumn>
                            <PColumn header="Year/Term">
                                <template #body="slotProps">
                                    <span>{{ slotProps.data.academic_year?.name ?? 'All' }}</span>
                                    <span class="text-xs text-slate-400"> / {{ slotProps.data.term?.name ?? 'All' }}</span>
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
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Generate Payment Records</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <ModelSelect
                                    v-model="generateForm.invoice_type_id"
                                    :options="invoiceTypes"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Invoice type"
                                    :canCreate="can('manage.payments')"
                                    createTitle="Add Invoice Type"
                                    createEndpoint="/payments/invoice-types"
                                    :createFields="[
                                        {
                                            name: 'name',
                                            label: 'Invoice name',
                                            type: 'text',
                                        },
                                        {
                                            name: 'amount',
                                            label: 'Amount',
                                            type: 'number',
                                        },
                                        {
                                            name: 'payment_category_id',
                                            label: 'Payment category',
                                            type: 'model-select',
                                            options: paymentCategories,
                                            optionLabel: 'name',
                                            optionValue: 'id',
                                            canCreate: can('manage.payments'),
                                            createTitle: 'Add Payment Category',
                                            createEndpoint: '/payments/categories',
                                            createFields: [{ name: 'name', label: 'Category name', type: 'text' }],
                                        },
                                        {
                                            name: 'section_id',
                                            label: 'Section',
                                            type: 'model-select',
                                            options: sections,
                                            optionLabel: 'name',
                                            optionValue: 'id',
                                        },
                                        {
                                            name: 'academic_year_id',
                                            label: 'Academic year',
                                            type: 'model-select',
                                            options: years,
                                            optionLabel: 'name',
                                            optionValue: 'id',
                                            canCreate: can('manage.settings'),
                                            createTitle: 'Add Academic Year',
                                            createEndpoint: '/academic-years',
                                            createFields: [
                                                { name: 'name', label: 'Name', type: 'text', placeholder: '2025/2026' },
                                                { name: 'starts_at', label: 'Start date', type: 'date' },
                                                { name: 'ends_at', label: 'End date', type: 'date' },
                                                { name: 'is_current', label: 'Set as current', type: 'select', options: [{ name: 'No', id: 0 }, { name: 'Yes', id: 1 }] },
                                            ],
                                        },
                                        {
                                            name: 'class_id',
                                            label: 'Class',
                                            type: 'model-select',
                                            options: classes,
                                            optionLabel: 'name',
                                            optionValue: 'id',
                                        },
                                        {
                                            name: 'gender',
                                            label: 'Gender',
                                            type: 'select',
                                            options: [
                                                { name: 'Male', id: 'male' },
                                                { name: 'Female', id: 'female' },
                                            ],
                                        },
                                        {
                                            name: 'term_id',
                                            label: 'Term',
                                            type: 'model-select',
                                            options: terms,
                                            optionLabel: 'name',
                                            optionValue: 'id',
                                        },
                                    ]"
                                />
                                <FieldError :errors="generateForm.errors" field="invoice_type_id" />
                            </div>
                            <PButton label="Generate" icon="pi pi-sync" severity="secondary" class="w-full" @click="generateRecords" />
                        </div>
                    </template>
                </PCard>
            </section>

            <PCard class="shadow-sm">
                <template #title>Payment Records</template>
                <template #content>
                    <PDataTable :value="records" stripedRows responsiveLayout="scroll" class="text-sm">
                        <PColumn header="Student">
                            <template #body="slotProps">
                                {{ slotProps.data.student?.user?.profile?.first_name ?? '' }} {{ slotProps.data.student?.user?.profile?.last_name ?? '' }}
                            </template>
                        </PColumn>
                        <PColumn header="Invoice">
                            <template #body="slotProps">
                                {{ slotProps.data.invoice_type?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn header="Category">
                            <template #body="slotProps">
                                {{ slotProps.data.invoice_type?.payment_category?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn field="amount_paid" header="Paid" />
                        <PColumn field="balance" header="Balance" />
                        <PColumn header="">
                            <template #body="slotProps">
                                <div class="flex gap-2">
                                    <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                    <PButton icon="pi pi-wallet" severity="success" text rounded @click="openPay(slotProps.data)" />
                                    <PButton icon="pi pi-print" severity="secondary" text rounded @click="printReceipts(slotProps.data.id)" />
                                    <PButton icon="pi pi-refresh" severity="warning" text rounded @click="resetRecord(slotProps.data.id)" />
                                </div>
                            </template>
                        </PColumn>
                    </PDataTable>
                </template>
            </PCard>

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

        <PDialog
            v-model:visible="showDefinitionForm"
            modal
            :header="editingDefinitionId ? 'Edit Fee Definition' : 'New Fee Definition'"
            class="w-full max-w-2xl"
            @update:visible="(value) => { if (!value) cancelEditDefinition(); }"
        >
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
                        v-model="definitionForm.payment_category_id"
                        :options="paymentCategories"
                        optionLabel="name"
                        optionValue="id"
                        placeholder="Payment category"
                        :canCreate="can('manage.payments')"
                        createTitle="Add Payment Category"
                        createEndpoint="/payments/categories"
                        :createFields="[{ name: 'name', label: 'Category name', type: 'text' }]"
                    />
                    <FieldError :errors="definitionForm.errors" field="payment_category_id" />
                </div>
                <div>
                    <ModelSelect
                        v-model="definitionForm.section_id"
                        :options="sections"
                        optionLabel="name"
                        optionValue="id"
                        placeholder="Section (optional)"
                    />
                    <FieldError :errors="definitionForm.errors" field="section_id" />
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
                        placeholder="Academic year (optional)"
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
                    <ModelSelect
                        v-model="definitionForm.term_id"
                        :options="terms"
                        optionLabel="name"
                        optionValue="id"
                        placeholder="Term (optional)"
                    />
                    <FieldError :errors="definitionForm.errors" field="term_id" />
                </div>
                <div>
                    <PDropdown
                        v-model="definitionForm.gender"
                        :options="genderOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Gender (optional)"
                        class="w-full"
                    />
                    <FieldError :errors="definitionForm.errors" field="gender" />
                </div>
                <div class="flex flex-wrap gap-2">
                    <PButton :label="editingDefinitionId ? 'Update Fee' : 'Create Fee'" icon="pi pi-check" severity="success" @click="createDefinition" />
                    <PButton label="Cancel" severity="secondary" text @click="cancelEditDefinition" />
                </div>
            </div>
        </PDialog>
    </AppShell>
</template>
