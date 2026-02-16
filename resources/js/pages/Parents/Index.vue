<script setup>
import AppShell from '../../layouts/AppShell.vue';

defineProps({
    children: Array,
});
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
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
        </div>
    </AppShell>
</template>
