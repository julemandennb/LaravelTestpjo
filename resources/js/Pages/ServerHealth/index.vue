<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    status: String,
    healthy: Boolean,
    checks: Object,
});

const statusClass = (value) => ({
    OK: 'bg-emerald-100 text-emerald-800',
    WARNING: 'bg-amber-100 text-amber-800',
    FAILED: 'bg-rose-100 text-rose-800',
}[value] || 'bg-slate-100 text-slate-800');
</script>

<template>
    <Head title="Server Health" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Server Health
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <div class="mb-6 flex items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">Application health</h1>
                            <p class="mt-1 text-sm text-gray-500">Current status of Laravel and its required services.</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-sm font-semibold" :class="statusClass(status)">
                            {{ status }}
                        </span>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div v-for="(check, name) in checks" :key="name" class="rounded border border-gray-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <h2 class="font-medium capitalize text-gray-900">{{ name.replaceAll('_', ' ') }}</h2>
                                <span class="rounded-full px-2 py-1 text-xs font-semibold" :class="statusClass(check.status)">
                                    {{ check.status }}
                                </span>
                            </div>
                            <p class="mt-3 text-sm text-gray-600">{{ check.message }}</p>
                            <p v-if="check.version" class="mt-2 text-xs text-gray-500">Version {{ check.version }}</p>
                            <p v-if="check.driver" class="mt-2 text-xs text-gray-500">Driver: {{ check.driver }} · Worker: {{ check.worker }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
