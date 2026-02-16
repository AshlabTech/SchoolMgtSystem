<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import { usePermissions } from '../../composables/usePermissions';
import { useToast } from '../../composables/useToast';

const props = defineProps({
    exams: Array,
    classes: Array,
    classLevels: Array,
    sections: Array,
    years: Array,
    terms: Array,
    subjects: Array,
    numberOfCaComponents: Number,
});

const subjectOptions = computed(() =>
    (props.subjects || []).map((assignment) => ({
        label: assignment.subject?.name ?? 'Subject',
        value: assignment.subject_id,
    }))
);

const { can } = usePermissions();
const { showSuccess, showError } = useToast();

const filter = useForm({
    exam_id: null,
    class_id: null,
    section_id: null,
    academic_year_id: null,
    subject_id: null,
    entries: [],
});

const students = ref([]);

const loadStudents = async () => {
    if (!filter.class_id) {
        showError('Please select a class first');
        return;
    }
    try {
        const token =
            typeof document !== 'undefined'
                ? document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                : null;
        const response = await fetch('/marks/students', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(token ? { 'X-CSRF-TOKEN': token } : {}),
            },
            body: JSON.stringify({
                class_id: filter.class_id,
                section_id: filter.section_id,
                academic_year_id: filter.academic_year_id,
                exam_id: filter.exam_id,
                subject_id: filter.subject_id,
            }),
        });
        
        if (!response.ok) {
            throw new Error('Failed to load students');
        }
        
        const data = await response.json();
        students.value = data.map((item) => ({
            student_id: item.student_id,
            name: `${item.student.user.profile.first_name} ${item.student.user.profile.last_name}`,
            t1: item.t1 ?? '',
            t2: item.t2 ?? '',
            t3: item.t3 ?? '',
            t4: item.t4 ?? '',
            exm: item.exm ?? '',
        }));
        showSuccess('Students loaded successfully');
    } catch (error) {
        showError('Failed to load students', error.message);
    }
};

const submit = () => {
    filter.entries = students.value;
    filter.post('/marks', {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess('Marks saved successfully');
        },
        onError: (errors) => {
            showError('Failed to save marks', Object.values(errors).join(', '));
        },
    });
};

// Show/hide CA columns based on settings
const showT1 = computed(() => props.numberOfCaComponents >= 1);
const showT2 = computed(() => props.numberOfCaComponents >= 2);
const showT3 = computed(() => props.numberOfCaComponents >= 3);
const showT4 = computed(() => props.numberOfCaComponents >= 4);
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <PCard class="shadow-sm">
                <template #title>Marks Entry</template>
                <template #content>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3 xl:grid-cols-5">
                        <div>
                            <ModelSelect
                                v-model="filter.exam_id"
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
                                        ],
                                    },
                                    { name: 'starts_at', label: 'Start date', type: 'date' },
                                    { name: 'ends_at', label: 'End date', type: 'date' },
                                ]"
                            />
                            <FieldError :errors="filter.errors" field="exam_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="filter.academic_year_id"
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
                            <FieldError :errors="filter.errors" field="academic_year_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="filter.class_id"
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
                            <FieldError :errors="filter.errors" field="class_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="filter.section_id"
                                :options="sections"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Section"
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
                            <FieldError :errors="filter.errors" field="section_id" />
                        </div>
                        <div>
                            <ModelSelect v-model="filter.subject_id" :options="subjectOptions" optionLabel="label" optionValue="value" placeholder="Subject" />
                            <FieldError :errors="filter.errors" field="subject_id" />
                        </div>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <PButton label="Load Students" icon="pi pi-users" severity="secondary" @click="loadStudents" />
                        <PButton label="Save Marks" icon="pi pi-save" severity="success" @click="submit" />
                    </div>

                    <div class="mt-6">
                        <PDataTable :value="students" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn field="name" header="Student" />
                            <PColumn v-if="showT1" header="T1">
                                <template #body="slotProps">
                                    <PInputText v-model="slotProps.data.t1" class="w-20" />
                                </template>
                            </PColumn>
                            <PColumn v-if="showT2" header="T2">
                                <template #body="slotProps">
                                    <PInputText v-model="slotProps.data.t2" class="w-20" />
                                </template>
                            </PColumn>
                            <PColumn v-if="showT3" header="T3">
                                <template #body="slotProps">
                                    <PInputText v-model="slotProps.data.t3" class="w-20" />
                                </template>
                            </PColumn>
                            <PColumn v-if="showT4" header="T4">
                                <template #body="slotProps">
                                    <PInputText v-model="slotProps.data.t4" class="w-20" />
                                </template>
                            </PColumn>
                            <PColumn header="Exam">
                                <template #body="slotProps">
                                    <PInputText v-model="slotProps.data.exm" class="w-20" />
                                </template>
                            </PColumn>
                        </PDataTable>
                    </div>
                </template>
            </PCard>
        </div>
    </AppShell>
</template>
