<script setup>
import AppShell from '../../layouts/AppShell.vue';

const props = defineProps({
    record: Object,
});

const studentName = `${props.record.student?.user?.profile?.first_name ?? ''} ${props.record.student?.user?.profile?.last_name ?? ''}`.trim();

const printPage = () => {
    window.print();
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <PCard class="shadow-sm">
                <template #title>
                    <div class="flex items-center justify-between">
                        <span>Receipts</span>
                        <PButton icon="pi pi-print" label="Print" severity="secondary" @click="printPage" />
                    </div>
                </template>
                <template #content>
                    <div class="mb-4 text-sm text-slate-500">
                        {{ studentName }} — {{ record.invoice_type?.name ?? '' }}
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
