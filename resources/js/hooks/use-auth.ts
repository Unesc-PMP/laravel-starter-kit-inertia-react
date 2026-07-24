import { usePage } from "@inertiajs/react";

export function useAuth() {
    return usePage().props.auth.user;
}

export function useAuthRoles() {
    return usePage().props.auth.roles;
}

export function useAuthPermissions() {
    return usePage().props.auth.permissions;
}

export function useAuthImpersonating() {
    return usePage().props.auth.impersonating;
}