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
    timetable: Object,
    timeslots: Array,
    entries: Array,
    subjects: Array,
});

const { can } = usePermissions();

const days = [
    { label: 'Monday', value: 1 },
    { label: 'Tuesday', value: 2 },
    { label: 'Wednesday', value: 3 },
    { label: 'Thursday', value: 4 },
    { label: 'Friday', value: 5 },
    { label: 'Saturday', value: 6 },
    { label: 'Sunday', value: 0 },
];

const timeslotForm = useForm({
    label: '',
    time_from: '',
    time_to: '',
    sort_order: 0,
});

const entryForm = useForm({
    timeslot_id: null,
    day_of_week: 1,
    exam_date: '',
    subject_id: null,
    room: '',
});

const showExam = computed(() => props.timetable?.type === 'exam');

const editingTimeslotId = ref(null);
const editingEntryId = ref(null);
const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const startEditTimeslot = (record) => {
    editingTimeslotId.value = record.id;
    timeslotForm.clearErrors();
    timeslotForm.label = record.label ?? '';
    timeslotForm.time_from = record.time_from ?? '';
    timeslotForm.time_to = record.time_to ?? '';
    timeslotForm.sort_order = record.sort_order ?? 0;
};

const cancelEditTimeslot = () => {
    editingTimeslotId.value = null;
    timeslotForm.reset();
    timeslotForm.clearErrors();
};

const startEditEntry = (record) => {
    editingEntryId.value = record.id;
    entryForm.clearErrors();
    entryForm.timeslot_id = record.timeslot_id ?? record.timeslot?.id ?? null;
    entryForm.subject_id = record.subject_id ?? record.subject?.id ?? null;
    entryForm.room = record.room ?? '';
    entryForm.day_of_week = record.day_of_week ?? 1;
    entryForm.exam_date = record.exam_date ?? '';
};

const cancelEditEntry = () => {
    editingEntryId.value = null;
    entryForm.reset();
    entryForm.clearErrors();
};

const submitTimeslot = () => {
    if (editingTimeslotId.value) {
        timeslotForm.put(`/timetables/timeslots/${editingTimeslotId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingTimeslotId.value = null;
                timeslotForm.reset();
            },
        });
        return;
    }

    timeslotForm.post(`/timetables/${props.timetable.id}/timeslots`, {
        preserveScroll: true,
        onSuccess: () => {
            timeslotForm.reset();
        },
    });
};

const submitEntry = () => {
    entryForm.transform((data) => {
        const payload = { ...data };
        if (showExam.value) {
            payload.day_of_week = null;
        } else {
            payload.exam_date = null;
        }
        return payload;
    });

    if (editingEntryId.value) {
        entryForm.put(`/timetables/entries/${editingEntryId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingEntryId.value = null;
                entryForm.reset();
            },
        });
        return;
    }

    entryForm.post(`/timetables/${props.timetable.id}/entries`, {
        preserveScroll: true,
        onSuccess: () => {
            entryForm.reset();
        },
    });
};

const deleteTimeslot = (id) => {
    if (!confirm('Delete this timeslot?')) return;
    router.delete(`/timetables/timeslots/${id}`, { preserveScroll: true });
};

const deleteEntry = (id) => {
    if (!confirm('Delete this entry?')) return;
    router.delete(`/timetables/entries/${id}`, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-slate-500">Timetable</div>
                    <div class="text-2xl font-semibold">{{ timetable.name }}</div>
                </div>
                <a href="/timetables" class="text-sm text-teal-700 hover:underline">Back to list</a>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[320px_1fr]">
                <PCard class="shadow-sm">
                    <template #title>Add Time Slot</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <PInputText v-model="timeslotForm.label" placeholder="Label (e.g. 08:00 - 09:00)" class="w-full" />
                                <FieldError :errors="timeslotForm.errors" field="label" />
                            </div>
                            <div>
                                <PInputText v-model="timeslotForm.time_from" type="time" class="w-full" />
                                <FieldError :errors="timeslotForm.errors" field="time_from" />
                            </div>
                            <div>
                                <PInputText v-model="timeslotForm.time_to" type="time" class="w-full" />
                                <FieldError :errors="timeslotForm.errors" field="time_to" />
                            </div>
                            <div>
                                <PInputNumber v-model="timeslotForm.sort_order" class="w-full" placeholder="Sort order" />
                                <FieldError :errors="timeslotForm.errors" field="sort_order" />
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <PButton :label="editingTimeslotId ? 'Update Slot' : 'Add Slot'" icon="pi pi-plus" severity="success" @click="submitTimeslot" />
                                <PButton v-if="editingTimeslotId" label="Cancel" severity="secondary" text @click="cancelEditTimeslot" />
                            </div>
                        </div>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Assign Subjects</template>
                    <template #content>
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <div>
                                <ModelSelect
                                    v-model="entryForm.timeslot_id"
                                    :options="timeslots"
                                    optionLabel="label"
                                    optionValue="id"
                                    placeholder="Select slot"
                                    :canCreate="can('manage.timetables')"
                                    createTitle="Add Time Slot"
                                    :createEndpoint="`/timetables/${timetable.id}/timeslots`"
                                    :createFields="[
                                        { name: 'label', label: 'Label', type: 'text', placeholder: '08:00 - 09:00' },
                                        { name: 'time_from', label: 'Start time', type: 'text', placeholder: '08:00' },
                                        { name: 'time_to', label: 'End time', type: 'text', placeholder: '09:00' },
                                        { name: 'sort_order', label: 'Sort order', type: 'number' },
                                    ]"
                                />
                                <FieldError :errors="entryForm.errors" field="timeslot_id" />
                            </div>
                            <div v-if="!showExam">
                                <PDropdown v-model="entryForm.day_of_week" :options="days" optionLabel="label" optionValue="value" class="w-full" />
                                <FieldError :errors="entryForm.errors" field="day_of_week" />
                            </div>
                            <div v-if="showExam">
                                <DateField v-model="entryForm.exam_date" placeholder="Exam date" />
                                <FieldError :errors="entryForm.errors" field="exam_date" />
                            </div>
                            <div>
                                <ModelSelect v-model="entryForm.subject_id" :options="subjects" optionLabel="name" optionValue="id" placeholder="Subject" />
                                <FieldError :errors="entryForm.errors" field="subject_id" />
                            </div>
                            <div>
                                <PInputText v-model="entryForm.room" placeholder="Room (optional)" class="w-full" />
                                <FieldError :errors="entryForm.errors" field="room" />
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <PButton :label="editingEntryId ? 'Update Entry' : 'Save Entry'" icon="pi pi-check" severity="info" @click="submitEntry" />
                            <PButton v-if="editingEntryId" label="Cancel" severity="secondary" text @click="cancelEditEntry" />
                        </div>
                    </template>
                </PCard>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <PCard class="shadow-sm">
                    <template #title>Time Slots</template>
                    <template #content>
                        <PDataTable :value="timeslots" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn field="label" header="Label" />
                            <PColumn field="time_from" header="From" />
                            <PColumn field="time_to" header="To" />
                            <PColumn header="">
                                <template #body="slotProps">
                                    <div class="flex gap-2">
                                        <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                        <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEditTimeslot(slotProps.data)" />
                                        <PButton icon="pi pi-trash" severity="danger" text rounded @click="deleteTimeslot(slotProps.data.id)" />
                                    </div>
                                </template>
                            </PColumn>
                        </PDataTable>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Entries</template>
                    <template #content>
                        <PDataTable :value="entries" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn header="Slot">
                                <template #body="slotProps">
                                    {{ slotProps.data.timeslot?.label ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn header="Day / Date">
                                <template #body="slotProps">
                                    <span v-if="slotProps.data.exam_date">{{ slotProps.data.exam_date }}</span>
                                    <span v-else>
                                        {{ days.find(day => day.value === slotProps.data.day_of_week)?.label ?? '—' }}
                                    </span>
                                </template>
                            </PColumn>
                            <PColumn header="Subject">
                                <template #body="slotProps">
                                    {{ slotProps.data.subject?.name ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn field="room" header="Room" />
                            <PColumn header="">
                                <template #body="slotProps">
                                    <div class="flex gap-2">
                                        <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                        <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEditEntry(slotProps.data)" />
                                        <PButton icon="pi pi-trash" severity="danger" text rounded @click="deleteEntry(slotProps.data.id)" />
                                    </div>
                                </template>
                            </PColumn>
                        </PDataTable>
                    </template>
                </PCard>
            </div>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Timetable Record" />
    </AppShell>
</template>
