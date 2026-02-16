<script setup>
import { computed, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';

const props = defineProps({
    children: Array,
    marks: Object,
    feeRecords: Object,
    adminView: Boolean,
    parentOptions: Array,
    selectedParentId: Number,
});

const showPicker = ref(false);
const selectedId = ref(props.selectedParentId ?? null);

const selectedLabel = computed(() => {
    return (props.parentOptions || []).find((option) => option.value === selectedId.value)?.label ?? 'Not selected';
});

watch(
    () => props.selectedParentId,
    (value) => {
        selectedId.value = value ?? null;
    }
);

const applySelection = () => {
    if (!selectedId.value) return;
    router.get('/portal/parent', { guardian_id: selectedId.value }, { replace: true, preserveScroll: true });
    showPicker.value = false;
};

const clearSelection = () => {
    selectedId.value = null;
    router.get('/portal/parent', {}, { replace: true, preserveScroll: true });
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
                            <span class="font-semibold text-slate-900">{{ selectedLabel }}</span>
                        </div>
                        <PButton label="Select Parent" icon="pi pi-users" severity="secondary" @click="showPicker = true" />
                        <PButton label="Clear" icon="pi pi-times" text severity="secondary" @click="clearSelection" />
                    </div>
                </template>
            </PCard>

            <div v-if="!children?.length" class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
                <span v-if="adminView">Select a parent to preview their portal.</span>
                <span v-else>No children assigned to this account.</span>
            </div>

            <template v-else>
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

            <div v-for="child in children" :key="child.id" class="grid grid-cols-1 gap-6 lg:grid-cols-2">
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
            </template>
        </div>

        <PDialog v-model:visible="showPicker" modal header="Select Parent" class="w-full max-w-md">
            <div class="space-y-4">
                <PDropdown
                    v-model="selectedId"
                    :options="parentOptions || []"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Choose a parent"
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
