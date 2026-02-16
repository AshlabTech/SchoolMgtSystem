<template>
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_#fef3c7,_#f8fafc_45%,_#e2e8f0_100%)]">
        <div class="flex min-h-screen items-center justify-center px-6 py-12">
            <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white/80 p-8 shadow-xl backdrop-blur">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-teal-600 text-white">
                        <i class="pi pi-sparkles text-lg"></i>
                    </div>
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">SchoolApp</div>
                        <div class="text-xs text-slate-400">Academic Suite</div>
                    </div>
                </div>

                <div class="mt-8">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
