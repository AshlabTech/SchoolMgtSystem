<script setup>
import AppShell from '../../layouts/AppShell.vue';

defineProps({
    user: Object,
    assignments: Array,
    timetableEntries: Array,
});
</script>

<template>
    <AppShell>
        <div class="grid gap-6">
            <PCard class="shadow-sm">
                <template #title>Staff Overview</template>
                <template #content>
                    <div class="grid gap-3 md:grid-cols-3">
                        <div>
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Name</div>
                            <div class="text-sm font-semibold">{{ user?.name }}</div>
                        </div>
                        <div>
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Designation</div>
                            <div class="text-sm font-semibold">{{ user?.staff_profile?.designation ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Roles</div>
                            <div class="text-sm font-semibold">{{ user?.roles?.map(r => r.name).join(', ') || '—' }}</div>
                        </div>
                    </div>
                </template>
            </PCard>

            <div class="grid gap-6 lg:grid-cols-2">
                <PCard class="shadow-sm">
                    <template #title>Subject Assignments</template>
                    <template #content>
                        <PDataTable :value="assignments" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn header="Subject">
                                <template #body="slotProps">
                                    {{ slotProps.data.subject?.name ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn header="Class">
                                <template #body="slotProps">
                                    {{ slotProps.data.school_class?.name ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn header="Section">
                                <template #body="slotProps">
                                    {{ slotProps.data.section?.name ?? '—' }}
                                </template>
                            </PColumn>
                        </PDataTable>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>My Timetable Entries</template>
                    <template #content>
                        <PDataTable :value="timetableEntries" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn header="Class">
                                <template #body="slotProps">
                                    {{ slotProps.data.timetable?.school_class?.name ?? '—' }}
                                </template>
                            </PColumn>
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
                        </PDataTable>
                    </template>
                </PCard>
            </div>
        </div>
    </AppShell>
</template>
