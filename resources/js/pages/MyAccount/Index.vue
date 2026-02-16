<script setup>
import { useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';

const props = defineProps({
    user: Object,
});

const profile = props.user.profile || {};

const form = useForm({
    name: props.user.name || '',
    email: props.user.email || '',
    username: props.user.username || '',
    first_name: profile.first_name || '',
    last_name: profile.last_name || '',
    other_name: profile.other_name || '',
    phone: profile.phone || '',
    address: profile.address || '',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put('/my-account', { preserveScroll: true });
};

const changePassword = () => {
    passwordForm.put('/my-account/password', {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
        },
    });
};
</script>

<template>
    <AppShell>
        <div class="grid gap-6">
            <PCard class="shadow-sm">
                <template #title>Profile</template>
                <template #content>
                    <div class="grid gap-3 md:grid-cols-2">
                        <div>
                            <PInputText v-model="form.name" placeholder="Display name" class="w-full" />
                            <FieldError :errors="form.errors" field="name" />
                        </div>
                        <div>
                            <PInputText v-model="form.email" placeholder="Email" class="w-full" />
                            <FieldError :errors="form.errors" field="email" />
                        </div>
                        <div>
                            <PInputText v-model="form.username" placeholder="Username" class="w-full" />
                            <FieldError :errors="form.errors" field="username" />
                        </div>
                        <div>
                            <PInputText v-model="form.first_name" placeholder="First name" class="w-full" />
                            <FieldError :errors="form.errors" field="first_name" />
                        </div>
                        <div>
                            <PInputText v-model="form.last_name" placeholder="Last name" class="w-full" />
                            <FieldError :errors="form.errors" field="last_name" />
                        </div>
                        <div>
                            <PInputText v-model="form.other_name" placeholder="Other name" class="w-full" />
                            <FieldError :errors="form.errors" field="other_name" />
                        </div>
                        <div>
                            <PInputText v-model="form.phone" placeholder="Phone" class="w-full" />
                            <FieldError :errors="form.errors" field="phone" />
                        </div>
                        <div>
                            <PInputText v-model="form.address" placeholder="Address" class="w-full" />
                            <FieldError :errors="form.errors" field="address" />
                        </div>
                    </div>
                    <PButton label="Save" icon="pi pi-save" severity="success" class="mt-4" @click="submit" />
                </template>
            </PCard>

            <PCard class="shadow-sm">
                <template #title>Change Password</template>
                <template #content>
                    <div class="grid gap-3 md:grid-cols-3">
                        <div>
                            <PInputText v-model="passwordForm.current_password" type="password" placeholder="Current password" class="w-full" />
                            <FieldError :errors="passwordForm.errors" field="current_password" />
                        </div>
                        <div>
                            <PInputText v-model="passwordForm.password" type="password" placeholder="New password" class="w-full" />
                            <FieldError :errors="passwordForm.errors" field="password" />
                        </div>
                        <div>
                            <PInputText v-model="passwordForm.password_confirmation" type="password" placeholder="Confirm password" class="w-full" />
                            <FieldError :errors="passwordForm.errors" field="password_confirmation" />
                        </div>
                    </div>
                    <PButton label="Update Password" icon="pi pi-lock" severity="info" class="mt-4" @click="changePassword" />
                </template>
            </PCard>
        </div>
    </AppShell>
</template>
