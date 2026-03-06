<script setup>
import { computed, ref, watch } from 'vue';

defineOptions({ name: 'StudentSelector' });

const props = defineProps({
    modelValue: {
        type: [String, Number, null],
        default: null,
    },
    students: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Search student...',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    showClass: {
        type: Boolean,
        default: true,
    },
    showAdmissionNumber: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['update:modelValue']);

const filteredStudents = ref([]);
const selectedStudent = ref(null);

// Format student display name
const getStudentDisplayName = (student) => {
    if (!student) return '';
    const name = student.user?.name ||
                 `${student.user?.profile?.first_name || ''} ${student.user?.profile?.last_name || ''}`.trim() ||
                 'Unnamed Student';
    return name;
};

// Format student with additional info
const formatStudentOption = (student) => {
    if (!student) return null;

    const name = getStudentDisplayName(student);
    const admissionNo = student.admission_number || student.user?.profile?.admission_number || '';
    const className = student.current_enrollment?.school_class?.name ||
                     student.school_class?.name ||
                     'No Class';

    let displayText = name;
    if (props.showAdmissionNumber && admissionNo) {
        displayText += ` (${admissionNo})`;
    }
    if (props.showClass) {
        displayText += ` - ${className}`;
    }

    return {
        id: student.id,
        name: name,
        displayText: displayText,
        admissionNumber: admissionNo,
        className: className,
        student: student,
    };
};

// All student options formatted
const studentOptions = computed(() => {
    return (props.students || []).map(formatStudentOption).filter(Boolean);
});

// Initialize selected student from modelValue
const initializeSelection = () => {
    if (props.modelValue) {
        const found = studentOptions.value.find(opt => opt.id === props.modelValue);
        selectedStudent.value = found || null;
    } else {
        selectedStudent.value = null;
    }
};

// Search/filter students
const searchStudents = (event) => {
    const query = event.query?.toLowerCase().trim() || '';

    if (!query) {
        filteredStudents.value = studentOptions.value;
        return;
    }

    filteredStudents.value = studentOptions.value.filter(option => {
        return option.displayText.toLowerCase().includes(query) ||
               option.name.toLowerCase().includes(query) ||
               option.admissionNumber.toLowerCase().includes(query) ||
               option.className.toLowerCase().includes(query);
    });
};

// Handle selection change - watch selectedStudent and emit the ID
watch(selectedStudent, (newValue) => {
    const newId = newValue?.id || null;
    if (newId !== props.modelValue) {
        emit('update:modelValue', newId);
    }
});

// Watch for external modelValue changes
watch(() => props.modelValue, () => {
    initializeSelection();
});

// Watch for students array changes
watch(() => props.students, () => {
    initializeSelection();
}, { deep: true });

// Initialize on mount
initializeSelection();


</script>

<template>
    <PAutoComplete
        v-model="selectedStudent"
        :suggestions="filteredStudents"
        :placeholder="placeholder"
        :disabled="disabled"
        optionLabel="displayText"
        :dropdown="true"
        class="w-full"
        @complete="searchStudents"
    >
        <template #option="slotProps">
            <div class="flex items-center gap-3 py-1">
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-teal-100 text-xs font-semibold text-teal-700">
                    {{ slotProps.option.name.charAt(0).toUpperCase() }}
                </div>
                <div class="flex-1 overflow-hidden">
                    <div class="truncate text-sm font-medium text-slate-900">
                        {{ slotProps.option.name }}
                    </div>
                    <div class="flex gap-2 text-xs text-slate-500">
                        <span v-if="showAdmissionNumber && slotProps.option.admissionNumber" class="font-mono">
                            {{ slotProps.option.admissionNumber }}
                        </span>
                        <span v-if="showClass" class="text-slate-400">•</span>
                        <span v-if="showClass">{{ slotProps.option.className }}</span>
                    </div>
                </div>
            </div>
        </template>

        <template #empty>
            <div class="p-3 text-center text-sm text-slate-500">
                <i class="pi pi-search mb-2 text-2xl text-slate-300"></i>
                <div>No students found</div>
            </div>
        </template>
    </PAutoComplete>
</template>

