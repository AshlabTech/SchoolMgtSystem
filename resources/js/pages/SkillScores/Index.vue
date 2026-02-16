<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import { usePermissions } from '../../composables/usePermissions';
import { useToast } from '../../composables/useToast';

const props = defineProps({
    classes: Array,
    sections: Array,
    exams: Array,
    years: Array,
    students: Array,
    skills: Array,
    skillScores: Array,
    selected: Object,
});

const { can } = usePermissions();
const { showSuccess, showError } = useToast();

const filters = useForm({
    class_id: props.selected?.class_id ?? null,
    section_id: props.selected?.section_id ?? null,
    exam_id: props.selected?.exam_id ?? null,
    academic_year_id: props.selected?.academic_year_id ?? null,
});

const submit = () => {
    filters.get('/skill-scores', {
        preserveState: true,
        replace: true,
    });
};

// Group skills by type
const psychomotorSkills = computed(() => 
    (props.skills || []).filter(s => s.skill_type === 'psychomotor')
);

const affectiveSkills = computed(() => 
    (props.skills || []).filter(s => s.skill_type === 'affective')
);

// Create a matrix of scores: [student_id][skill_id] = rating
const scoreMatrix = computed(() => {
    const matrix = {};
    (props.skillScores || []).forEach(score => {
        if (!matrix[score.student_id]) {
            matrix[score.student_id] = {};
        }
        matrix[score.student_id][score.skill_id] = score.rating;
    });
    return matrix;
});

const ratings = [
    { label: '5 - Excellent', value: 5 },
    { label: '4 - Good', value: 4 },
    { label: '3 - Satisfactory', value: 3 },
    { label: '2 - Needs Improvement', value: 2 },
    { label: '1 - Poor', value: 1 },
];

const getRatingColor = (rating) => {
    if (rating >= 5) return 'bg-green-100 text-green-800';
    if (rating >= 4) return 'bg-blue-100 text-blue-800';
    if (rating >= 3) return 'bg-yellow-100 text-yellow-800';
    if (rating >= 2) return 'bg-orange-100 text-orange-800';
    return 'bg-red-100 text-red-800';
};

const bulkForm = useForm({
    scores: [],
});

const updateScore = (studentId, skillId, rating) => {
    if (!filters.exam_id || !filters.class_id) return;
    
    const scoreData = {
        student_id: studentId,
        skill_id: skillId,
        exam_id: filters.exam_id,
        class_id: filters.class_id,
        section_id: filters.section_id,
        academic_year_id: filters.academic_year_id,
        rating: parseInt(rating),
        comment: null,
    };

    // Find if this score already exists in our bulk form
    const existingIndex = bulkForm.scores.findIndex(
        s => s.student_id === studentId && s.skill_id === skillId
    );

    if (existingIndex >= 0) {
        bulkForm.scores[existingIndex] = scoreData;
    } else {
        bulkForm.scores.push(scoreData);
    }
};

const saveBulk = () => {
    if (bulkForm.scores.length === 0) {
        showError('No scores to save');
        return;
    }

    bulkForm.post('/skill-scores/bulk', {
        preserveScroll: true,
        onSuccess: () => {
            bulkForm.scores = [];
            showSuccess(`${bulkForm.scores.length} skill score(s) saved successfully`);
        },
        onError: (errors) => {
            showError('Failed to save skill scores', Object.values(errors).join(', '));
        },
    });
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <PCard class="shadow-sm">
                <template #title>Skill Score Entry - Filter</template>
                <template #content>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                        <div>
                            <ModelSelect
                                v-model="filters.class_id"
                                :options="classes"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Select class"
                            />
                            <FieldError :errors="filters.errors" field="class_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="filters.section_id"
                                :options="sections"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Section (optional)"
                            />
                            <FieldError :errors="filters.errors" field="section_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="filters.exam_id"
                                :options="exams"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Select exam"
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
                            />
                            <FieldError :errors="filters.errors" field="academic_year_id" />
                        </div>
                    </div>
                    <PButton label="Load Students & Skills" icon="pi pi-search" severity="info" class="mt-4" @click="submit" />
                </template>
            </PCard>

            <PCard v-if="students.length > 0" class="shadow-sm">
                <template #title>
                    <div class="flex items-center justify-between">
                        <span>Psychomotor Domain Scores</span>
                        <PButton
                            v-if="can('manage.marks') && bulkForm.scores.length > 0"
                            :label="`Save ${bulkForm.scores.length} Score(s)`"
                            icon="pi pi-save"
                            severity="success"
                            size="small"
                            @click="saveBulk"
                        />
                    </div>
                </template>
                <template #content>
                    <div v-if="psychomotorSkills.length === 0" class="text-sm text-slate-500">
                        No psychomotor skills defined for this class level.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="border-b">
                                <tr>
                                    <th class="px-2 py-2 text-left font-medium">Student</th>
                                    <th v-for="skill in psychomotorSkills" :key="skill.id" class="px-2 py-2 text-center font-medium">
                                        {{ skill.name }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="student in students" :key="student.id" class="border-b">
                                    <td class="px-2 py-2 font-medium">{{ student.user?.name ?? 'Unnamed' }}</td>
                                    <td v-for="skill in psychomotorSkills" :key="skill.id" class="px-2 py-2 text-center">
                                        <PDropdown
                                            v-if="can('manage.marks')"
                                            :modelValue="scoreMatrix[student.id]?.[skill.id] ?? null"
                                            :options="ratings"
                                            optionLabel="label"
                                            optionValue="value"
                                            placeholder="Rate"
                                            class="w-full"
                                            @update:modelValue="(val) => updateScore(student.id, skill.id, val)"
                                        />
                                        <span
                                            v-else
                                            :class="[
                                                'inline-flex rounded px-2 py-1 text-xs font-semibold',
                                                getRatingColor(scoreMatrix[student.id]?.[skill.id] ?? 0)
                                            ]"
                                        >
                                            {{ scoreMatrix[student.id]?.[skill.id] ?? '—' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
            </PCard>

            <PCard v-if="students.length > 0" class="shadow-sm">
                <template #title>Affective Domain Scores</template>
                <template #content>
                    <div v-if="affectiveSkills.length === 0" class="text-sm text-slate-500">
                        No affective skills defined for this class level.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="border-b">
                                <tr>
                                    <th class="px-2 py-2 text-left font-medium">Student</th>
                                    <th v-for="skill in affectiveSkills" :key="skill.id" class="px-2 py-2 text-center font-medium">
                                        {{ skill.name }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="student in students" :key="student.id" class="border-b">
                                    <td class="px-2 py-2 font-medium">{{ student.user?.name ?? 'Unnamed' }}</td>
                                    <td v-for="skill in affectiveSkills" :key="skill.id" class="px-2 py-2 text-center">
                                        <PDropdown
                                            v-if="can('manage.marks')"
                                            :modelValue="scoreMatrix[student.id]?.[skill.id] ?? null"
                                            :options="ratings"
                                            optionLabel="label"
                                            optionValue="value"
                                            placeholder="Rate"
                                            class="w-full"
                                            @update:modelValue="(val) => updateScore(student.id, skill.id, val)"
                                        />
                                        <span
                                            v-else
                                            :class="[
                                                'inline-flex rounded px-2 py-1 text-xs font-semibold',
                                                getRatingColor(scoreMatrix[student.id]?.[skill.id] ?? 0)
                                            ]"
                                        >
                                            {{ scoreMatrix[student.id]?.[skill.id] ?? '—' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
            </PCard>
        </div>
    </AppShell>
</template>
