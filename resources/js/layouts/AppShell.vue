<script setup>
import { router } from '@inertiajs/vue3';

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <div class="min-h-screen text-slate-900">
        <div class="grid min-h-screen grid-cols-[260px_1fr] gap-0">
            <aside class="relative overflow-hidden border-r border-slate-200 bg-white/70 backdrop-blur">
                <div class="flex h-16 items-center gap-3 px-6">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-600 text-white">
                        <i class="pi pi-sparkles text-base"></i>
                    </div>
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">SchoolApp</div>
                        <div class="text-xs text-slate-400">Academic Suite</div>
                    </div>
                </div>
                <nav class="px-4 py-6">
                    <div class="mb-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Core</div>
                    <div class="flex flex-col gap-2">
                        <a class="flex items-center gap-3 rounded-xl bg-teal-50 px-4 py-2 text-sm font-medium text-teal-800" href="/dashboard">
                            <i class="pi pi-chart-line"></i>
                            Dashboard
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/students">
                            <i class="pi pi-users"></i>
                            Students
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/my-children">
                            <i class="pi pi-heart"></i>
                            My Children
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/promotions">
                            <i class="pi pi-arrow-up-right"></i>
                            Promotions
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/marks">
                            <i class="pi pi-pencil"></i>
                            Marks
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/results">
                            <i class="pi pi-chart-bar"></i>
                            Results
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/staff">
                            <i class="pi pi-briefcase"></i>
                            Staff
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/academics">
                            <i class="pi pi-book"></i>
                            Academics
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/subjects">
                            <i class="pi pi-bookmark"></i>
                            Subjects
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/exams">
                            <i class="pi pi-file-edit"></i>
                            Exams
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/grades">
                            <i class="pi pi-star"></i>
                            Grades
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/payments">
                            <i class="pi pi-credit-card"></i>
                            Payments
                        </a>
                    </div>

                    <div class="mt-8 mb-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Operations</div>
                    <div class="flex flex-col gap-2">
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/timetables">
                            <i class="pi pi-calendar"></i>
                            Timetable
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/pins">
                            <i class="pi pi-key"></i>
                            Pins
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/library">
                            <i class="pi pi-book"></i>
                            Library
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/dorms">
                            <i class="pi pi-home"></i>
                            Dorms
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/settings">
                            <i class="pi pi-cog"></i>
                            Settings
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/my-account">
                            <i class="pi pi-user"></i>
                            My Account
                        </a>
                    </div>

                    <div class="mt-8 mb-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Portals</div>
                    <div class="flex flex-col gap-2">
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/portal/student">
                            <i class="pi pi-id-card"></i>
                            Student Portal
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/portal/parent">
                            <i class="pi pi-users"></i>
                            Parent Portal
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/portal/staff">
                            <i class="pi pi-briefcase"></i>
                            Staff Portal
                        </a>
                    </div>
                </nav>
                <div class="absolute bottom-6 left-6 right-6 rounded-2xl bg-slate-900 p-4 text-white">
                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Status</div>
                    <div class="mt-2 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold">Term 2</div>
                            <div class="text-xs text-slate-400">2025/2026</div>
                        </div>
                        <PTag value="Live" severity="success" />
                    </div>
                </div>
            </aside>

            <div class="flex flex-col">
                <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white/70 px-6 backdrop-blur">
                    <div class="flex items-center gap-4">
                        <div class="text-lg font-semibold" :style="{ fontFamily: 'var(--font-display)' }">Overview</div>
                        <div class="hidden items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-500 md:flex">
                            <span class="h-2 w-2 rounded-full bg-teal-500"></span>
                            Next exam cycle in 12 days
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="hidden text-xs text-slate-500 md:inline">Search</span>
                        <PInputText placeholder="Quick find" class="w-44" />
                        <PButton icon="pi pi-bell" severity="secondary" text rounded />
                        <PButton icon="pi pi-plus" label="New" severity="success" rounded />
                        <PButton icon="pi pi-sign-out" severity="secondary" text rounded @click="logout" />
                        <PAvatar label="SA" shape="circle" class="bg-teal-600 text-white" />
                    </div>
                </header>

                <main class="flex-1 px-6 py-8">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
