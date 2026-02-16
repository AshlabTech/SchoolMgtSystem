import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function usePermissions() {
    const page = usePage();
    const roles = computed(() => page.props.auth?.roles ?? []);
    const permissions = computed(() => page.props.auth?.permissions ?? []);

    const can = (permission) => permissions.value.includes(permission);
    const hasRole = (role) => roles.value.includes(role);

    return { roles, permissions, can, hasRole };
}
