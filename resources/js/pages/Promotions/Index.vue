<script setup>
import { computed, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';

const props = defineProps({
    students: Array,
    classes: Array,
    classLevels: Array,
    sections: Array,
    academicYears: Array,
    promotions: Array,
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

const form = useForm({
    student_id: null,
    to_class_id: null,
    to_section_id: null,
    to_academic_year_id: props.academicYears?.[0]?.id ?? null,
    is_graduated: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        is_graduated: data.is_graduated ? 1 : 0,
    }));
    form.post('/promotions', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('student_id', 'to_class_id', 'to_section_id', 'is_graduated');
        },
    });
};

const resetPromotion = (id) => {
    if (!confirm('Reset this promotion?')) return;
    router.post(`/promotions/${id}/reset`, {}, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <PCard class="shadow-sm">
                <template #title>Promote Student</template>
                <template #content>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div>
                            <ModelSelect v-model="form.student_id" :options="studentOptions" optionLabel="label" optionValue="value" placeholder="Select student" />
                            <FieldError :errors="form.errors" field="student_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="form.to_class_id"
                                :options="classes"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="To class"
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
                            <FieldError :errors="form.errors" field="to_class_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="form.to_section_id"
                                :options="sections"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="To section"
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
                            <FieldError :errors="form.errors" field="to_section_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="form.to_academic_year_id"
                                :options="academicYears"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="To academic year"
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
                            <FieldError :errors="form.errors" field="to_academic_year_id" />
                        </div>
                        <div>
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input v-model="form.is_graduated" type="checkbox" class="h-4 w-4 rounded border-slate-300" />
                                Mark as graduated
                            </label>
                            <FieldError :errors="form.errors" field="is_graduated" />
                        </div>
                    </div>
                    <PButton label="Promote" icon="pi pi-arrow-up" severity="success" class="mt-4" @click="submit" />
                </template>
            </PCard>

            <PCard class="shadow-sm">
                <template #title>Recent Promotions</template>
                <template #content>
                    <PDataTable :value="promotions" stripedRows responsiveLayout="scroll" class="text-sm">
                        <PColumn header="Student">
                            <template #body="slotProps">
                                {{ slotProps.data.student?.user?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn header="From">
                            <template #body="slotProps">
                                {{ slotProps.data.from_class?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn header="To">
                            <template #body="slotProps">
                                {{ slotProps.data.to_class?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn header="Status">
                            <template #body="slotProps">
                                <PTag :value="slotProps.data.status" :severity="slotProps.data.status === 'reset' ? 'warning' : 'success'" />
                            </template>
                        </PColumn>
                        <PColumn header="">
                            <template #body="slotProps">
                                <div class="flex gap-2">
                                    <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                    <PButton icon="pi pi-replay" severity="secondary" text rounded @click="resetPromotion(slotProps.data.id)" />
                                </div>
                            </template>
                        </PColumn>
                    </PDataTable>
                </template>
            </PCard>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Promotion Record" />
    </AppShell>
</template>
