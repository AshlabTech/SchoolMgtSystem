<script setup>
import AppShell from '../../layouts/AppShell.vue';

const props = defineProps({
    record: Object,
});

const studentName = `${props.record.student?.user?.profile?.first_name ?? ''} ${props.record.student?.user?.profile?.last_name ?? ''}`.trim();

const printPage = () => {
    window.print();
};

const downloadPDF = () => {
    window.location.href = `/payments/records/${props.record.id}/receipts/download`;
};

const formatMoney = (value) => Number(value ?? 0).toLocaleString();
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <PCard class="receipt-terminal-card">
                <template #title>
                    <div class="flex items-center justify-between gap-3">
                        <span class="terminal-title">PAYMENT RECEIPT LOG</span>
                        <div class="flex gap-2">
                            <PButton icon="pi pi-file-pdf" label="Download PDF" severity="success" @click="downloadPDF" />
                            <PButton icon="pi pi-print" label="Print" severity="contrast" @click="printPage" />
                        </div>
                    </div>
                </template>
                <template #content>
                    <div class="terminal-screen">
                        <div class="terminal-meta-grid">
                            <div><span class="terminal-label">STUDENT</span> {{ studentName || 'Unknown Student' }}</div>
                            <div><span class="terminal-label">INVOICE</span> {{ record.invoice_type?.name ?? 'N/A' }}</div>
                            <div><span class="terminal-label">CATEGORY</span> {{ record.invoice_type?.payment_category?.name ?? 'N/A' }}</div>
                            <div><span class="terminal-label">REFERENCE</span> {{ record.reference ?? 'N/A' }}</div>
                        </div>

                        <div class="terminal-divider"></div>

                        <div class="terminal-table">
                            <div class="terminal-row terminal-head">
                                <span>ENTRY</span>
                                <span>ISSUED</span>
                                <span class="text-right">PAID</span>
                                <span class="text-right">BALANCE</span>
                            </div>
                            <div v-for="(receipt, index) in record.receipts" :key="receipt.id" class="terminal-row">
                                <span>#{{ index + 1 }}</span>
                                <span>{{ receipt.issued_at }}</span>
                                <span class="text-right">NGN {{ formatMoney(receipt.amount_paid) }}</span>
                                <span class="text-right">NGN {{ formatMoney(receipt.balance) }}</span>
                            </div>
                            <div v-if="!record.receipts?.length" class="terminal-empty">
                                NO RECEIPTS FOUND
                            </div>
                        </div>
                    </div>
                </template>
            </PCard>
        </div>
    </AppShell>
</template>

<style scoped>
.receipt-terminal-card {
    background: #0d1117;
    border: 1px solid #1f2937;
}

.terminal-title {
    color: #9ef7b9;
    font-family: "Consolas", "Courier New", monospace;
    letter-spacing: 0.08em;
    font-weight: 700;
}

.terminal-screen {
    background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.12), transparent 45%), #050b09;
    border: 1px solid #184d3a;
    border-radius: 10px;
    color: #ccf8dc;
    font-family: "Consolas", "Courier New", monospace;
    padding: 1rem;
}

.terminal-meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 0.6rem;
    font-size: 0.85rem;
}

.terminal-label {
    color: #57d48c;
    margin-right: 0.5rem;
}

.terminal-divider {
    border-top: 1px dashed #22694f;
    margin: 0.9rem 0;
}

.terminal-table {
    display: grid;
    gap: 0.4rem;
}

.terminal-row {
    display: grid;
    grid-template-columns: 90px minmax(150px, 1fr) minmax(120px, 1fr) minmax(120px, 1fr);
    gap: 0.75rem;
    font-size: 0.82rem;
    align-items: center;
}

.terminal-head {
    color: #7cf0a9;
    font-weight: 700;
    border-bottom: 1px solid #1d5c44;
    padding-bottom: 0.35rem;
}

.terminal-empty {
    color: #8ec9a3;
    font-size: 0.85rem;
    text-align: center;
    padding: 0.7rem 0;
    border: 1px dashed #1d5c44;
}

@media (max-width: 700px) {
    .terminal-row {
        grid-template-columns: 60px 1fr 1fr 1fr;
        gap: 0.45rem;
        font-size: 0.72rem;
    }
}

@media print {
    .terminal-screen {
        border: 1px solid #000;
        color: #000;
        background: #fff;
    }

    .terminal-title,
    .terminal-label,
    .terminal-head {
        color: #000;
    }
}
</style>
