<script setup>
import { computed, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import DateField from '../../components/DateField.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';

const props = defineProps({
    dorms: Array,
    assignments: Array,
    students: Array,
});

const { can } = usePermissions();

const studentOptions = computed(() =>
    (props.students || []).map((student) => ({
        label: student.user?.name ?? 'Unnamed',
        value: student.id,
    }))
);

const dormForm = useForm({
    name: '',
    description: '',
    capacity: null,
});

const editingDormId = ref(null);
const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const startEditDorm = (record) => {
    editingDormId.value = record.id;
    dormForm.clearErrors();
    dormForm.name = record.name ?? '';
    dormForm.description = record.description ?? '';
    dormForm.capacity = record.capacity ?? null;
};

const cancelEditDorm = () => {
    editingDormId.value = null;
    dormForm.reset();
    dormForm.clearErrors();
};

const assignmentForm = useForm({
    dormitory_id: null,
    student_id: null,
    room_no: '',
    assigned_at: '',
});

const submitDorm = () => {
    if (editingDormId.value) {
        dormForm.put(`/dorms/${editingDormId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingDormId.value = null;
                dormForm.reset();
            },
        });
        return;
    }

    dormForm.post('/dorms', {
        preserveScroll: true,
        onSuccess: () => {
            dormForm.reset();
        },
    });
};

const submitAssignment = () => {
    assignmentForm.post('/dorms/assignments', {
        preserveScroll: true,
        onSuccess: () => {
            assignmentForm.reset();
        },
    });
};

const deleteDorm = (id) => {
    if (!confirm('Delete this dormitory?')) return;
    router.delete(`/dorms/${id}`, { preserveScroll: true });
};

const releaseAssignment = (id) => {
    router.post(`/dorms/assignments/${id}/release`, {}, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid gap-6">
            <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
                <PCard class="shadow-sm">
                    <template #title>Add Dormitory</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <PInputText v-model="dormForm.name" placeholder="Name" class="w-full" />
                                <FieldError :errors="dormForm.errors" field="name" />
                            </div>
                            <div>
                                <PInputNumber v-model="dormForm.capacity" placeholder="Capacity" class="w-full" />
                                <FieldError :errors="dormForm.errors" field="capacity" />
                            </div>
                            <div>
                                <textarea v-model="dormForm.description" rows="3" class="w-full rounded-md border border-slate-200 p-2 text-sm" placeholder="Description"></textarea>
                                <FieldError :errors="dormForm.errors" field="description" />
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <PButton :label="editingDormId ? 'Update Dorm' : 'Save Dorm'" icon="pi pi-plus" severity="success" @click="submitDorm" />
                                <PButton v-if="editingDormId" label="Cancel" severity="secondary" text @click="cancelEditDorm" />
                            </div>
                        </div>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Dormitories</template>
                    <template #content>
                        <PDataTable :value="dorms" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn field="name" header="Name" />
                            <PColumn header="Assigned">
                                <template #body="slotProps">
                                    {{ slotProps.data.assignments_count ?? 0 }}
                                </template>
                            </PColumn>
                            <PColumn header="">
                                <template #body="slotProps">
                                    <div class="flex gap-2">
                                        <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                        <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEditDorm(slotProps.data)" />
                                        <PButton icon="pi pi-trash" severity="danger" text rounded @click="deleteDorm(slotProps.data.id)" />
                                    </div>
                                </template>
                            </PColumn>
                        </PDataTable>
                    </template>
                </PCard>
            </div>

            <PCard class="shadow-sm">
                <template #title>Assign Students</template>
                <template #content>
                    <div class="grid gap-3 md:grid-cols-2">
                        <div>
                            <ModelSelect
                                v-model="assignmentForm.dormitory_id"
                                :options="dorms"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Dormitory"
                                :canCreate="can('manage.dorms')"
                                createTitle="Add Dormitory"
                                createEndpoint="/dorms"
                                :createFields="[
                                    { name: 'name', label: 'Name', type: 'text' },
                                    { name: 'capacity', label: 'Capacity', type: 'number' },
                                    { name: 'description', label: 'Description', type: 'textarea' },
                                ]"
                            />
                            <FieldError :errors="assignmentForm.errors" field="dormitory_id" />
                        </div>
                        <div>
                            <ModelSelect
                                v-model="assignmentForm.student_id"
                                :options="studentOptions"
                                optionLabel="label"
                                optionValue="value"
                                placeholder="Student"
                            />
                            <FieldError :errors="assignmentForm.errors" field="student_id" />
                        </div>
                        <div>
                            <PInputText v-model="assignmentForm.room_no" placeholder="Room" class="w-full" />
                            <FieldError :errors="assignmentForm.errors" field="room_no" />
                        </div>
                        <div>
                            <DateField v-model="assignmentForm.assigned_at" placeholder="Assigned at" />
                            <FieldError :errors="assignmentForm.errors" field="assigned_at" />
                        </div>
                    </div>
                    <PButton label="Assign" icon="pi pi-send" severity="info" class="mt-4" @click="submitAssignment" />
                </template>
            </PCard>

            <PCard class="shadow-sm">
                <template #title>Current Assignments</template>
                <template #content>
                    <PDataTable :value="assignments" stripedRows responsiveLayout="scroll" class="text-sm">
                        <PColumn header="Student">
                            <template #body="slotProps">
                                {{ slotProps.data.student?.user?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn header="Dorm">
                            <template #body="slotProps">
                                {{ slotProps.data.dormitory?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn field="room_no" header="Room" />
                        <PColumn field="assigned_at" header="Assigned" />
                            <PColumn header="">
                                <template #body="slotProps">
                                    <div class="flex gap-2">
                                        <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                        <PButton icon="pi pi-times" severity="warning" text rounded @click="releaseAssignment(slotProps.data.id)" />
                                    </div>
                                </template>
                            </PColumn>
                        </PDataTable>
                    </template>
                </PCard>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Dormitory Record" />
    </AppShell>
</template>
