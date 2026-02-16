<script setup>
import AppShell from '../../layouts/AppShell.vue';

defineProps({
    student: Object,
    marks: Array,
    feeRecords: Array,
    loans: Array,
    timetables: Array,
});

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
        <div class="grid gap-6">
            <PCard class="shadow-sm">
                <template #title>My Profile</template>
                <template #content>
                    <div class="grid gap-3 md:grid-cols-3">
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

            <div class="grid gap-6 lg:grid-cols-2">
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
                                    {{ slotProps.data.fee_definition?.name ?? '—' }}
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

            <div class="grid gap-6 lg:grid-cols-2">
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
        </div>
    </AppShell>
</template>
