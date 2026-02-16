<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import DateField from '../../components/DateField.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';

const props = defineProps({
    roles: Array,
    staff: Array,
});

const form = useForm({
    name: '',
    email: '',
    username: '',
    password: '',
    role: null,
    first_name: '',
    last_name: '',
    phone: '',
    designation: '',
    employment_date: '',
});

const editingId = ref(null);
const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const startEdit = (record) => {
    editingId.value = record.id;
    form.clearErrors();
    form.name = record.name ?? '';
    form.email = record.email ?? '';
    form.username = record.username ?? '';
    form.password = '';
    form.role = record.roles?.[0]?.name ?? null;
    form.first_name = record.profile?.first_name ?? '';
    form.last_name = record.profile?.last_name ?? '';
    form.phone = record.profile?.phone ?? '';
    form.designation = record.staff_profile?.designation ?? '';
    form.employment_date = record.staff_profile?.employment_date ?? '';
};

const cancelEdit = () => {
    editingId.value = null;
    form.reset();
    form.clearErrors();
};

const submit = () => {
    if (editingId.value) {
        form.put(`/staff/${editingId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingId.value = null;
                form.reset();
            },
        });
        return;
    }

    form.post('/staff', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const resetPassword = (id) => {
    router.post(`/staff/${id}/reset-password`, {}, { preserveScroll: true });
};

const deleteStaff = (id) => {
    if (!confirm('Delete this staff account?')) return;
    router.delete(`/staff/${id}`, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <PCard class="shadow-sm">
                <template #title>Add Staff</template>
                <template #content>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
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
                            <PInputText v-model="form.password" type="password" placeholder="Password" class="w-full" />
                            <FieldError :errors="form.errors" field="password" />
                        </div>
                        <div>
                            <ModelSelect v-model="form.role" :options="roles" optionLabel="name" optionValue="name" placeholder="Role" />
                            <FieldError :errors="form.errors" field="role" />
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
                            <PInputText v-model="form.phone" placeholder="Phone" class="w-full" />
                            <FieldError :errors="form.errors" field="phone" />
                        </div>
                        <div>
                            <PInputText v-model="form.designation" placeholder="Designation" class="w-full" />
                            <FieldError :errors="form.errors" field="designation" />
                        </div>
                        <div>
                            <DateField v-model="form.employment_date" placeholder="Employment date" />
                            <FieldError :errors="form.errors" field="employment_date" />
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <PButton :label="editingId ? 'Update' : 'Create'" icon="pi pi-save" severity="success" @click="submit" />
                        <PButton v-if="editingId" label="Cancel" severity="secondary" text @click="cancelEdit" />
                    </div>
                </template>
            </PCard>

            <PCard class="shadow-sm">
                <template #title>Staff Directory</template>
                <template #content>
                    <PDataTable :value="staff" stripedRows responsiveLayout="scroll" class="text-sm">
                        <PColumn field="name" header="Name" />
                        <PColumn header="Role">
                            <template #body="slotProps">
                                {{ slotProps.data.roles?.map(role => role.name).join(', ') || '—' }}
                            </template>
                        </PColumn>
                        <PColumn field="email" header="Email" />
                        <PColumn header="Designation">
                            <template #body="slotProps">
                                {{ slotProps.data.staff_profile?.designation ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn header="">
                            <template #body="slotProps">
                                <div class="flex gap-2">
                                    <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                    <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEdit(slotProps.data)" />
                                    <PButton icon="pi pi-refresh" severity="secondary" text rounded @click="resetPassword(slotProps.data.id)" />
                                    <PButton icon="pi pi-trash" severity="danger" text rounded @click="deleteStaff(slotProps.data.id)" />
                                </div>
                            </template>
                        </PColumn>
                    </PDataTable>
                </template>
            </PCard>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Staff Record" :excludeKeys="['password', 'remember_token']" />
    </AppShell>
</template>
