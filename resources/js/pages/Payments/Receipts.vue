<script setup>
import AppShell from '../../layouts/AppShell.vue';

const props = defineProps({
    record: Object,
});

const studentName = `${props.record.student?.user?.profile?.first_name ?? ''} ${props.record.student?.user?.profile?.last_name ?? ''}`.trim();
</script>

<template>
    <AppShell>
        <div class="grid gap-6">
            <PCard class="shadow-sm">
                <template #title>Receipts</template>
                <template #content>
                    <div class="mb-4 text-sm text-slate-500">
                        {{ studentName }} — {{ record.fee_definition?.name ?? '' }}
                    </div>
                    <PDataTable :value="record.receipts" stripedRows responsiveLayout="scroll" class="text-sm">
                        <PColumn field="amount_paid" header="Amount" />
                        <PColumn field="balance" header="Balance" />
                        <PColumn field="issued_at" header="Issued" />
                    </PDataTable>
                </template>
            </PCard>
        </div>
    </AppShell>
</template>
