<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import StudentSelector from '../../components/StudentSelector.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';
import { useToast } from '../../composables/useToast';

const props = defineProps({
    students: Array,
    exams: Array,
    years: Array,
    terms: Array,
    classes: Array,
    sections: Array,
    marks: Array,
    selected: Object,
    examResult: Object,
    skillScores: Array,
    skills: Array,
    resultComments: Array,
    autoApplyComments: Boolean,
    currentAcademicYearId: Number,
    currentTermId: Number,
});

const studentOptions = computed(() =>
    (props.students || []).map((student) => ({
        label: student.user?.name ?? 'Unnamed',
        value: student.id,
    }))
);

const { can } = usePermissions();
const { showSuccess, showError } = useToast();

const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const filters = useForm({
    student_id: props.selected?.student_id ?? null,
    exam_id: props.selected?.exam_id ?? null,
    academic_year_id: props.selected?.academic_year_id ?? props.currentAcademicYearId ?? null,
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
    if (!props.examResult?.id) {
        showError('Please compute and view result first');
        return;
    }
    commentForm.put(`/results/${props.examResult.id}/comment`, {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess('Comment saved successfully');
        },
        onError: (errors) => {
            showError('Failed to save comment', Object.values(errors).join(', '));
        },
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

const downloadReportCard = () => {
    if (!filters.student_id || !filters.exam_id) {
        showError('Please select a student and exam first');
        return;
    }
    
    const params = new URLSearchParams({
        student_id: filters.student_id,
        exam_id: filters.exam_id,
    });
    
    if (filters.academic_year_id) {
        params.append('academic_year_id', filters.academic_year_id);
    }
    
    window.location.href = `/results/report-card?${params.toString()}`;
};

const classExportForm = useForm({
    class_id: null,
    exam_id: null,
    section_id: null,
    academic_year_id: props.currentAcademicYearId ?? null,
});

const downloadClassReportCards = () => {
    if (!classExportForm.class_id || !classExportForm.exam_id) {
        showError('Please select a class and exam first');
        return;
    }
    
    const params = new URLSearchParams({
        class_id: classExportForm.class_id,
        exam_id: classExportForm.exam_id,
    });
    
    if (classExportForm.section_id) {
        params.append('section_id', classExportForm.section_id);
    }
    
    if (classExportForm.academic_year_id) {
        params.append('academic_year_id', classExportForm.academic_year_id);
    }
    
    window.location.href = `/results/class-report-cards?${params.toString()}`;
};

const downloadClassBroadsheet = () => {
    if (!classExportForm.class_id || !classExportForm.exam_id) {
        showError('Please select a class and exam first');
        return;
    }
    
    const params = new URLSearchParams({
        class_id: classExportForm.class_id,
        exam_id: classExportForm.exam_id,
    });
    
    if (classExportForm.section_id) {
        params.append('section_id', classExportForm.section_id);
    }
    
    if (classExportForm.academic_year_id) {
        params.append('academic_year_id', classExportForm.academic_year_id);
    }
    
    window.location.href = `/results/class-broadsheet?${params.toString()}`;
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
                            <StudentSelector v-model="filters.student_id" :students="students" placeholder="Search student..." />
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
                    <div class="mt-4 flex gap-3">
                        <PButton label="View Results" icon="pi pi-search" severity="info" @click="submit" />
                        <PButton 
                            v-if="selected && selected.student_id && selected.exam_id"
                            label="Download Report Card (PDF)" 
                            icon="pi pi-file-pdf" 
                            severity="success" 
                            @click="downloadReportCard" 
                        />
                    </div>
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

            <PCard v-if="examResult" class="shadow-sm">
                <template #title>Domain Scores</template>
                <template #content>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <h3 class="mb-2 font-semibold text-slate-700">Psychomotor Domain</h3>
                            <div class="rounded border border-slate-200 bg-slate-50 p-3">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ examResult.psychomotor || 'Not Computed' }}
                                </div>
                                <div v-if="skillScores.filter(s => s.skill?.skill_type === 'psychomotor').length > 0" class="mt-2 text-sm text-slate-600">
                                    <div class="space-y-1">
                                        <div
                                            v-for="score in skillScores.filter(s => s.skill?.skill_type === 'psychomotor')"
                                            :key="score.id"
                                            class="flex justify-between"
                                        >
                                            <span>{{ score.skill?.name }}</span>
                                            <span class="font-semibold">{{ score.rating }}/5</span>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="mt-2 text-sm text-slate-500">
                                    No psychomotor scores recorded
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="mb-2 font-semibold text-slate-700">Affective Domain</h3>
                            <div class="rounded border border-slate-200 bg-slate-50 p-3">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ examResult.affective || 'Not Computed' }}
                                </div>
                                <div v-if="skillScores.filter(s => s.skill?.skill_type === 'affective').length > 0" class="mt-2 text-sm text-slate-600">
                                    <div class="space-y-1">
                                        <div
                                            v-for="score in skillScores.filter(s => s.skill?.skill_type === 'affective')"
                                            :key="score.id"
                                            class="flex justify-between"
                                        >
                                            <span>{{ score.skill?.name }}</span>
                                            <span class="font-semibold">{{ score.rating }}/5</span>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="mt-2 text-sm text-slate-500">
                                    No affective scores recorded
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </PCard>
            
            <PCard class="shadow-sm">
                <template #title>Class Report Cards Download</template>
                <template #content>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                        <div>
                            <ModelSelect
                                v-model="classExportForm.class_id"
                                :options="classes"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Class"
                            />
                            <FieldError :errors="classExportForm.errors" field="class_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="classExportForm.exam_id"
                                :options="exams"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Exam"
                            />
                            <FieldError :errors="classExportForm.errors" field="exam_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="classExportForm.section_id"
                                :options="sections"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Section (Optional)"
                            />
                            <FieldError :errors="classExportForm.errors" field="section_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="classExportForm.academic_year_id"
                                :options="years"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Academic Year"
                            />
                            <FieldError :errors="classExportForm.errors" field="academic_year_id" />
                        </div>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <PButton 
                            label="Download Class Report Cards (PDF)" 
                            icon="pi pi-file-pdf" 
                            severity="success" 
                            @click="downloadClassReportCards" 
                        />
                        <PButton 
                            label="Download Class Broadsheet (PDF)" 
                            icon="pi pi-file-excel" 
                            severity="info" 
                            @click="downloadClassBroadsheet" 
                        />
                    </div>
                    <div class="mt-3 text-sm text-slate-600">
                        <strong>Note:</strong> Report cards download a sample. Broadsheet shows all students with subjects as columns.
                    </div>
                </template>
            </PCard>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Mark Record" />
    </AppShell>
</template>
