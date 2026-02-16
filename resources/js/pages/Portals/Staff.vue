<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';

const props = defineProps({
    user: Object,
    assignments: Array,
    timetableEntries: Array,
    adminView: Boolean,
    staffOptions: Array,
    selectedStaffId: Number,
});

const showPicker = ref(false);
const selectedId = ref(props.selectedStaffId ?? null);

watch(
    () => props.selectedStaffId,
    (value) => {
        selectedId.value = value ?? null;
    }
);

const applySelection = () => {
    if (!selectedId.value) return;
    router.get('/portal/staff', { staff_id: selectedId.value }, { replace: true, preserveScroll: true });
    showPicker.value = false;
};

const clearSelection = () => {
    selectedId.value = null;
    router.get('/portal/staff', {}, { replace: true, preserveScroll: true });
    showPicker.value = false;
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
                            <span class="font-semibold text-slate-900">{{ user?.name ?? 'Not selected' }}</span>
                        </div>
                        <PButton label="Select Staff" icon="pi pi-briefcase" severity="secondary" @click="showPicker = true" />
                        <PButton label="Clear" icon="pi pi-times" text severity="secondary" @click="clearSelection" />
                    </div>
                </template>
            </PCard>

            <div v-if="!user" class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
                Select a staff member to preview their portal.
            </div>

            <template v-else>
            <PCard class="shadow-sm">
                <template #title>Staff Overview</template>
                <template #content>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
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

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
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
            </template>
        </div>

        <PDialog v-model:visible="showPicker" modal header="Select Staff" class="w-full max-w-md">
            <div class="space-y-4">
                <PDropdown
                    v-model="selectedId"
                    :options="staffOptions || []"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Choose a staff member"
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
