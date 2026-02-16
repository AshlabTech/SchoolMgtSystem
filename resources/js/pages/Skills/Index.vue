<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';

const props = defineProps({
    skills: Array,
    levels: Array,
});

const { can } = usePermissions();

const form = useForm({
    name: '',
    skill_type: '',
    class_level_id: null,
});

const editingId = ref(null);
const showView = ref(false);
const viewRecord = ref(null);

const skillTypes = [
    { label: 'Psychomotor', value: 'psychomotor' },
    { label: 'Affective', value: 'affective' },
];

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const startEdit = (record) => {
    editingId.value = record.id;
    form.clearErrors();
    form.name = record.name ?? '';
    form.skill_type = record.skill_type ?? '';
    form.class_level_id = record.class_level_id ?? record.class_level?.id ?? null;
};

const cancelEdit = () => {
    editingId.value = null;
    form.reset();
    form.clearErrors();
};

const submit = () => {
    if (editingId.value) {
        form.put(`/skills/${editingId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingId.value = null;
                form.reset();
            },
        });
        return;
    }

    form.post('/skills', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const remove = (id) => {
    if (!confirm('Delete this skill?')) return;
    router.delete(`/skills/${id}`, { preserveScroll: true });
};

const getSkillTypeLabel = (type) => {
    return type === 'psychomotor' ? 'Psychomotor' : type === 'affective' ? 'Affective' : type;
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-[400px_1fr]">
                <PCard class="shadow-sm">
                    <template #title>{{ editingId ? 'Edit Skill' : 'New Skill' }}</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <PInputText v-model="form.name" placeholder="Skill name (e.g. Handwriting, Punctuality)" class="w-full" />
                                <FieldError :errors="form.errors" field="name" />
                            </div>
                            <div>
                                <PDropdown
                                    v-model="form.skill_type"
                                    :options="skillTypes"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Select skill type"
                                    class="w-full"
                                />
                                <FieldError :errors="form.errors" field="skill_type" />
                            </div>
                            <div>
                                <ModelSelect
                                    v-model="form.class_level_id"
                                    :options="levels"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Class level (optional - applies to all if not selected)"
                                />
                                <FieldError :errors="form.errors" field="class_level_id" />
                            </div>
                            <div class="flex gap-2">
                                <PButton :label="editingId ? 'Update' : 'Create'" icon="pi pi-check" severity="success" @click="submit" />
                                <PButton v-if="editingId" label="Cancel" icon="pi pi-times" severity="secondary" @click="cancelEdit" />
                            </div>
                        </div>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Skills</template>
                    <template #content>
                        <PDataTable :value="skills" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn field="name" header="Skill Name" />
                            <PColumn header="Type">
                                <template #body="slotProps">
                                    <span
                                        :class="{
                                            'inline-flex rounded-full px-2 py-1 text-xs font-semibold': true,
                                            'bg-blue-100 text-blue-800': slotProps.data.skill_type === 'psychomotor',
                                            'bg-purple-100 text-purple-800': slotProps.data.skill_type === 'affective',
                                        }"
                                    >
                                        {{ getSkillTypeLabel(slotProps.data.skill_type) }}
                                    </span>
                                </template>
                            </PColumn>
                            <PColumn header="Class Level">
                                <template #body="slotProps">
                                    {{ slotProps.data.class_level?.name ?? 'All Levels' }}
                                </template>
                            </PColumn>
                            <PColumn header="Actions">
                                <template #body="slotProps">
                                    <div class="flex gap-1">
                                        <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                        <PButton v-if="can('manage.grades')" icon="pi pi-pencil" severity="info" text rounded @click="startEdit(slotProps.data)" />
                                        <PButton v-if="can('manage.grades')" icon="pi pi-trash" severity="danger" text rounded @click="remove(slotProps.data.id)" />
                                    </div>
                                </template>
                            </PColumn>
                        </PDataTable>
                    </template>
                </PCard>
            </section>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Skill Record" />
    </AppShell>
</template>
