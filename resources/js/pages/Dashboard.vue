<script setup>
import AppShell from '../layouts/AppShell.vue';

const props = defineProps({
    stats: { type: Array, default: () => [] },
    payments: { type: Array, default: () => [] },
    events: { type: Array, default: () => [] },
});

const formatCurrency = (amount) =>
    new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: 'NGN',
        maximumFractionDigits: 0,
    }).format(Number(amount || 0));

const displayStatValue = (item) => (item?.is_currency ? formatCurrency(item.value) : item.value);

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
                <PCard v-for="item in props.stats" :key="item.label" class="shadow-sm">
                    <template #content>
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ item.label }}</div>
                                <div class="mt-2 text-2xl font-semibold" :style="{ fontFamily: 'var(--font-display)' }">
                                    {{ displayStatValue(item) }}
                                </div>
                            </div>
                        </div>
                    </template>
                </PCard>
            </section>

            <section class="grid grid-cols-1 gap-6 xl:grid-cols-[2fr_1fr]">
                <PCard class="shadow-sm">
                    <template #title>Recent Payments</template>
                    <template #content>
                        <PDataTable :value="props.payments" class="text-sm" stripedRows responsiveLayout="scroll">
                            <PColumn field="student" header="Student"></PColumn>
                            <PColumn field="class" header="Class"></PColumn>
                            <PColumn header="Amount">
                                <template #body="slotProps">
                                    {{ formatCurrency(slotProps.data.amount) }}
                                </template>
                            </PColumn>
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
                                <div v-for="event in props.events" :key="event.title" class="rounded-xl border border-slate-100 bg-white/80 p-4">
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
