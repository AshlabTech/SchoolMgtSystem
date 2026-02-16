<script setup>
import AppShell from '../layouts/AppShell.vue';

const stats = [
    { label: 'Active Students', value: '1,482', delta: '+4.2%', tone: 'success' },
    { label: 'Outstanding Fees', value: 'NGN 12.4M', delta: '-1.6%', tone: 'warning' },
    { label: 'Avg. Attendance', value: '93.8%', delta: '+0.9%', tone: 'success' },
    { label: 'Staff On Duty', value: '86', delta: '+2', tone: 'info' },
];

const payments = [
    { student: 'Aisha Bello', class: 'SS2 Gold', amount: 'NGN 120,000', status: 'Paid', date: 'Feb 14, 2026' },
    { student: 'Daniel Okoro', class: 'JSS3 Blue', amount: 'NGN 85,000', status: 'Pending', date: 'Feb 13, 2026' },
    { student: 'Zainab Musa', class: 'SS1 Red', amount: 'NGN 95,000', status: 'Paid', date: 'Feb 12, 2026' },
    { student: 'Samuel Ade', class: 'JSS2 Green', amount: 'NGN 60,000', status: 'Overdue', date: 'Feb 10, 2026' },
];

const events = [
    { title: 'Mid-term exams start', date: 'Feb 28, 2026', owner: 'Academic Office' },
    { title: 'PTA leadership meeting', date: 'Mar 02, 2026', owner: 'Admin Office' },
    { title: 'Sports festival trials', date: 'Mar 05, 2026', owner: 'Sports Unit' },
];

const statusSeverity = (status) => {
    if (status === 'Paid') return 'success';
    if (status === 'Pending') return 'warning';
    return 'danger';
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                <PCard v-for="item in stats" :key="item.label" class="shadow-sm">
                    <template #content>
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ item.label }}</div>
                                <div class="mt-2 text-2xl font-semibold" :style="{ fontFamily: 'var(--font-display)' }">{{ item.value }}</div>
                            </div>
                            <PTag :value="item.delta" :severity="item.tone" />
                        </div>
                        <div class="mt-4 h-1 w-full rounded-full bg-slate-100">
                            <div class="h-1 rounded-full bg-teal-500" style="width: 72%"></div>
                        </div>
                    </template>
                </PCard>
            </section>

            <section class="grid grid-cols-1 gap-6 xl:grid-cols-[2fr_1fr]">
                <PCard class="shadow-sm">
                    <template #title>Recent Payments</template>
                    <template #content>
                        <PDataTable :value="payments" class="text-sm" stripedRows responsiveLayout="scroll">
                            <PColumn field="student" header="Student"></PColumn>
                            <PColumn field="class" header="Class"></PColumn>
                            <PColumn field="amount" header="Amount"></PColumn>
                            <PColumn field="status" header="Status">
                                <template #body="slotProps">
                                    <PTag :value="slotProps.data.status" :severity="statusSeverity(slotProps.data.status)" />
                                </template>
                            </PColumn>
                            <PColumn field="date" header="Date"></PColumn>
                        </PDataTable>
                    </template>
                </PCard>

                <div class="grid grid-cols-1 gap-6">
                    <PCard class="shadow-sm">
                        <template #title>Upcoming Events</template>
                        <template #content>
                            <div class="space-y-4">
                                <div v-for="event in events" :key="event.title" class="rounded-xl border border-slate-100 bg-white/80 p-4">
                                    <div class="text-sm font-semibold">{{ event.title }}</div>
                                    <div class="mt-1 text-xs text-slate-500">{{ event.date }}</div>
                                    <div class="mt-2 flex items-center gap-2 text-xs text-slate-400">
                                        <i class="pi pi-user"></i>
                                        {{ event.owner }}
                                    </div>
                                </div>
                            </div>
                        </template>
                    </PCard>

                    <PCard class="shadow-sm">
                        <template #title>Quick Actions</template>
                        <template #content>
                            <div class="grid grid-cols-1 gap-3">
                                <PButton label="Create Exam" icon="pi pi-file-edit" severity="secondary" outlined class="w-full" />
                                <PButton label="Record Payment" icon="pi pi-credit-card" severity="success" class="w-full" />
                                <PButton label="Assign Subjects" icon="pi pi-book" severity="info" class="w-full" />
                            </div>
                        </template>
                    </PCard>
                </div>
            </section>
        </div>
    </AppShell>
</template>
