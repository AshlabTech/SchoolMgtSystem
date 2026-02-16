<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';

const props = defineProps({
    students: Array,
    exams: Array,
    years: Array,
    terms: Array,
    marks: Array,
    selected: Object,
    examResult: Object,
    resultComments: Array,
    autoApplyComments: Boolean,
});

const studentOptions = computed(() =>
    (props.students || []).map((student) => ({
        label: student.user?.name ?? 'Unnamed',
        value: student.id,
    }))
);

const { can } = usePermissions();

const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const filters = useForm({
    student_id: props.selected?.student_id ?? null,
    exam_id: props.selected?.exam_id ?? null,
    academic_year_id: props.selected?.academic_year_id ?? null,
});

const submit = () => {
    filters.get('/results', {
        preserveState: true,
        replace: true,
    });
};

const commentForm = useForm({
    result_comment_id: null,
});

const saveComment = () => {
    if (!props.examResult?.id) return;
    commentForm.put(`/results/${props.examResult.id}/comment`, {
        preserveScroll: true,
    });
};

const totalFor = (mark) => {
    const t1 = Number(mark.t1 || 0);
    const t2 = Number(mark.t2 || 0);
    const t3 = Number(mark.t3 || 0);
    const t4 = Number(mark.t4 || 0);
    const exm = Number(mark.exm || 0);
    return t1 + t2 + t3 + t4 + exm;
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <PCard class="shadow-sm">
                <template #title>Result Viewer</template>
                <template #content>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                        <div>
                            <ModelSelect v-model="filters.student_id" :options="studentOptions" optionLabel="label" optionValue="value" placeholder="Student" />
                            <FieldError :errors="filters.errors" field="student_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="filters.exam_id"
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
                            <FieldError :errors="filters.errors" field="exam_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="filters.academic_year_id"
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
                            <FieldError :errors="filters.errors" field="academic_year_id" />
                        </div>
                    </div>
                    <PButton label="View Results" icon="pi pi-search" severity="info" class="mt-4" @click="submit" />
                </template>
            </PCard>

            <PCard class="shadow-sm">
                <template #title>Marks</template>
                <template #content>
                    <PDataTable :value="marks" stripedRows responsiveLayout="scroll" class="text-sm">
                        <PColumn header="Subject">
                            <template #body="slotProps">
                                {{ slotProps.data.subject?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn field="t1" header="T1" />
                        <PColumn field="t2" header="T2" />
                        <PColumn field="t3" header="T3" />
                        <PColumn field="t4" header="T4" />
                        <PColumn field="exm" header="Exam" />
                        <PColumn header="Total">
                            <template #body="slotProps">
                                {{ totalFor(slotProps.data) }}
                            </template>
                        </PColumn>
                        <PColumn header="Grade">
                            <template #body="slotProps">
                                {{ slotProps.data.grade?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn header="">
                            <template #body="slotProps">
                                <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                            </template>
                        </PColumn>
                    </PDataTable>
                </template>
            </PCard>

            <PCard class="shadow-sm">
                <template #title>Result Comment</template>
                <template #content>
                    <div v-if="!examResult" class="text-sm text-slate-500">
                        Compute and view result first before assigning a comment.
                    </div>
                    <div v-else-if="autoApplyComments" class="text-sm text-slate-600">
                        Auto comment is enabled in settings. Current comment:
                        <span class="font-medium">{{ examResult.teacher_comment || '—' }}</span>
                    </div>
                    <div v-else class="grid grid-cols-1 gap-3 md:grid-cols-[1fr_auto]">
                        <PDropdown
                            v-model="commentForm.result_comment_id"
                            :options="resultComments"
                            optionLabel="comment"
                            optionValue="id"
                            placeholder="Select predefined comment"
                            class="w-full"
                        />
                        <PButton label="Save Comment" icon="pi pi-save" severity="success" @click="saveComment" />
                    </div>
                </template>
            </PCard>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Mark Record" />
    </AppShell>
</template>
