<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import DateField from '../../components/DateField.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';

const props = defineProps({
    exams: Array,
    years: Array,
    terms: Array,
});

const { can } = usePermissions();

const form = useForm({
    name: '',
    academic_year_id: null,
    term_id: null,
    starts_at: '',
    ends_at: '',
});

const editingId = ref(null);
const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const startEdit = (record) => {
    editingId.value = record.id;
    form.clearErrors();
    form.name = record.name ?? '';
    form.academic_year_id = record.academic_year_id ?? record.academic_year?.id ?? null;
    form.term_id = record.term_id ?? record.term?.id ?? null;
    form.starts_at = record.starts_at ?? '';
    form.ends_at = record.ends_at ?? '';
};

const cancelEdit = () => {
    editingId.value = null;
    form.reset();
    form.clearErrors();
};

const submit = () => {
    if (editingId.value) {
        form.put(`/exams/${editingId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingId.value = null;
                form.reset();
            },
        });
        return;
    }

    form.post('/exams', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const remove = (id) => {
    if (!confirm('Delete this exam?')) return;
    router.delete(`/exams/${id}`, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid gap-6">
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-[360px_1fr]">
                <PCard class="shadow-sm">
                    <template #title>New Exam</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <PInputText v-model="form.name" placeholder="Exam name" class="w-full" />
                                <FieldError :errors="form.errors" field="name" />
                            </div>
                            <div>
                                <ModelSelect
                                    v-model="form.academic_year_id"
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
                                <FieldError :errors="form.errors" field="academic_year_id" />
                            </div>
                            <div>
                                <ModelSelect
                                    v-model="form.term_id"
                                    :options="terms"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Term"
                                    :canCreate="can('manage.settings')"
                                    createTitle="Add Term"
                                    createEndpoint="/terms"
                                    :createFields="[
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
                                        { name: 'name', label: 'Term name', type: 'text' },
                                        { name: 'order', label: 'Order', type: 'number' },
                                        { name: 'starts_at', label: 'Start date', type: 'date' },
                                        { name: 'ends_at', label: 'End date', type: 'date' },
                                        { name: 'is_current', label: 'Set as current', type: 'select', options: [{ name: 'No', id: 0 }, { name: 'Yes', id: 1 }] },
                                    ]"
                                />
                                <FieldError :errors="form.errors" field="term_id" />
                            </div>
                            <div>
                                <DateField v-model="form.starts_at" placeholder="Start date" />
                                <FieldError :errors="form.errors" field="starts_at" />
                            </div>
                            <div>
                                <DateField v-model="form.ends_at" placeholder="End date" />
                                <FieldError :errors="form.errors" field="ends_at" />
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <PButton :label="editingId ? 'Update Exam' : 'Create Exam'" icon="pi pi-plus" severity="success" @click="submit" />
                                <PButton v-if="editingId" label="Cancel" severity="secondary" text @click="cancelEdit" />
                            </div>
                        </div>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Exams</template>
                    <template #content>
                        <PDataTable :value="exams" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn field="name" header="Exam" />
                            <PColumn header="Year">
                                <template #body="slotProps">
                                    {{ slotProps.data.academic_year?.name ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn header="Term">
                                <template #body="slotProps">
                                    {{ slotProps.data.term?.name ?? '—' }}
                                </template>
                            </PColumn>
                                <PColumn header="">
                                    <template #body="slotProps">
                                        <div class="flex gap-2">
                                            <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                            <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEdit(slotProps.data)" />
                                            <PButton icon="pi pi-trash" severity="danger" text rounded @click="remove(slotProps.data.id)" />
                                        </div>
                                    </template>
                                </PColumn>
                            </PDataTable>
                        </template>
                    </PCard>
                </section>
            </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Exam Record" />
    </AppShell>
</template>
