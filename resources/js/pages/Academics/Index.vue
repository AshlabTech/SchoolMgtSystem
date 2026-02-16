<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';

const props = defineProps({
    classLevels: Array,
    classes: Array,
    sections: Array,
    teachers: Array,
});

const { can } = usePermissions();

const levelForm = useForm({
    name: '',
    code: '',
    description: '',
});

const classForm = useForm({
    class_level_id: null,
    name: '',
    code: '',
    description: '',
});

const sectionForm = useForm({
    class_ids: [],
    name: '',
    teacher_id: null,
});

const editingLevelId = ref(null);
const editingClassId = ref(null);
const editingSectionId = ref(null);
const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const startEditLevel = (record) => {
    editingLevelId.value = record.id;
    levelForm.clearErrors();
    levelForm.name = record.name ?? '';
    levelForm.code = record.code ?? '';
    levelForm.description = record.description ?? '';
};

const cancelEditLevel = () => {
    editingLevelId.value = null;
    levelForm.reset();
    levelForm.clearErrors();
};

const startEditClass = (record) => {
    editingClassId.value = record.id;
    classForm.clearErrors();
    classForm.class_level_id = record.class_level_id ?? record.level?.id ?? null;
    classForm.name = record.name ?? '';
    classForm.code = record.code ?? '';
    classForm.description = record.description ?? '';
};

const cancelEditClass = () => {
    editingClassId.value = null;
    classForm.reset();
    classForm.clearErrors();
};

const startEditSection = (record) => {
    editingSectionId.value = record.id;
    sectionForm.clearErrors();
    sectionForm.class_ids = record.school_classes?.map((item) => item.id) ?? (record.class_id ? [record.class_id] : []);
    sectionForm.name = record.name ?? '';
    sectionForm.teacher_id = record.teacher_id ?? record.teacher?.id ?? null;
};

const cancelEditSection = () => {
    editingSectionId.value = null;
    sectionForm.reset();
    sectionForm.clearErrors();
};

const submitLevel = () => {
    if (editingLevelId.value) {
        levelForm.put(`/academics/class-levels/${editingLevelId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingLevelId.value = null;
                levelForm.reset();
            },
        });
        return;
    }

    levelForm.post('/academics/class-levels', {
        preserveScroll: true,
        onSuccess: () => {
            levelForm.reset();
        },
    });
};

const submitClass = () => {
    if (editingClassId.value) {
        classForm.put(`/academics/classes/${editingClassId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingClassId.value = null;
                classForm.reset();
            },
        });
        return;
    }

    classForm.post('/academics/classes', {
        preserveScroll: true,
        onSuccess: () => {
            classForm.reset();
        },
    });
};

const submitSection = () => {
    if (editingSectionId.value) {
        sectionForm.put(`/academics/sections/${editingSectionId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingSectionId.value = null;
                sectionForm.reset();
            },
        });
        return;
    }

    sectionForm.post('/academics/sections', {
        preserveScroll: true,
        onSuccess: () => {
            sectionForm.reset();
        },
    });
};

const deleteLevel = (id) => {
    if (!confirm('Delete this class level?')) return;
    router.delete(`/academics/class-levels/${id}`, { preserveScroll: true });
};

const deleteClass = (id) => {
    if (!confirm('Delete this class?')) return;
    router.delete(`/academics/classes/${id}`, { preserveScroll: true });
};

const deleteSection = (id) => {
    if (!confirm('Delete this section?')) return;
    router.delete(`/academics/sections/${id}`, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                <PCard class="shadow-sm">
                    <template #title>Class Levels</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <PInputText v-model="levelForm.name" placeholder="Level name" class="w-full" />
                                <FieldError :errors="levelForm.errors" field="name" />
                            </div>
                            <div>
                                <PInputText v-model="levelForm.code" placeholder="Code (optional)" class="w-full" />
                                <FieldError :errors="levelForm.errors" field="code" />
                            </div>
                            <div>
                                <PInputText v-model="levelForm.description" placeholder="Description" class="w-full" />
                                <FieldError :errors="levelForm.errors" field="description" />
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <PButton :label="editingLevelId ? 'Update Level' : 'Add Level'" icon="pi pi-plus" severity="success" @click="submitLevel" />
                                <PButton v-if="editingLevelId" label="Cancel" severity="secondary" text @click="cancelEditLevel" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <PDataTable :value="classLevels" stripedRows responsiveLayout="scroll" class="text-sm">
                                <PColumn field="name" header="Level" />
                                <PColumn field="classes_count" header="Classes" />
                                <PColumn header="">
                                    <template #body="slotProps">
                                        <div class="flex gap-2">
                                            <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                            <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEditLevel(slotProps.data)" />
                                            <PButton icon="pi pi-trash" severity="danger" text rounded @click="deleteLevel(slotProps.data.id)" />
                                        </div>
                                    </template>
                                </PColumn>
                            </PDataTable>
                        </div>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Classes</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <ModelSelect
                                    v-model="classForm.class_level_id"
                                    :options="classLevels"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Select level"
                                    :canCreate="can('manage.classes')"
                                    createTitle="Add Class Level"
                                    createEndpoint="/academics/class-levels"
                                    :createFields="[
                                        { name: 'name', label: 'Level name', type: 'text' },
                                        { name: 'code', label: 'Code', type: 'text' },
                                        { name: 'description', label: 'Description', type: 'text' },
                                    ]"
                                />
                                <FieldError :errors="classForm.errors" field="class_level_id" />
                            </div>
                            <div>
                                <PInputText v-model="classForm.name" placeholder="Class name" class="w-full" />
                                <FieldError :errors="classForm.errors" field="name" />
                            </div>
                            <div>
                                <PInputText v-model="classForm.code" placeholder="Code (optional)" class="w-full" />
                                <FieldError :errors="classForm.errors" field="code" />
                            </div>
                            <div>
                                <PInputText v-model="classForm.description" placeholder="Description" class="w-full" />
                                <FieldError :errors="classForm.errors" field="description" />
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <PButton :label="editingClassId ? 'Update Class' : 'Add Class'" icon="pi pi-plus" severity="info" @click="submitClass" />
                                <PButton v-if="editingClassId" label="Cancel" severity="secondary" text @click="cancelEditClass" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <PDataTable :value="classes" stripedRows responsiveLayout="scroll" class="text-sm">
                                <PColumn field="name" header="Class" />
                                <PColumn header="Level">
                                    <template #body="slotProps">
                                        {{ slotProps.data.level?.name ?? '—' }}
                                    </template>
                                </PColumn>
                                <PColumn field="sections_count" header="Sections" />
                                <PColumn header="">
                                    <template #body="slotProps">
                                        <div class="flex gap-2">
                                            <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                            <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEditClass(slotProps.data)" />
                                            <PButton icon="pi pi-trash" severity="danger" text rounded @click="deleteClass(slotProps.data.id)" />
                                        </div>
                                    </template>
                                </PColumn>
                            </PDataTable>
                        </div>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Educational Sections</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <PMultiSelect
                                    v-model="sectionForm.class_ids"
                                    :options="classes"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Select class(es)"
                                    class="w-full"
                                />
                                <FieldError :errors="sectionForm.errors" field="class_ids" />
                            </div>
                            <div>
                                <ModelSelect
                                    v-model="sectionForm.teacher_id"
                                    :options="teachers"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Form teacher (optional)"
                                />
                                <FieldError :errors="sectionForm.errors" field="teacher_id" />
                            </div>
                            <div>
                                <PInputText v-model="sectionForm.name" placeholder="Section name (e.g. Nursery, Primary, Secondary)" class="w-full" />
                                <FieldError :errors="sectionForm.errors" field="name" />
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <PButton :label="editingSectionId ? 'Update Section' : 'Add Section'" icon="pi pi-plus" severity="secondary" @click="submitSection" />
                                <PButton v-if="editingSectionId" label="Cancel" severity="secondary" text @click="cancelEditSection" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <PDataTable :value="sections" stripedRows responsiveLayout="scroll" class="text-sm">
                                <PColumn field="name" header="Section" />
                                <PColumn header="Class">
                                    <template #body="slotProps">
                                        <span v-if="slotProps.data.school_classes?.length">
                                            {{ slotProps.data.school_classes.map(item => item.name).join(', ') }}
                                        </span>
                                        <span v-else>
                                            {{ slotProps.data.school_class?.name ?? '—' }}
                                        </span>
                                    </template>
                                </PColumn>
                                <PColumn header="Form Teacher">
                                    <template #body="slotProps">
                                        {{ slotProps.data.teacher?.name ?? '—' }}
                                    </template>
                                </PColumn>
                                <PColumn header="">
                                    <template #body="slotProps">
                                        <div class="flex gap-2">
                                            <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                            <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEditSection(slotProps.data)" />
                                            <PButton icon="pi pi-trash" severity="danger" text rounded @click="deleteSection(slotProps.data.id)" />
                                        </div>
                                    </template>
                                </PColumn>
                            </PDataTable>
                        </div>
                    </template>
                </PCard>
            </section>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Academic Record" />
    </AppShell>
</template>
