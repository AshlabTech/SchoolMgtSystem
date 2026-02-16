<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthShell from '../../layouts/AuthShell.vue';

const form = useForm({
    login: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login');
};
</script>

<template>
    <AuthShell>
        <div class="space-y-2">
            <div class="text-2xl font-semibold" :style="{ fontFamily: 'var(--font-display)' }">Welcome back</div>
            <p class="text-sm text-slate-500">Sign in with your email or username.</p>
        </div>

        <div class="mt-6 space-y-4">
            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Email / Username</label>
                <PInputText v-model="form.login" placeholder="Enter your login" class="mt-2 w-full" />
                <div v-if="form.errors.login" class="mt-1 text-xs text-red-500">{{ form.errors.login }}</div>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Password</label>
                <PInputText v-model="form.password" type="password" placeholder="Password" class="mt-2 w-full" />
                <div v-if="form.errors.password" class="mt-1 text-xs text-red-500">{{ form.errors.password }}</div>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-500">
                <input v-model="form.remember" type="checkbox" class="h-4 w-4 rounded border-slate-300" />
                Remember me
            </label>
            <PButton label="Sign In" icon="pi pi-lock" severity="success" class="w-full" :loading="form.processing" @click="submit" />
        </div>
    </AuthShell>
</template>
