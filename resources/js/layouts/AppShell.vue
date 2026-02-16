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
                class="fixed inset-y-0 left-0 z-40 h-full w-62 overflow-y-auto border-r border-slate-200 bg-white/90 backdrop-blur transition-transform lg:static lg:translate-x-0 lg:bg-white/70"
                :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
                <div class="flex h-16 items-center gap-3 px-6">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-600 text-white">
                        <i class="pi pi-sparkles text-base"></i>
                    </div>
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">SchoolApp</div>
                        <div class="text-xs text-slate-400">Academic Suite</div>
                    </div>
                </div>
                <div class="px-6 pb-2">
                    <div class="text-[11px] uppercase tracking-[0.2em] text-slate-400">Current Term</div>
                    <div class="text-xs font-semibold text-slate-600">{{ currentTerm }}</div>
                    <div class="mt-2 text-[11px] uppercase tracking-[0.2em] text-slate-400">Academic Year</div>
                    <div class="text-xs text-slate-500">{{ currentYear }}</div>
                </div>
                <nav class="px-4 py-6" @click="onNavClick">
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
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/skills">
                            <i class="pi pi-list-check"></i>
                            Skills
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/skill-scores">
                            <i class="pi pi-chart-pie"></i>
                            Skill Scores
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
                        <a class="flex items-center gap-3 rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100" href="/comments">
                            <i class="pi pi-comments"></i>
                            Comments
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
            </aside>

            <div class="flex h-full min-w-0 flex-col overflow-hidden lg:pl-0">
                <header class="flex min-h-[4rem] items-center justify-between gap-4 border-b border-slate-200 bg-white/70 px-4 py-3 backdrop-blur lg:h-16 lg:px-6">
                    <div class="flex items-center gap-4">
                        <button class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 lg:hidden" @click="toggleSidebar">
                            <i class="pi pi-bars"></i>
                        </button>
                        <div class="text-lg font-semibold" :style="{ fontFamily: 'var(--font-display)' }">Overview</div>
                        <div class="hidden items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-500 md:flex">
                            <span class="h-2 w-2 rounded-full bg-teal-500"></span>
                            Next exam cycle in 12 days
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <span class="hidden text-xs text-slate-500 md:inline">Search</span>
                        <PInputText placeholder="Quick find" class="w-32 sm:w-44" />
                        <PButton icon="pi pi-bell" severity="secondary" text rounded class="hidden sm:inline-flex" />
                        <PButton icon="pi pi-plus" label="New" severity="success" rounded class="hidden sm:inline-flex" />
                        <PButton icon="pi pi-sign-out" severity="secondary" text rounded @click="logout" />
                        <PAvatar label="SA" shape="circle" class="hidden sm:inline-flex bg-teal-600 text-white" />
                    </div>
                </header>

                <main class="flex-1 min-w-0 overflow-y-auto px-4 py-6 lg:px-6 lg:py-8">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
