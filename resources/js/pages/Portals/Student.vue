<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';

const props = defineProps({
    student: Object,
    marks: Array,
    feeRecords: Array,
    loans: Array,
    timetables: Array,
    adminView: Boolean,
    studentOptions: Array,
    selectedStudentId: Number,
});

const showPicker = ref(false);
const selectedId = ref(props.selectedStudentId ?? null);

watch(
    () => props.selectedStudentId,
    (value) => {
        selectedId.value = value ?? null;
    }
);

const applySelection = () => {
    if (!selectedId.value) return;
    router.get('/portal/student', { student_id: selectedId.value }, { replace: true, preserveScroll: true });
    showPicker.value = false;
};

const clearSelection = () => {
    selectedId.value = null;
    router.get('/portal/student', {}, { replace: true, preserveScroll: true });
    showPicker.value = false;
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
            <PCard v-if="adminView" class="shadow-sm">
                <template #title>Portal Preview</template>
                <template #content>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="text-sm text-slate-600">
                            Viewing:
                            <span class="font-semibold text-slate-900">{{ student?.user?.name ?? 'Not selected' }}</span>
                        </div>
                        <PButton label="Select Student" icon="pi pi-user" severity="secondary" @click="showPicker = true" />
                        <PButton label="Clear" icon="pi pi-times" text severity="secondary" @click="clearSelection" />
                    </div>
                </template>
            </PCard>

            <div v-if="!student" class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
                Select a student to preview the portal.
            </div>

            <template v-else>
            <PCard class="shadow-sm">
                <template #title>My Profile</template>
                <template #content>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                        <div>
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Name</div>
                            <div class="text-sm font-semibold">
                                {{ student?.user?.profile?.first_name }} {{ student?.user?.profile?.last_name }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Admission</div>
                            <div class="text-sm font-semibold">{{ student?.admission_no }}</div>
                        </div>
                        <div>
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Class</div>
                            <div class="text-sm font-semibold">
                                {{ student?.current_enrollment?.school_class?.name ?? '—' }}
                            </div>
                        </div>
                    </div>
                </template>
            </PCard>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <PCard class="shadow-sm">
                    <template #title>Recent Marks</template>
                    <template #content>
                        <PDataTable :value="marks" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn header="Exam">
                                <template #body="slotProps">
                                    {{ slotProps.data.exam?.name ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn header="Subject">
                                <template #body="slotProps">
                                    {{ slotProps.data.subject?.name ?? '—' }}
                                </template>
                            </PColumn>
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
                        </PDataTable>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Payments</template>
                    <template #content>
                        <PDataTable :value="feeRecords" stripedRows responsiveLayout="scroll" class="text-sm">
                                <PColumn header="Fee">
                                    <template #body="slotProps">
                                        {{ slotProps.data.invoice_type?.name ?? '—' }}
                                    </template>
                                </PColumn>
                            <PColumn field="amount_paid" header="Paid" />
                            <PColumn field="balance" header="Balance" />
                            <PColumn header="Status">
                                <template #body="slotProps">
                                    <PTag :value="slotProps.data.is_paid ? 'Paid' : 'Due'" :severity="slotProps.data.is_paid ? 'success' : 'warning'" />
                                </template>
                            </PColumn>
                        </PDataTable>
                    </template>
                </PCard>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <PCard class="shadow-sm">
                    <template #title>Library Loans</template>
                    <template #content>
                        <PDataTable :value="loans" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn header="Book">
                                <template #body="slotProps">
                                    {{ slotProps.data.book?.title ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn field="issued_at" header="Issued" />
                            <PColumn field="due_at" header="Due" />
                            <PColumn header="Status">
                                <template #body="slotProps">
                                    <PTag :value="slotProps.data.status" :severity="slotProps.data.status === 'returned' ? 'success' : 'warning'" />
                                </template>
                            </PColumn>
                        </PDataTable>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Timetable</template>
                    <template #content>
                        <div v-if="!timetables.length" class="text-sm text-slate-500">No timetable set.</div>
                        <div v-for="table in timetables" :key="table.id" class="mb-4">
                            <div class="text-sm font-semibold">{{ table.name }}</div>
                            <PDataTable :value="table.entries" stripedRows responsiveLayout="scroll" class="mt-2 text-sm">
                                <PColumn header="Slot">
                                    <template #body="slotProps">
                                        {{ slotProps.data.timeslot?.label ?? '—' }}
                                    </template>
                                </PColumn>
                                <PColumn header="Subject">
                                    <template #body="slotProps">
                                        {{ slotProps.data.subject?.name ?? '—' }}
                                    </template>
                                </PColumn>
                                <PColumn field="day_of_week" header="Day" />
                                <PColumn field="exam_date" header="Date" />
                            </PDataTable>
                        </div>
                    </template>
                </PCard>
            </div>
            </template>
        </div>

        <PDialog v-model:visible="showPicker" modal header="Select Student" class="w-full max-w-md">
            <div class="space-y-4">
                <PDropdown
                    v-model="selectedId"
                    :options="studentOptions || []"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Choose a student"
                    class="w-full"
                    filter
                />
                <div class="flex justify-end gap-2">
                    <PButton label="Cancel" text severity="secondary" @click="showPicker = false" />
                    <PButton label="Load Portal" icon="pi pi-check" severity="success" @click="applySelection" />
                </div>
            </div>
        </PDialog>
    </AppShell>
</template>
