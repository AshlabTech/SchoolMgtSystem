<script setup>
import AppShell from '../../layouts/AppShell.vue';

defineProps({
    children: Array,
    marks: Object,
    feeRecords: Object,
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
                <template #title>My Children</template>
                <template #content>
                    <PDataTable :value="children" stripedRows responsiveLayout="scroll" class="text-sm">
                        <PColumn header="Student">
                            <template #body="slotProps">
                                {{ slotProps.data.student?.user?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn header="Class">
                            <template #body="slotProps">
                                {{ slotProps.data.student?.current_enrollment?.school_class?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn header="Section">
                            <template #body="slotProps">
                                {{ slotProps.data.student?.current_enrollment?.section?.name ?? '—' }}
                            </template>
                        </PColumn>
                    </PDataTable>
                </template>
            </PCard>

            <div v-for="child in children" :key="child.id" class="grid gap-6 lg:grid-cols-2">
                <PCard class="shadow-sm">
                    <template #title>Marks - {{ child.student?.user?.name ?? 'Student' }}</template>
                    <template #content>
                        <PDataTable :value="marks?.[child.student_id] || []" stripedRows responsiveLayout="scroll" class="text-sm">
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
                    <template #title>Payments - {{ child.student?.user?.name ?? 'Student' }}</template>
                    <template #content>
                        <PDataTable :value="feeRecords?.[child.student_id] || []" stripedRows responsiveLayout="scroll" class="text-sm">
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
        </div>
    </AppShell>
</template>
