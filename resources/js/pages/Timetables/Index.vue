<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';

const props = defineProps({
    timetables: Array,
    classes: Array,
    classLevels: Array,
    sections: Array,
    academicYears: Array,
    terms: Array,
    exams: Array,
});

const { can } = usePermissions();

const typeOptions = [
    { label: 'Class', value: 'class' },
    { label: 'Exam', value: 'exam' },
];

const form = useForm({
    name: '',
    type: 'class',
    class_id: null,
    section_id: null,
    academic_year_id: props.academicYears?.[0]?.id ?? null,
    term_id: props.terms?.[0]?.id ?? null,
    exam_id: null,
});

const showExam = computed(() => form.type === 'exam');

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
    form.type = record.type ?? 'class';
    form.class_id = record.class_id ?? record.school_class?.id ?? null;
    form.section_id = record.section_id ?? record.section?.id ?? null;
    form.academic_year_id = record.academic_year_id ?? record.academic_year?.id ?? null;
    form.term_id = record.term_id ?? record.term?.id ?? null;
    form.exam_id = record.exam_id ?? record.exam?.id ?? null;
};

const cancelEdit = () => {
    editingId.value = null;
    form.reset();
    form.clearErrors();
};

const submit = () => {
    if (editingId.value) {
        form.put(`/timetables/${editingId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingId.value = null;
                form.reset('name', 'class_id', 'section_id', 'exam_id');
            },
        });
        return;
    }

    form.post('/timetables', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('name', 'class_id', 'section_id', 'exam_id');
        },
    });
};
</script>

<template>
    <AppShell>
        <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
            <PCard class="shadow-sm">
                <template #title>Create Timetable</template>
                <template #content>
                    <div class="space-y-3">
                        <div>
                            <PInputText v-model="form.name" placeholder="Timetable name" class="w-full" />
                            <FieldError :errors="form.errors" field="name" />
                        </div>
                        <div>
                            <PDropdown v-model="form.type" :options="typeOptions" optionLabel="label" optionValue="value" class="w-full" />
                            <FieldError :errors="form.errors" field="type" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="form.class_id"
                                :options="classes"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Select class"
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
                            <FieldError :errors="form.errors" field="class_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="form.section_id"
                                :options="sections"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Select section"
                                :canCreate="can('manage.sections')"
                                createTitle="Add Section"
                                createEndpoint="/academics/sections"
                                :createFields="[
                                    {
                                        name: 'class_id',
                                        label: 'Class',
                                        type: 'model-select',
                                        options: classes,
                                        optionLabel: 'name',
                                        optionValue: 'id',
                                        canCreate: can('manage.classes'),
                                        createTitle: 'Add Class',
                                        createEndpoint: '/academics/classes',
                                        createFields: [
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
                                        ],
                                    },
                                    { name: 'name', label: 'Section name', type: 'text' },
                                ]"
                            />
                            <FieldError :errors="form.errors" field="section_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="form.academic_year_id"
                                :options="academicYears"
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
                                        options: academicYears,
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
                        <div v-if="showExam">
                            <ModelSelect
                                v-model="form.exam_id"
                                :options="exams"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Exam"
                                :canCreate="can('manage.exams')"
                                createTitle="Add Exam"
                                createEndpoint="/exams"
                                :createFields="[
                                    { name: 'name', label: 'Exam name', type: 'text' },
                                    {
                                        name: 'academic_year_id',
                                        label: 'Academic year',
                                        type: 'model-select',
                                        options: academicYears,
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
                                        name: 'term_id',
                                        label: 'Term',
                                        type: 'model-select',
                                        options: terms,
                                        optionLabel: 'name',
                                        optionValue: 'id',
                                        canCreate: can('manage.settings'),
                                        createTitle: 'Add Term',
                                        createEndpoint: '/terms',
                                        createFields: [
                                            {
                                                name: 'academic_year_id',
                                                label: 'Academic year',
                                                type: 'model-select',
                                                options: academicYears,
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
                                        ],
                                    },
                                    { name: 'starts_at', label: 'Start date', type: 'date' },
                                    { name: 'ends_at', label: 'End date', type: 'date' },
                                ]"
                            />
                            <FieldError :errors="form.errors" field="exam_id" />
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <PButton :label="editingId ? 'Update' : 'Create'" icon="pi pi-plus" severity="success" @click="submit" />
                            <PButton v-if="editingId" label="Cancel" severity="secondary" text @click="cancelEdit" />
                        </div>
                    </div>
                </template>
            </PCard>

            <PCard class="shadow-sm">
                <template #title>Timetables</template>
                <template #content>
                    <PDataTable :value="timetables" stripedRows responsiveLayout="scroll" class="text-sm">
                        <PColumn field="name" header="Name" />
                        <PColumn header="Class">
                            <template #body="slotProps">
                                {{ slotProps.data.school_class?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn header="Section">
                            <template #body="slotProps">
                                {{ slotProps.data.section?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn header="Type">
                            <template #body="slotProps">
                                <PTag :value="slotProps.data.type" />
                            </template>
                        </PColumn>
                        <PColumn header="">
                            <template #body="slotProps">
                                <div class="flex items-center gap-2">
                                    <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                    <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEdit(slotProps.data)" />
                                    <a :href="`/timetables/${slotProps.data.id}`" class="text-teal-700 hover:underline">Manage</a>
                                </div>
                            </template>
                        </PColumn>
                    </PDataTable>
                </template>
            </PCard>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Timetable Record" />
    </AppShell>
</template>
