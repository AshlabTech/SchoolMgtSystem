<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';

const props = defineProps({
    subjects: Array,
    assignments: Array,
    classes: Array,
    classLevels: Array,
    sections: Array,
    years: Array,
    teachers: Array,
});

const { can } = usePermissions();

const subjectForm = useForm({
    name: '',
    code: '',
    description: '',
});

const assignmentForm = useForm({
    subject_id: null,
    class_id: null,
    section_id: null,
    teacher_id: null,
    academic_year_id: null,
});

const editingSubjectId = ref(null);
const editingAssignmentId = ref(null);
const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const startEditSubject = (record) => {
    editingSubjectId.value = record.id;
    subjectForm.clearErrors();
    subjectForm.name = record.name ?? '';
    subjectForm.code = record.code ?? '';
    subjectForm.description = record.description ?? '';
};

const cancelEditSubject = () => {
    editingSubjectId.value = null;
    subjectForm.reset();
    subjectForm.clearErrors();
};

const startEditAssignment = (record) => {
    editingAssignmentId.value = record.id;
    assignmentForm.clearErrors();
    assignmentForm.subject_id = record.subject_id ?? record.subject?.id ?? null;
    assignmentForm.class_id = record.class_id ?? record.school_class?.id ?? null;
    assignmentForm.section_id = record.section_id ?? record.section?.id ?? null;
    assignmentForm.teacher_id = record.teacher_id ?? record.teacher?.id ?? null;
    assignmentForm.academic_year_id = record.academic_year_id ?? record.academic_year?.id ?? null;
};

const cancelEditAssignment = () => {
    editingAssignmentId.value = null;
    assignmentForm.reset();
    assignmentForm.clearErrors();
};

const submitSubject = () => {
    if (editingSubjectId.value) {
        subjectForm.put(`/subjects/${editingSubjectId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingSubjectId.value = null;
                subjectForm.reset();
            },
        });
        return;
    }

    subjectForm.post('/subjects', {
        preserveScroll: true,
        onSuccess: () => {
            subjectForm.reset();
        },
    });
};

const submitAssignment = () => {
    if (editingAssignmentId.value) {
        assignmentForm.put(`/subjects/assignments/${editingAssignmentId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingAssignmentId.value = null;
                assignmentForm.reset();
            },
        });
        return;
    }

    assignmentForm.post('/subjects/assignments', {
        preserveScroll: true,
        onSuccess: () => {
            assignmentForm.reset();
        },
    });
};

const deleteSubject = (id) => {
    if (!confirm('Delete this subject?')) return;
    router.delete(`/subjects/${id}`, { preserveScroll: true });
};

const deleteAssignment = (id) => {
    if (!confirm('Delete this assignment?')) return;
    router.delete(`/subjects/assignments/${id}`, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-[360px_1fr]">
                <PCard class="shadow-sm">
                    <template #title>New Subject</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <PInputText v-model="subjectForm.name" placeholder="Subject name" class="w-full" />
                                <FieldError :errors="subjectForm.errors" field="name" />
                            </div>
                            <div>
                                <PInputText v-model="subjectForm.code" placeholder="Code (optional)" class="w-full" />
                                <FieldError :errors="subjectForm.errors" field="code" />
                            </div>
                            <div>
                                <PInputText v-model="subjectForm.description" placeholder="Description" class="w-full" />
                                <FieldError :errors="subjectForm.errors" field="description" />
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <PButton :label="editingSubjectId ? 'Update Subject' : 'Add Subject'" icon="pi pi-plus" severity="success" @click="submitSubject" />
                                <PButton v-if="editingSubjectId" label="Cancel" severity="secondary" text @click="cancelEditSubject" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <PDataTable :value="subjects" stripedRows responsiveLayout="scroll" class="text-sm">
                                <PColumn field="name" header="Subject" />
                                <PColumn field="code" header="Code" />
                                <PColumn header="">
                                    <template #body="slotProps">
                                        <div class="flex gap-2">
                                            <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                            <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEditSubject(slotProps.data)" />
                                            <PButton icon="pi pi-trash" severity="danger" text rounded @click="deleteSubject(slotProps.data.id)" />
                                        </div>
                                    </template>
                                </PColumn>
                            </PDataTable>
                        </div>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Subject Assignments</template>
                    <template #content>
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <div>
                                <ModelSelect
                                    v-model="assignmentForm.subject_id"
                                    :options="subjects"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Subject"
                                    :canCreate="can('manage.subjects')"
                                    createTitle="Add Subject"
                                    createEndpoint="/subjects"
                                    :createFields="[
                                        { name: 'name', label: 'Subject name', type: 'text' },
                                        { name: 'code', label: 'Code', type: 'text' },
                                        { name: 'description', label: 'Description', type: 'text' },
                                    ]"
                                />
                                <FieldError :errors="assignmentForm.errors" field="subject_id" />
                            </div>
                            <div>
                                <ModelSelect
                                    v-model="assignmentForm.class_id"
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
                                <FieldError :errors="assignmentForm.errors" field="class_id" />
                            </div>
                            <div>
                                <ModelSelect
                                    v-model="assignmentForm.section_id"
                                    :options="sections"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Section (optional)"
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
                                <FieldError :errors="assignmentForm.errors" field="section_id" />
                            </div>
                            <div>
                                <ModelSelect
                                    v-model="assignmentForm.teacher_id"
                                    :options="teachers"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Teacher"
                                />
                                <FieldError :errors="assignmentForm.errors" field="teacher_id" />
                            </div>
                            <div>
                                <ModelSelect
                                    v-model="assignmentForm.academic_year_id"
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
                                <FieldError :errors="assignmentForm.errors" field="academic_year_id" />
                            </div>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <PButton :label="editingAssignmentId ? 'Update Assignment' : 'Assign Subject'" icon="pi pi-check" severity="info" class="w-full" @click="submitAssignment" />
                            <PButton v-if="editingAssignmentId" label="Cancel" severity="secondary" text @click="cancelEditAssignment" />
                        </div>
                        <div class="mt-6">
                            <PDataTable :value="assignments" stripedRows responsiveLayout="scroll" class="text-sm">
                                <PColumn header="Subject">
                                    <template #body="slotProps">
                                        {{ slotProps.data.subject?.name ?? '—' }}
                                    </template>
                                </PColumn>
                                <PColumn header="Class">
                                    <template #body="slotProps">
                                        {{ slotProps.data.school_class?.name ?? '—' }}
                                    </template>
                                </PColumn>
                                <PColumn header="Teacher">
                                    <template #body="slotProps">
                                        {{ slotProps.data.teacher?.name ?? '—' }}
                                    </template>
                                </PColumn>
                                <PColumn header="">
                                    <template #body="slotProps">
                                        <div class="flex gap-2">
                                            <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                            <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEditAssignment(slotProps.data)" />
                                            <PButton icon="pi pi-trash" severity="danger" text rounded @click="deleteAssignment(slotProps.data.id)" />
                                        </div>
                                    </template>
                                </PColumn>
                            </PDataTable>
                        </div>
                    </template>
                </PCard>
            </section>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Subject Record" />
    </AppShell>
</template>
