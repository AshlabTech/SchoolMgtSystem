<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppShell from '../../layouts/AppShell.vue';
import DateField from '../../components/DateField.vue';
import FieldError from '../../components/FieldError.vue';
import ModelSelect from '../../components/ModelSelect.vue';
import RecordViewer from '../../components/RecordViewer.vue';
import { usePermissions } from '../../composables/usePermissions';

const props = defineProps({
    books: Array,
    loans: Array,
    classes: Array,
    users: Array,
});

const { can } = usePermissions();

const today = new Date().toISOString().slice(0, 10);

const bookForm = useForm({
    title: '',
    author: '',
    category: '',
    location: '',
    class_id: null,
    description: '',
    url: '',
    total_copies: null,
});

const editingBookId = ref(null);
const showView = ref(false);
const viewRecord = ref(null);

const openView = (record) => {
    viewRecord.value = record;
    showView.value = true;
};

const startEditBook = (record) => {
    editingBookId.value = record.id;
    bookForm.clearErrors();
    bookForm.title = record.title ?? '';
    bookForm.author = record.author ?? '';
    bookForm.category = record.category ?? '';
    bookForm.location = record.location ?? '';
    bookForm.class_id = record.class_id ?? record.school_class?.id ?? null;
    bookForm.description = record.description ?? '';
    bookForm.url = record.url ?? '';
    bookForm.total_copies = record.total_copies ?? null;
};

const cancelEditBook = () => {
    editingBookId.value = null;
    bookForm.reset();
    bookForm.clearErrors();
};

const loanForm = useForm({
    book_id: null,
    borrower_user_id: null,
    issued_at: today,
    due_at: '',
    notes: '',
});

const submitBook = () => {
    if (editingBookId.value) {
        bookForm.put(`/library/books/${editingBookId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                editingBookId.value = null;
                bookForm.reset();
            },
        });
        return;
    }

    bookForm.post('/library/books', {
        preserveScroll: true,
        onSuccess: () => {
            bookForm.reset();
        },
    });
};

const submitLoan = () => {
    loanForm.post('/library/loans', {
        preserveScroll: true,
        onSuccess: () => {
            loanForm.reset('book_id', 'borrower_user_id', 'due_at', 'notes');
            loanForm.issued_at = today;
        },
    });
};

const deleteBook = (id) => {
    if (!confirm('Delete this book?')) return;
    router.delete(`/library/books/${id}`, { preserveScroll: true });
};

const returnLoan = (id) => {
    router.post(`/library/loans/${id}/return`, {}, { preserveScroll: true });
};
</script>

<template>
    <AppShell>
        <div class="grid grid-cols-1 gap-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[360px_1fr]">
                <PCard class="shadow-sm">
                    <template #title>Add Book</template>
                    <template #content>
                        <div class="space-y-3">
                            <div>
                                <PInputText v-model="bookForm.title" placeholder="Title" class="w-full" />
                                <FieldError :errors="bookForm.errors" field="title" />
                            </div>
                            <div>
                                <PInputText v-model="bookForm.author" placeholder="Author" class="w-full" />
                                <FieldError :errors="bookForm.errors" field="author" />
                            </div>
                            <div>
                                <PInputText v-model="bookForm.category" placeholder="Category" class="w-full" />
                                <FieldError :errors="bookForm.errors" field="category" />
                            </div>
                            <div>
                                <PInputText v-model="bookForm.location" placeholder="Shelf / Location" class="w-full" />
                                <FieldError :errors="bookForm.errors" field="location" />
                            </div>
                            <div>
                                <ModelSelect
                                    v-model="bookForm.class_id"
                                    :options="classes"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Linked class (optional)"
                                />
                                <FieldError :errors="bookForm.errors" field="class_id" />
                            </div>
                            <div>
                                <PInputText v-model="bookForm.url" placeholder="Resource URL" class="w-full" />
                                <FieldError :errors="bookForm.errors" field="url" />
                            </div>
                            <div>
                                <PInputNumber v-model="bookForm.total_copies" placeholder="Total copies" class="w-full" />
                                <FieldError :errors="bookForm.errors" field="total_copies" />
                            </div>
                            <div>
                                <textarea v-model="bookForm.description" rows="3" class="w-full rounded-md border border-slate-200 p-2 text-sm" placeholder="Description"></textarea>
                                <FieldError :errors="bookForm.errors" field="description" />
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <PButton :label="editingBookId ? 'Update Book' : 'Save Book'" icon="pi pi-plus" severity="success" @click="submitBook" />
                                <PButton v-if="editingBookId" label="Cancel" severity="secondary" text @click="cancelEditBook" />
                            </div>
                        </div>
                    </template>
                </PCard>

                <PCard class="shadow-sm">
                    <template #title>Books</template>
                    <template #content>
                        <PDataTable :value="books" stripedRows responsiveLayout="scroll" class="text-sm">
                            <PColumn field="title" header="Title" />
                            <PColumn header="Copies">
                                <template #body="slotProps">
                                    <span v-if="slotProps.data.total_copies !== null">
                                        {{ slotProps.data.total_copies - slotProps.data.issued_copies }} / {{ slotProps.data.total_copies }}
                                    </span>
                                    <span v-else>∞</span>
                                </template>
                            </PColumn>
                            <PColumn header="Class">
                                <template #body="slotProps">
                                    {{ slotProps.data.school_class?.name ?? '—' }}
                                </template>
                            </PColumn>
                            <PColumn header="">
                                <template #body="slotProps">
                                    <div class="flex gap-2">
                                        <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                        <PButton icon="pi pi-pencil" severity="info" text rounded @click="startEditBook(slotProps.data)" />
                                        <PButton icon="pi pi-trash" severity="danger" text rounded @click="deleteBook(slotProps.data.id)" />
                                    </div>
                                </template>
                            </PColumn>
                        </PDataTable>
                    </template>
                </PCard>
            </div>

            <PCard class="shadow-sm">
                <template #title>Issue / Return</template>
                <template #content>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div>
                            <ModelSelect
                                v-model="loanForm.book_id"
                                :options="books"
                                optionLabel="title"
                                optionValue="id"
                                placeholder="Select book"
                                :canCreate="can('manage.library')"
                                createTitle="Add Book"
                                createEndpoint="/library/books"
                                :createFields="[
                                    { name: 'title', label: 'Title', type: 'text' },
                                    { name: 'author', label: 'Author', type: 'text' },
                                    { name: 'category', label: 'Category', type: 'text' },
                                    { name: 'location', label: 'Location', type: 'text' },
                                    { name: 'total_copies', label: 'Total copies', type: 'number' },
                                ]"
                            />
                            <FieldError :errors="loanForm.errors" field="book_id" />
                        </div>
                        <div>
                            <ModelSelect v-model="loanForm.borrower_user_id" :options="users" optionLabel="name" optionValue="id" placeholder="Borrower" />
                            <FieldError :errors="loanForm.errors" field="borrower_user_id" />
                        </div>
                        <div>
                            <DateField v-model="loanForm.issued_at" placeholder="Issued date" />
                            <FieldError :errors="loanForm.errors" field="issued_at" />
                        </div>
                        <div>
                            <DateField v-model="loanForm.due_at" placeholder="Due date" />
                            <FieldError :errors="loanForm.errors" field="due_at" />
                        </div>
                        <div>
                            <PInputText v-model="loanForm.notes" placeholder="Notes" class="w-full" />
                            <FieldError :errors="loanForm.errors" field="notes" />
                        </div>
                    </div>
                    <PButton label="Issue Book" icon="pi pi-send" severity="info" class="mt-4" @click="submitLoan" />
                </template>
            </PCard>

            <PCard class="shadow-sm">
                <template #title>Loans</template>
                <template #content>
                    <PDataTable :value="loans" stripedRows responsiveLayout="scroll" class="text-sm">
                        <PColumn header="Book">
                            <template #body="slotProps">
                                {{ slotProps.data.book?.title ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn header="Borrower">
                            <template #body="slotProps">
                                {{ slotProps.data.borrower?.name ?? '—' }}
                            </template>
                        </PColumn>
                        <PColumn field="issued_at" header="Issued" />
                        <PColumn field="due_at" header="Due" />
                        <PColumn header="Status">
                            <template #body="slotProps">
                                <PTag :value="slotProps.data.status" :severity="slotProps.data.status === 'returned' ? 'success' : 'warning'" />
                            </template>
                        </PColumn>
                        <PColumn header="">
                            <template #body="slotProps">
                                <div class="flex gap-2">
                                    <PButton icon="pi pi-eye" severity="secondary" text rounded @click="openView(slotProps.data)" />
                                    <PButton
                                        v-if="!slotProps.data.returned_at"
                                        icon="pi pi-check"
                                        severity="success"
                                        text
                                        rounded
                                        @click="returnLoan(slotProps.data.id)"
                                    />
                                </div>
                            </template>
                        </PColumn>
                        </PDataTable>
                    </template>
                </PCard>
        </div>

        <RecordViewer v-model:visible="showView" :record="viewRecord" title="Library Record" />
    </AppShell>
</template>
