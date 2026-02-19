<script setup>
import { computed } from 'vue';
import AppShell from '../layouts/AppShell.vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    currentSession: { type: String, default: null },
    currentTerm: { type: String, default: null },
    totalStudents: { type: Number, default: 0 },
    activeStudents: { type: Number, default: 0 },
    totalStaff: { type: Number, default: 0 },
    teachingStaff: { type: Number, default: 0 },
    studentsByClass: { type: Array, default: () => [] },
    currentTermRevenue: { type: Number, default: 0 },
    uniqueStudentsPaidCurrentTerm: { type: Number, default: 0 },
    todayRevenue: { type: Number, default: 0 },
    weekRevenue: { type: Number, default: 0 },
    monthRevenue: { type: Number, default: 0 },
    yearRevenue: { type: Number, default: 0 },
    newEnrollmentsThisMonth: { type: Number, default: 0 },
    newEnrollmentsThisTerm: { type: Number, default: 0 },
});

const formatCurrency = (amount) =>
    new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: 'NGN',
        maximumFractionDigits: 0,
    }).format(Number(amount || 0));

// Chart data for student distribution
const chartData = computed(() => ({
    labels: props.studentsByClass.map((item) => item.class),
    datasets: [
        {
            label: 'Students',
            data: props.studentsByClass.map((item) => item.count),
            backgroundColor: [
                'rgba(20, 184, 166, 0.8)',
                'rgba(251, 146, 60, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(168, 85, 247, 0.8)',
                'rgba(236, 72, 153, 0.8)',
                'rgba(34, 197, 94, 0.8)',
                'rgba(234, 179, 8, 0.8)',
                'rgba(239, 68, 68, 0.8)',
            ],
            borderColor: [
                'rgb(20, 184, 166)',
                'rgb(251, 146, 60)',
                'rgb(59, 130, 246)',
                'rgb(168, 85, 247)',
                'rgb(236, 72, 153)',
                'rgb(34, 197, 94)',
                'rgb(234, 179, 8)',
                'rgb(239, 68, 68)',
            ],
            borderWidth: 2,
        },
    ],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
    },
};
</script>

<template>
    <AppShell>
        <div class="space-y-6">
            <!-- Current Session and Term -->
            <div class=" bg-white p-6 text-slate-900 shadow-xs border border-slate-100">
                <div class="flex items-center justify-between gap-6">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-slate-500">Current Academic Session</div>
                        <div class="mt-1 text-3xl font-bold" :style="{ fontFamily: 'var(--font-display)' }">
                            {{ currentSession || 'Not Set' }}
                        </div>
                    </div>
                    <div class="w-48 text-right">
                        <div class="text-sm font-medium text-slate-500">Current Term</div>
                        <div class="mt-1 text-2xl font-semibold text-slate-700">{{ currentTerm || 'Not Set' }}</div>
                    </div>
                </div>
            </div>

            <!-- Student & Staff Statistics -->
            <section>
                <h2 class="mb-4 text-lg font-semibold text-slate-700" :style="{ fontFamily: 'var(--font-display)' }">
                    Student & Staff Overview
                </h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <PCard class="shadow-sm transition-shadow hover:shadow-md">
                        <template #content>
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Students</div>
                                    <div class="mt-2 text-3xl font-semibold text-teal-600" :style="{ fontFamily: 'var(--font-display)' }">
                                        {{ totalStudents }}
                                    </div>
                                </div>
                                <div class="bg-teal-100 p-3 rounded-none">
                                    <i class="pi pi-users text-xl text-teal-600"></i>
                                </div>
                            </div>
                        </template>
                    </PCard>

                    <PCard class="shadow-sm transition-shadow hover:shadow-md">
                        <template #content>
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Active Students</div>
                                    <div class="mt-2 text-3xl font-semibold text-green-600" :style="{ fontFamily: 'var(--font-display)' }">
                                        {{ activeStudents }}
                                    </div>
                                </div>
                                <div class="bg-green-100 p-3 rounded-none">
                                    <i class="pi pi-check-circle text-xl text-green-600"></i>
                                </div>
                            </div>
                        </template>
                    </PCard>

                    <PCard class="shadow-sm transition-shadow hover:shadow-md">
                        <template #content>
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Staff</div>
                                    <div class="mt-2 text-3xl font-semibold text-blue-600" :style="{ fontFamily: 'var(--font-display)' }">
                                        {{ totalStaff }}
                                    </div>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-none">
                                    <i class="pi pi-briefcase text-xl text-blue-600"></i>
                                </div>
                            </div>
                        </template>
                    </PCard>

                    <PCard class="shadow-sm transition-shadow hover:shadow-md">
                        <template #content>
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Teaching Staff</div>
                                    <div class="mt-2 text-3xl font-semibold text-purple-600" :style="{ fontFamily: 'var(--font-display)' }">
                                        {{ teachingStaff }}
                                    </div>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-none">
                                    <i class="pi pi-book text-xl text-purple-600"></i>
                                </div>
                            </div>
                        </template>
                    </PCard>
                </div>
            </section>

            <!-- Student Distribution by Class -->
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_1fr]">
                <PCard class="shadow-sm">
                    <template #title>
                        <div class="flex items-center gap-2">
                            <i class="pi pi-chart-bar text-teal-600"></i>
                            <span>Student Distribution by Class</span>
                        </div>
                    </template>
                    <template #content>
                        <div v-if="studentsByClass.length > 0" class="h-80">
                            <PChart type="bar" :data="chartData" :options="chartOptions" class="h-full" />
                        </div>
                        <div v-else class="flex h-80 items-center justify-center text-slate-400">
                            <div class="text-center">
                                <i class="pi pi-chart-bar mb-2 text-4xl"></i>
                                <div>No class distribution data available</div>
                            </div>
                        </div>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>
                        <div class="flex items-center gap-2">
                            <i class="pi pi-list text-teal-600"></i>
                            <span>Class Breakdown</span>
                        </div>
                    </template>
                    <template #content>
                        <div v-if="studentsByClass.length > 0" class="space-y-3">
                            <div
                                v-for="item in studentsByClass"
                                :key="item.class"
                                class="flex items-center justify-between border border-slate-100 bg-slate-50 p-4 transition-colors hover:bg-slate-100"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center bg-teal-100 text-sm font-semibold text-teal-700 rounded-none">
                                        {{ item.class.charAt(0) }}
                                    </div>
                                    <div class="font-medium text-slate-700">{{ item.class }}</div>
                                </div>
                                <div class="text-2xl font-bold text-teal-600" :style="{ fontFamily: 'var(--font-display)' }">
                                    {{ item.count }}
                                </div>
                            </div>
                        </div>
                        <div v-else class="py-8 text-center text-slate-400">
                            <i class="pi pi-inbox mb-2 text-4xl"></i>
                            <div>No students enrolled yet</div>
                        </div>
                    </template>
                </PCard>
            </section>

            <!-- Revenue Statistics -->
            <section>
                <h2 class="mb-4 text-lg font-semibold text-slate-700" :style="{ fontFamily: 'var(--font-display)' }">
                    Revenue Overview
                </h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <PCard class="shadow-sm transition-shadow hover:shadow-md">
                        <template #content>
                            <div>
                                <div class="flex items-center gap-2 text-xs uppercase tracking-[0.2em] text-slate-400">
                                    <i class="pi pi-calendar"></i>
                                    <span>Current Term Revenue</span>
                                </div>
                                <div class="mt-2 text-2xl font-bold text-teal-600" :style="{ fontFamily: 'var(--font-display)' }">
                                    {{ formatCurrency(currentTermRevenue) }}
                                </div>
                                <div class="mt-2 text-sm text-slate-500">
                                    {{ uniqueStudentsPaidCurrentTerm }} students paid
                                </div>
                            </div>
                        </template>
                    </PCard>

                    <PCard class="shadow-sm transition-shadow hover:shadow-md">
                        <template #content>
                            <div>
                                <div class="flex items-center gap-2 text-xs uppercase tracking-[0.2em] text-slate-400">
                                    <i class="pi pi-sun"></i>
                                    <span>Today's Revenue</span>
                                </div>
                                <div class="mt-2 text-2xl font-bold text-orange-600" :style="{ fontFamily: 'var(--font-display)' }">
                                    {{ formatCurrency(todayRevenue) }}
                                </div>
                            </div>
                        </template>
                    </PCard>

                    <PCard class="shadow-sm transition-shadow hover:shadow-md">
                        <template #content>
                            <div>
                                <div class="flex items-center gap-2 text-xs uppercase tracking-[0.2em] text-slate-400">
                                    <i class="pi pi-calendar-times"></i>
                                    <span>This Week</span>
                                </div>
                                <div class="mt-2 text-2xl font-bold text-blue-600" :style="{ fontFamily: 'var(--font-display)' }">
                                    {{ formatCurrency(weekRevenue) }}
                                </div>
                            </div>
                        </template>
                    </PCard>

                    <PCard class="shadow-sm transition-shadow hover:shadow-md">
                        <template #content>
                            <div>
                                <div class="flex items-center gap-2 text-xs uppercase tracking-[0.2em] text-slate-400">
                                    <i class="pi pi-calendar-plus"></i>
                                    <span>This Month</span>
                                </div>
                                <div class="mt-2 text-2xl font-bold text-purple-600" :style="{ fontFamily: 'var(--font-display)' }">
                                    {{ formatCurrency(monthRevenue) }}
                                </div>
                            </div>
                        </template>
                    </PCard>

                    <PCard class="shadow-sm transition-shadow hover:shadow-md">
                        <template #content>
                            <div>
                                <div class="flex items-center gap-2 text-xs uppercase tracking-[0.2em] text-slate-400">
                                    <i class="pi pi-chart-line"></i>
                                    <span>This Year</span>
                                </div>
                                <div class="mt-2 text-2xl font-bold text-green-600" :style="{ fontFamily: 'var(--font-display)' }">
                                    {{ formatCurrency(yearRevenue) }}
                                </div>
                            </div>
                        </template>
                    </PCard>
                </div>
            </section>

            <!-- New Enrollments -->
            <section>
                <h2 class="mb-4 text-lg font-semibold text-slate-700" :style="{ fontFamily: 'var(--font-display)' }">
                    New Enrollments
                </h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <PCard class="shadow-sm transition-shadow hover:shadow-md">
                        <template #content>
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">This Month</div>
                                    <div class="mt-2 text-3xl font-semibold text-teal-600" :style="{ fontFamily: 'var(--font-display)' }">
                                        {{ newEnrollmentsThisMonth }}
                                    </div>
                                    <div class="mt-1 text-sm text-slate-500">New students enrolled</div>
                                </div>
                                <div class="bg-teal-100 p-3 rounded-none">
                                    <i class="pi pi-user-plus text-xl text-teal-600"></i>
                                </div>
                            </div>
                        </template>
                    </PCard>

                    <PCard class="shadow-sm transition-shadow hover:shadow-md">
                        <template #content>
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">This Term</div>
                                    <div class="mt-2 text-3xl font-semibold text-orange-600" :style="{ fontFamily: 'var(--font-display)' }">
                                        {{ newEnrollmentsThisTerm }}
                                    </div>
                                    <div class="mt-1 text-sm text-slate-500">New students enrolled</div>
                                </div>
                                <div class="bg-orange-100 p-3 rounded-none">
                                    <i class="pi pi-users text-xl text-orange-600"></i>
                                </div>
                            </div>
                        </template>
                    </PCard>
                </div>
            </section>
        </div>
    </AppShell>
</template>
