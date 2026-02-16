import { useToast as usePrimeToast } from 'primevue/usetoast';

export function useToast() {
    const toast = usePrimeToast();

    const showSuccess = (message, detail = null) => {
        toast.add({
            severity: 'success',
            summary: message || 'Success',
            detail: detail,
            life: 3000,
        });
    };

    const showError = (message, detail = null) => {
        toast.add({
            severity: 'error',
            summary: message || 'Error',
            detail: detail,
            life: 5000,
        });
    };

    const showInfo = (message, detail = null) => {
        toast.add({
            severity: 'info',
            summary: message || 'Info',
            detail: detail,
            life: 3000,
        });
    };

    const showWarn = (message, detail = null) => {
        toast.add({
            severity: 'warn',
            summary: message || 'Warning',
            detail: detail,
            life: 4000,
        });
    };

    return {
        showSuccess,
        showError,
        showInfo,
        showWarn,
        toast,
    };
}
