<script setup>
import { reactive } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import FieldError from '../../components/FieldError.vue';

const props = defineProps({
    comments: Array,
});

const rows = reactive((props.comments || []).map((comment) => ({ ...comment })));

const form = useForm({
    comment: '',
    type: 'teacher',
    is_active: true,
    is_default: false,
    sort_order: 0,
});

const submit = () => {
    form.post('/comments', {
        preserveScroll: true,
        onSuccess: () => form.reset('comment', 'is_default', 'sort_order'),
    });
};

const updateComment = (comment) => {
    router.put(`/comments/${comment.id}`, {
        comment: comment.comment,
        type: comment.type,
        is_active: comment.is_active ? 1 : 0,
        is_default: comment.is_default ? 1 : 0,
        sort_order: comment.sort_order || 0,
    }, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <PCard class="shadow-sm">
                <template #title>Add Result Comment</template>
                <template #content>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-5">
                        <div class="md:col-span-2">
                            <PInputText v-model="form.comment" placeholder="Comment text" class="w-full" />
                            <FieldError :errors="form.errors" field="comment" />
                        </div>
                        <div>
                            <PDropdown
                                v-model="form.type"
                                :options="[{ label: 'Teacher', value: 'teacher' }, { label: 'Principal', value: 'principal' }]"
                                optionLabel="label"
                                optionValue="value"
                                class="w-full"
                            />
                        </div>
                        <div>
                            <PInputNumber v-model="form.sort_order" :min="0" class="w-full" />
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm">
                                <PInputSwitch v-model="form.is_active" />
                                Active
                            </label>
                            <label class="flex items-center gap-2 text-sm">
                                <PInputSwitch v-model="form.is_default" />
                                Default
                            </label>
                        </div>
                    </div>
                    <PButton label="Save" icon="pi pi-plus" severity="success" class="mt-4" @click="submit" />
                </template>
            </PCard>

            <PCard class="shadow-sm">
                <template #title>Predefined Comments</template>
                <template #content>
                    <PDataTable :value="rows" stripedRows responsiveLayout="scroll" class="text-sm">
                        <PColumn header="Comment">
                            <template #body="slotProps">
                                <PInputText v-model="slotProps.data.comment" class="w-full" />
                            </template>
                        </PColumn>
                        <PColumn header="Type">
                            <template #body="slotProps">
                                <PDropdown
                                    v-model="slotProps.data.type"
                                    :options="[{ label: 'Teacher', value: 'teacher' }, { label: 'Principal', value: 'principal' }]"
                                    optionLabel="label"
                                    optionValue="value"
                                    class="w-full"
                                />
                            </template>
                        </PColumn>
                        <PColumn header="Order">
                            <template #body="slotProps">
                                <PInputNumber v-model="slotProps.data.sort_order" :min="0" class="w-full" />
                            </template>
                        </PColumn>
                        <PColumn header="Active">
                            <template #body="slotProps">
                                <PInputSwitch v-model="slotProps.data.is_active" />
                            </template>
                        </PColumn>
                        <PColumn header="Default">
                            <template #body="slotProps">
                                <PInputSwitch v-model="slotProps.data.is_default" />
                            </template>
                        </PColumn>
                        <PColumn header="">
                            <template #body="slotProps">
                                <PButton icon="pi pi-save" severity="success" text rounded @click="updateComment(slotProps.data)" />
                            </template>
                        </PColumn>
                    </PDataTable>
                </template>
            </PCard>
        </div>
    </AppShell>
</template>
