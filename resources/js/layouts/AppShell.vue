<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const logout = () => {
    router.post('/logout');
};

const isSidebarOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const closeSidebar = () => {
    isSidebarOpen.value = false;
};

const onNavClick = (event) => {
    if (window.innerWidth < 1024 && event.target.closest('a')) {
        closeSidebar();
    }
};

const page = usePage();
const currentTerm = computed(() => page.props.schoolContext?.term?.name ?? 'Current Term');
const currentYear = computed(() => page.props.schoolContext?.academicYear?.name ?? 'Academic Year');
</script>

<template>
    <div class="h-screen overflow-hidden text-slate-900">
        <PToast position="top-right" />
        <div class="grid h-full grid-cols-1 gap-0 lg:grid-cols-[260px_1fr]">
            <div
                class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm transition-opacity lg:hidden"
                :class="isSidebarOpen ? 'opacity-100' : 'pointer-events-none opacity-0'"
                @click="closeSidebar"
            ></div>
            <aside
                class="fixed inset-y-0 left-0 z-40 h-full w-64 overflow-y-auto border-r border-slate-200/80 bg-white/95 backdrop-blur-xl shadow-xl transition-transform lg:static lg:translate-x-0 lg:bg-white/80 lg:shadow-none"
                :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
                <div class="flex h-18 items-center gap-3 px-6 py-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-teal-600 text-white shadow-lg shadow-teal-500/30">
                        <i class="pi pi-sparkles text-lg"></i>
                    </div>
                    <div>
                        <div class="font-display text-base font-bold tracking-tight text-slate-800">SchoolApp</div>
                        <div class="text-xs font-medium text-slate-500">Academic Suite</div>
                    </div>
                </div>
                <div class="mx-4 mb-4 rounded-xl bg-gradient-to-br from-teal-50 to-orange-50 p-4 shadow-sm">
                    <div class="text-[10px] font-semibold uppercase tracking-[0.15em] text-teal-700">Current Term</div>
                    <div class="mt-0.5 text-sm font-semibold text-slate-800">{{ currentTerm }}</div>
                    <div class="mt-3 text-[10px] font-semibold uppercase tracking-[0.15em] text-orange-700">Academic Year</div>
                    <div class="mt-0.5 text-sm font-medium text-slate-700">{{ currentYear }}</div>
                </div>
                <nav class="px-4 py-2" @click="onNavClick">
                    <div class="mb-2 px-2 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400">Core</div>
                    <div class="flex flex-col gap-1">
                        <a class="group flex items-center gap-3 rounded-xl bg-gradient-to-r from-teal-500 to-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-teal-500/20 transition-all hover:shadow-lg hover:shadow-teal-500/30" href="/dashboard">
                            <i class="pi pi-chart-line text-base"></i>
                            Dashboard
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/students">
                            <i class="pi pi-users text-base transition-colors group-hover:text-teal-600"></i>
                            Students
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/my-children">
                            <i class="pi pi-heart text-base transition-colors group-hover:text-teal-600"></i>
                            My Children
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/promotions">
                            <i class="pi pi-arrow-up-right text-base transition-colors group-hover:text-teal-600"></i>
                            Promotions
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/marks">
                            <i class="pi pi-pencil text-base transition-colors group-hover:text-teal-600"></i>
                            Marks
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/results">
                            <i class="pi pi-chart-bar text-base transition-colors group-hover:text-teal-600"></i>
                            Results
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/staff">
                            <i class="pi pi-briefcase text-base transition-colors group-hover:text-teal-600"></i>
                            Staff
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/academics">
                            <i class="pi pi-book text-base transition-colors group-hover:text-teal-600"></i>
                            Academics
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/subjects">
                            <i class="pi pi-bookmark text-base transition-colors group-hover:text-teal-600"></i>
                            Subjects
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/exams">
                            <i class="pi pi-file-edit text-base transition-colors group-hover:text-teal-600"></i>
                            Exams
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/grades">
                            <i class="pi pi-star text-base transition-colors group-hover:text-teal-600"></i>
                            Grades
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/skills">
                            <i class="pi pi-list-check text-base transition-colors group-hover:text-teal-600"></i>
                            Skills
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/skill-scores">
                            <i class="pi pi-chart-pie text-base transition-colors group-hover:text-teal-600"></i>
                            Skill Scores
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/payments">
                            <i class="pi pi-credit-card text-base transition-colors group-hover:text-teal-600"></i>
                            Payments
                        </a>
                    </div>

                    <div class="mb-2 mt-6 px-2 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400">Operations</div>
                    <div class="flex flex-col gap-1">
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/timetables">
                            <i class="pi pi-calendar text-base transition-colors group-hover:text-teal-600"></i>
                            Timetable
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/pins">
                            <i class="pi pi-key text-base transition-colors group-hover:text-teal-600"></i>
                            Pins
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/library">
                            <i class="pi pi-book text-base transition-colors group-hover:text-teal-600"></i>
                            Library
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/dorms">
                            <i class="pi pi-home text-base transition-colors group-hover:text-teal-600"></i>
                            Dorms
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/settings">
                            <i class="pi pi-cog text-base transition-colors group-hover:text-teal-600"></i>
                            Settings
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/comments">
                            <i class="pi pi-comments text-base transition-colors group-hover:text-teal-600"></i>
                            Comments
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/my-account">
                            <i class="pi pi-user text-base transition-colors group-hover:text-teal-600"></i>
                            My Account
                        </a>
                    </div>

                    <div class="mb-2 mt-6 px-2 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400">Portals</div>
                    <div class="flex flex-col gap-1 pb-6">
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/portal/student">
                            <i class="pi pi-id-card text-base transition-colors group-hover:text-teal-600"></i>
                            Student Portal
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/portal/parent">
                            <i class="pi pi-users text-base transition-colors group-hover:text-teal-600"></i>
                            Parent Portal
                        </a>
                        <a class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900" href="/portal/staff">
                            <i class="pi pi-briefcase text-base transition-colors group-hover:text-teal-600"></i>
                            Staff Portal
                        </a>
                    </div>
                </nav>
            </aside>

            <div class="flex h-full min-w-0 flex-col overflow-hidden lg:pl-0">
                <header class="flex min-h-[4.5rem] items-center justify-between gap-4 border-b border-slate-200/80 bg-white/80 px-4 py-4 shadow-sm backdrop-blur-xl lg:h-18 lg:px-8">
                    <div class="flex items-center gap-4">
                        <button class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition-all hover:border-teal-300 hover:bg-teal-50 hover:text-teal-700 lg:hidden" @click="toggleSidebar">
                            <i class="pi pi-bars text-base"></i>
                        </button>
                        <div class="font-display text-xl font-bold tracking-tight text-slate-900">Overview</div>
                        <div class="hidden items-center gap-2 rounded-full bg-gradient-to-r from-teal-50 to-teal-100 px-3 py-1.5 text-xs font-medium text-teal-700 shadow-sm md:flex">
                            <span class="h-2 w-2 animate-pulse rounded-full bg-teal-500 shadow-sm shadow-teal-500/50"></span>
                            Next exam cycle in 12 days
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <PInputText placeholder="Quick find..." class="w-32 rounded-xl sm:w-48" />
                        <PButton icon="pi pi-bell" severity="secondary" text rounded class="hidden sm:inline-flex" />
                        <PButton icon="pi pi-plus" label="New" severity="success" class="hidden rounded-xl sm:inline-flex" />
                        <PButton icon="pi pi-sign-out" severity="secondary" text rounded @click="logout" />
                        <PAvatar label="SA" shape="circle" class="hidden bg-gradient-to-br from-teal-500 to-teal-600 text-white shadow-md shadow-teal-500/30 sm:inline-flex" />
                    </div>
                </header>

                <main class="flex-1 min-w-0 overflow-y-auto px-4 py-6 lg:px-8 lg:py-8">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
