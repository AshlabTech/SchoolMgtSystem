<script setup>
import { ref, computed, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import DateField from '../../components/DateField.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';

const props = defineProps({
    students: Array,
    classes: Array,
    classLevels: Array,
    years: Array,
});

const { can } = usePermissions();

const genderOptions = [
    { label: 'Male', value: 'male' },
    { label: 'Female', value: 'female' },
    { label: 'Other', value: 'other' },
];

const form = useForm({
    first_name: '',
    last_name: '',
    other_name: '',
    gender: '',
    date_of_birth: '',
    phone: '',
    address: '',
    email: '',
    username: '',
    admission_no: '',
    class_id: null,
    section_id: null,
    academic_year_id: null,
});

const editingId = ref(null);
const showForm = ref(false);
const showView = ref(false);
const viewRecord = ref(null);
const selectedClassId = ref(null);
const selectedYearId = ref(null);
const globalFilter = ref('');
const originalClassId = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const openCreate = () => {
    editingId.value = null;
    form.reset();
    form.clearErrors();
    if (selectedClassId.value) {
        form.class_id = selectedClassId.value;
    }
    originalClassId.value = null;
    showForm.value = true;
};

const startEdit = (record) => {
    editingId.value = record.id;
    showForm.value = true;
    form.clearErrors();
    form.first_name = record.user?.profile?.first_name ?? '';
    form.last_name = record.user?.profile?.last_name ?? '';
    form.other_name = record.user?.profile?.other_name ?? '';
    form.gender = record.user?.profile?.gender ?? '';
    form.date_of_birth = record.user?.profile?.date_of_birth ?? '';
    form.phone = record.user?.profile?.phone ?? '';
    form.address = record.user?.profile?.address ?? '';
    form.email = record.user?.email ?? '';
    form.username = record.user?.username ?? '';
    form.admission_no = record.admission_no ?? '';
    form.class_id = record.current_enrollment?.class_id ?? null;
    form.section_id = record.current_enrollment?.section_id ?? null;
    form.academic_year_id = record.current_enrollment?.academic_year_id ?? null;
    originalClassId.value = form.class_id;
};

const cancelEdit = () => {
    editingId.value = null;
    form.reset();
    form.clearErrors();
    showForm.value = false;
    originalClassId.value = null;
};

const submit = () => {
    if (editingId.value) {
        form.put(`/students/${editingId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingId.value = null;
                form.reset();
                showForm.value = false;
            },
        });
        return;
    }

    form.post('/students', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
};

const deactivate = (id) => {
    if (!confirm('Deactivate this student?')) return;
    router.delete(`/students/${id}`, { preserveScroll: true });
};

const classesWithCounts = computed(() =>
    (props.classes || []).map((cls) => {
        const count = (props.students || []).filter((student) => {
            const matchesYear = selectedYearId.value
                ? student.current_enrollment?.academic_year_id === selectedYearId.value
                : true;
            return matchesYear && student.current_enrollment?.class_id === cls.id;
        }).length;
        return { ...cls, count };
    })
);

const filteredStudents = computed(() => {
    let list = props.students || [];
    if (selectedYearId.value) {
        list = list.filter((student) => student.current_enrollment?.academic_year_id === selectedYearId.value);
    }
    if (selectedClassId.value) {
        list = list.filter((student) => student.current_enrollment?.class_id === selectedClassId.value);
    }
    return list;
});

const totalCount = computed(() => filteredStudents.value.length);
const readyToShow = computed(() => Boolean(selectedYearId.value || selectedClassId.value));

watch(
    () => form.class_id,
    (value) => {
        if (editingId.value && originalClassId.value && value !== originalClassId.value) {
            form.section_id = null;
        }
    }
);
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[260px_1fr]">
            <aside class="space-y-4">
                <PCard class="shadow-sm">
                    <template #title>Classes</template>
                    <template #content>
                        <div class="mb-3">
                            <PDropdown
                                v-model="selectedYearId"
                                :options="years"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="All academic years"
                                showClear
                                class="w-full"
                            />
                        </div>
                        <div class="flex flex-col gap-2">
                            <button
                                type="button"
                                class="flex items-center justify-between rounded-lg px-3 py-2 text-sm"
                                :class="selectedClassId === null ? 'bg-teal-50 text-teal-700' : 'hover:bg-slate-100 text-slate-600'"
                                @click="selectedClassId = null"
                            >
                                <span>All Classes</span>
                                <span class="text-xs text-slate-400">{{ totalCount }}</span>
                            </button>
                            <button
                                v-for="cls in classesWithCounts"
                                :key="cls.id"
                                type="button"
                                class="flex items-center justify-between rounded-lg px-3 py-2 text-sm"
                                :class="selectedClassId === cls.id ? 'bg-teal-50 text-teal-700' : 'hover:bg-slate-100 text-slate-600'"
                                @click="selectedClassId = cls.id"
                            >
                                <span>{{ cls.name }}</span>
                                <span class="text-xs text-slate-400">{{ cls.count }}</span>
                            </button>
                        </div>
                    </template>
                </PCard>
            </aside>

            <div class="grid grid-cols-1 gap-6">
                <PCard class="shadow-sm">
                    <template #title>Students</template>
                    <template #content>
                        <div class="mb-4 flex flex-wrap items-center gap-3">
                            <PButton label="New Student" icon="pi pi-user-plus" severity="success" @click="openCreate" />
                            <span class="text-xs text-slate-500">Click a class on the left to filter.</span>
                            <div class="ml-auto">
                                <PInputText
                                    v-model="globalFilter"
                                    :disabled="!readyToShow"
                                    :placeholder="readyToShow ? 'Search students...' : 'Select a year or class to search'"
                                    class="w-64"
                                />
                            </div>
                        </div>
                        <div v-if="!readyToShow" class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
                            Select an academic year or a class to view enrolled students.
                        </div>
                        <PDataTable
                            v-else
                            :value="filteredStudents"
                            stripedRows
                            responsiveLayout="scroll"
                            class="text-sm"
                            :globalFilterFields="['admission_no','user.profile.first_name','user.profile.last_name','user.name']"
                            :filters="{ global: { value: globalFilter, matchMode: 'contains' } }"
                        >
                            <PColumn header="Name">
                                <template #body="slotProps">
                                    <div class="font-medium">
                                        {{ slotProps.data.user?.profile?.first_name ?? '' }} {{ slotProps.data.user?.profile?.last_name ?? '' }}
                                    </div>
                                    <div class="text-xs text-slate-400">{{ slotProps.data.admission_no }}</div>
                                </template>
                            </PColumn>
                            <PColumn header="Class">
                                <template #body="slotProps">
                                    {{ slotProps.data.current_enrollment?.school_class?.name ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn header="Section">
                                <template #body="slotProps">
                                    {{ slotProps.data.current_enrollment?.section?.name ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn header="Status">
                                <template #body="slotProps">
                                    <PTag :value="slotProps.data.status" :severity="slotProps.data.status === 'active' ? 'success' : 'warning'" />
                                </template>
                            </PColumn>
                            <PColumn header="">
                                <template #body="slotProps">
                                    <div class="flex gap-2">
                                        <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                        <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEdit(slotProps.data)" />
                                        <PButton icon="pi pi-ban" severity="danger" text rounded @click="deactivate(slotProps.data.id)" />
                                    </div>
                                </template>
                            </PColumn>
                        </PDataTable>
                    </template>
                </PCard>
            </div>
        </div>

        <PDialog
            v-model:visible="showForm"
            modal
            :header="editingId ? 'Edit Student' : 'New Student'"
            class="w-full max-w-3xl"
            @update:visible="(value) => { if (!value) cancelEdit(); }"
        >
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                <div>
                    <PInputText v-model="form.first_name" placeholder="First name" class="w-full" />
                    <FieldError :errors="form.errors" field="first_name" />
                </div>
                <div>
                    <PInputText v-model="form.last_name" placeholder="Last name" class="w-full" />
                    <FieldError :errors="form.errors" field="last_name" />
                </div>
                <div>
                    <PInputText v-model="form.other_name" placeholder="Other name" class="w-full" />
                    <FieldError :errors="form.errors" field="other_name" />
                </div>
                <div>
                    <PDropdown v-model="form.gender" :options="genderOptions" optionLabel="label" optionValue="value" placeholder="Gender" class="w-full" />
                    <FieldError :errors="form.errors" field="gender" />
                </div>
                <div>
                    <DateField v-model="form.date_of_birth" placeholder="Date of birth" />
                    <FieldError :errors="form.errors" field="date_of_birth" />
                </div>
                <div>
                    <PInputText v-model="form.phone" placeholder="Phone" class="w-full" />
                    <FieldError :errors="form.errors" field="phone" />
                </div>
                <div>
                    <PInputText v-model="form.address" placeholder="Address" class="w-full" />
                    <FieldError :errors="form.errors" field="address" />
                </div>
                <div>
                    <PInputText v-model="form.email" placeholder="Email" class="w-full" />
                    <FieldError :errors="form.errors" field="email" />
                </div>
                <div>
                    <PInputText v-model="form.username" placeholder="Username" class="w-full" />
                    <FieldError :errors="form.errors" field="username" />
                </div>
                <div>
                    <PInputText v-model="form.admission_no" placeholder="Admission number" class="w-full" />
                    <FieldError :errors="form.errors" field="admission_no" />
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
                        v-model="form.class_id"
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
                    <FieldError :errors="form.errors" field="class_id" />
                </div>
            </div>
            <template #footer>
                <div class="flex justify-end gap-2">
                    <PButton label="Cancel" severity="secondary" text @click="cancelEdit" />
                    <PButton :label="editingId ? 'Update Student' : 'Create Student'" icon="pi pi-check" severity="success" @click="submit" />
                </div>
            </template>
        </PDialog>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Student Record" :excludeKeys="['password', 'remember_token']" />
    </AppShell>
</template>
