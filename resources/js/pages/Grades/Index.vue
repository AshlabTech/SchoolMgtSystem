<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';

const props = defineProps({
    grades: Array,
    levels: Array,
});

const { can } = usePermissions();

const form = useForm({
    name: '',
    mark_from: '',
    mark_to: '',
    remark: '',
    class_level_id: null,
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
    form.mark_from = record.mark_from ?? '';
    form.mark_to = record.mark_to ?? '';
    form.remark = record.remark ?? '';
    form.class_level_id = record.class_level_id ?? record.class_level?.id ?? null;
};

const cancelEdit = () => {
    editingId.value = null;
    form.reset();
    form.clearErrors();
};

const submit = () => {
    if (editingId.value) {
        form.put(`/grades/${editingId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingId.value = null;
                form.reset();
            },
        });
        return;
    }

    form.post('/grades', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const remove = (id) => {
    if (!confirm('Delete this grade?')) return;
    router.delete(`/grades/${id}`, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid gap-6">
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-[360px_1fr]">
                <PCard class="shadow-sm">
                    <template #title>New Grade</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <PInputText v-model="form.name" placeholder="Grade name (e.g. A)" class="w-full" />
                                <FieldError :errors="form.errors" field="name" />
                            </div>
                            <div>
                                <PInputText v-model="form.mark_from" placeholder="Mark from" class="w-full" />
                                <FieldError :errors="form.errors" field="mark_from" />
                            </div>
                            <div>
                                <PInputText v-model="form.mark_to" placeholder="Mark to" class="w-full" />
                                <FieldError :errors="form.errors" field="mark_to" />
                            </div>
                            <div>
                                <PInputText v-model="form.remark" placeholder="Remark" class="w-full" />
                                <FieldError :errors="form.errors" field="remark" />
                            </div>
                            <div>
                                <ModelSelect
                                    v-model="form.class_level_id"
                                    :options="levels"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Class level"
                                    :canCreate="can('manage.classes')"
                                    createTitle="Add Class Level"
                                    createEndpoint="/academics/class-levels"
                                    :createFields="[
                                        { name: 'name', label: 'Level name', type: 'text' },
                                        { name: 'code', label: 'Code', type: 'text' },
                                        { name: 'description', label: 'Description', type: 'text' },
                                    ]"
                                />
                                <FieldError :errors="form.errors" field="class_level_id" />
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <PButton :label="editingId ? 'Update Grade' : 'Create Grade'" icon="pi pi-plus" severity="success" @click="submit" />
                                <PButton v-if="editingId" label="Cancel" severity="secondary" text @click="cancelEdit" />
                            </div>
                        </div>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Grades</template>
                    <template #content>
                        <PDataTable :value="grades" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn field="name" header="Grade" />
                            <PColumn field="mark_from" header="From" />
                            <PColumn field="mark_to" header="To" />
                            <PColumn header="Level">
                                <template #body="slotProps">
                                    {{ slotProps.data.class_level?.name ?? '—' }}
                                </template>
                            </PColumn>
                                <PColumn header="">
                                    <template #body="slotProps">
                                        <div class="flex gap-2">
                                            <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                            <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEdit(slotProps.data)" />
                                            <PButton icon="pi pi-trash" severity="danger" text rounded @click="remove(slotProps.data.id)" />
                                        </div>
                                    </template>
                                </PColumn>
                            </PDataTable>
                        </template>
                    </PCard>
                </section>
            </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Grade Record" />
    </AppShell>
</template>
