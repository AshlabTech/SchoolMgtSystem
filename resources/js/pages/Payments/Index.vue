<script setup>
import { computed, ref } from 'vue';
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
    students: Array,
    classLevels: Array,
    sections: Array,
    years: Array,
    terms: Array,
    paymentCategories: Array,
    invoiceTypes: Array,
    paymentSummary: Object,
    filters: Object,
});

const { can } = usePermissions();

const showView = ref(false);
const viewRecord = ref(null);
const showDefinitionForm = ref(false);
const showGenerateDialog = ref(false);
const showPayDialog = ref(false);
const editingDefinitionId = ref(null);
const selectedRecord = ref(null);

const genderOptions = [
    { label: 'Male', value: 'male' },
    { label: 'Female', value: 'female' },
];

const studentDisplayName = (student) => student?.user?.name || student?.user?.profile?.first_name || 'Student';

const filterForm = useForm({
    status: props.filters?.status ?? null,
    class_id: props.filters?.class_id ?? null,
    student_id: props.filters?.student_id ?? null,
    search: props.filters?.search ?? '',
});

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

const generateForm = useForm({
    invoice_type_id: null,
});

const payForm = useForm({
    amount: '',
    record_id: null,
});

const studentOptions = computed(() =>
    (props.students || [])
        .filter((student) => !filterForm.class_id || student.current_enrollment?.class_id === filterForm.class_id)
        .map((student) => ({
            id: student.id,
            name: `${studentDisplayName(student)} (${student.current_enrollment?.school_class?.name ?? 'No Class'})`,
        }))
);

const formatCurrency = (amount) =>
    new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: 'NGN',
        maximumFractionDigits: 0,
    }).format(Number(amount ?? 0));

const getStudentName = (record) => {
    const profileName = `${record.student?.user?.profile?.first_name ?? ''} ${record.student?.user?.profile?.last_name ?? ''}`.trim();
    return record.student?.user?.name || profileName || 'Unknown Student';
};

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const applyFilters = () => {
    filterForm.get('/payments', {
        preserveState: true,
        replace: true,
    });
};

const clearFilters = () => {
    filterForm.reset();
    filterForm.get('/payments', {
        preserveState: true,
        replace: true,
    });
};

const exportRecords = (scope) => {
    const params = new URLSearchParams();
    if (filterForm.status) params.set('status', String(filterForm.status));
    if (filterForm.class_id) params.set('class_id', String(filterForm.class_id));
    if (filterForm.student_id) params.set('student_id', String(filterForm.student_id));
    if (filterForm.search) params.set('search', String(filterForm.search));
    params.set('scope', scope);
    window.open(`/payments/export?${params.toString()}`, '_blank', 'noopener');
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

const createDefinition = () => {
    if (editingDefinitionId.value) {
        definitionForm.put(`/payments/definitions/${editingDefinitionId.value}`, {
            preserveScroll: true,
            onSuccess: cancelEditDefinition,
        });
        return;
    }

    definitionForm.post('/payments/definitions', {
        preserveScroll: true,
        onSuccess: cancelEditDefinition,
    });
};

const generateRecords = () => {
    generateForm.post('/payments/records/generate', {
        preserveScroll: true,
        onSuccess: () => {
            generateForm.reset();
            showGenerateDialog.value = false;
        },
    });
};

const openPay = (record) => {
    selectedRecord.value = record;
    payForm.record_id = record.id;
    payForm.amount = '';
    showPayDialog.value = true;
};

const submitPay = () => {
    if (!payForm.record_id) return;
    payForm.post(`/payments/records/${payForm.record_id}/pay`, {
        preserveScroll: true,
        onSuccess: () => {
            payForm.reset();
            showPayDialog.value = false;
        },
    });
};

const printReceipts = (id) => {
    window.open(`/payments/records/${id}/receipts`, '_blank', 'noopener');
};

const resetRecord = (id) => {
    if (!confirm('Reset this payment record?')) return;
    router.post(`/payments/records/${id}/reset`, {}, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                <PCard class="shadow-sm">
                    <template #content>
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Billed</div>
                        <div class="mt-2 text-xl font-semibold">{{ formatCurrency(props.paymentSummary?.total_billed) }}</div>
                    </template>
                </PCard>
                <PCard class="shadow-sm">
                    <template #content>
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Collected</div>
                        <div class="mt-2 text-xl font-semibold text-emerald-700">{{ formatCurrency(props.paymentSummary?.total_paid) }}</div>
                    </template>
                </PCard>
                <PCard class="shadow-sm">
                    <template #content>
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Outstanding</div>
                        <div class="mt-2 text-xl font-semibold text-amber-700">{{ formatCurrency(props.paymentSummary?.total_outstanding) }}</div>
                    </template>
                </PCard>
                <PCard class="shadow-sm">
                    <template #content>
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Paid / Unpaid</div>
                        <div class="mt-2 text-xl font-semibold">{{ props.paymentSummary?.paid_records ?? 0 }} / {{ props.paymentSummary?.unpaid_records ?? 0 }}</div>
                    </template>
                </PCard>
            </section>

            <PCard class="shadow-sm">
                <template #title>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span>Payment Workspace</span>
                        <div class="flex flex-wrap gap-2">
                            <PButton label="New Fee" icon="pi pi-plus" severity="success" @click="openCreateDefinition" />
                            <PButton label="Generate Records" icon="pi pi-sync" severity="secondary" @click="showGenerateDialog = true" />
                            <PButton label="Export Full" icon="pi pi-download" severity="contrast" @click="exportRecords('all')" />
                            <PButton label="Export Paid" icon="pi pi-download" severity="success" text @click="exportRecords('paid')" />
                            <PButton label="Export Unpaid" icon="pi pi-download" severity="warning" text @click="exportRecords('unpaid')" />
                        </div>
                    </div>
                </template>
                <template #content>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-5">
                        <ModelSelect
                            v-model="filterForm.class_id"
                            :options="classes"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Load by class"
                        />
                        <ModelSelect
                            v-model="filterForm.student_id"
                            :options="studentOptions"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Specific student"
                        />
                        <PDropdown
                            v-model="filterForm.status"
                            :options="[{ label: 'All Statuses', value: null }, { label: 'Paid', value: 'paid' }, { label: 'Unpaid', value: 'unpaid' }]"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Status"
                            class="w-full"
                        />
                        <PInputText v-model="filterForm.search" placeholder="Student, invoice or reference..." class="w-full" />
                        <div class="flex gap-2">
                            <PButton label="Apply" icon="pi pi-filter" severity="info" class="flex-1" @click="applyFilters" />
                            <PButton label="Clear" severity="secondary" text @click="clearFilters" />
                        </div>
                    </div>
                </template>
            </PCard>

            <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_1.2fr]">
                <PCard class="shadow-sm">
                    <template #title>Fee Definitions</template>
                    <template #content>
                        <PDataTable :value="definitions" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn field="name" header="Fee" />
                            <PColumn header="Category">
                                <template #body="slotProps">
                                    {{ slotProps.data.payment_category?.name ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn field="amount" header="Amount" />
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
                    <template #title>Payment Records</template>
                    <template #content>
                        <PDataTable :value="records" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn header="Student">
                                <template #body="slotProps">
                                    <div class="font-medium">{{ getStudentName(slotProps.data) }}</div>
                                    <div class="text-xs text-slate-500">
                                        {{ slotProps.data.student?.current_enrollment?.school_class?.name ?? '—' }}
                                    </div>
                                </template>
                            </PColumn>
                            <PColumn header="Invoice">
                                <template #body="slotProps">
                                    {{ slotProps.data.invoice_type?.name ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn header="Paid">
                                <template #body="slotProps">
                                    {{ formatCurrency(slotProps.data.amount_paid) }}
                                </template>
                            </PColumn>
                            <PColumn header="Balance">
                                <template #body="slotProps">
                                    {{ formatCurrency(slotProps.data.balance) }}
                                </template>
                            </PColumn>
                            <PColumn header="Status">
                                <template #body="slotProps">
                                    <PTag :value="slotProps.data.is_paid ? 'Paid' : 'Unpaid'" :severity="slotProps.data.is_paid ? 'success' : 'warning'" />
                                </template>
                            </PColumn>
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
            </section>
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
                    <ModelSelect v-model="definitionForm.section_id" :options="sections" optionLabel="name" optionValue="id" placeholder="Section (optional, auto from class)" />
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
                    />
                    <FieldError :errors="definitionForm.errors" field="academic_year_id" />
                </div>
                <div>
                    <ModelSelect v-model="definitionForm.term_id" :options="terms" optionLabel="name" optionValue="id" placeholder="Term (optional)" />
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

        <PDialog v-model:visible="showGenerateDialog" modal header="Generate Payment Records" class="w-full max-w-xl">
            <div class="space-y-3">
                <ModelSelect
                    v-model="generateForm.invoice_type_id"
                    :options="invoiceTypes"
                    optionLabel="name"
                    optionValue="id"
                    placeholder="Invoice type"
                />
                <FieldError :errors="generateForm.errors" field="invoice_type_id" />
                <PButton label="Generate" icon="pi pi-sync" severity="success" class="w-full" @click="generateRecords" />
            </div>
        </PDialog>

        <PDialog v-model:visible="showPayDialog" modal header="Apply Payment" class="w-full max-w-lg">
            <div class="space-y-3">
                <div class="rounded-lg bg-slate-50 p-3 text-sm">
                    <div class="font-medium">{{ selectedRecord ? getStudentName(selectedRecord) : 'No record selected' }}</div>
                    <div class="text-slate-500">Balance: {{ formatCurrency(selectedRecord?.balance) }}</div>
                </div>
                <PInputText v-model="payForm.amount" placeholder="Amount to pay" class="w-full" />
                <FieldError :errors="payForm.errors" field="amount" />
                <PButton label="Apply Payment" icon="pi pi-check" severity="success" class="w-full" @click="submitPay" />
            </div>
        </PDialog>
    </AppShell>
</template>
