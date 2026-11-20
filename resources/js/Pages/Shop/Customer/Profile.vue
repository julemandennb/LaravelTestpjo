
<script setup>
import { Head, Link, useForm, usePage,router } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';

defineOptions({
    layout: ShopLayout,
});

const props = defineProps({
    customer: Object,
});

const page = usePage();

const profileForm = useForm({
    name: props.customer.name ?? '',
    email: props.customer.email ?? '',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

function updateProfile() {
    profileForm.patch(route('customer.profile.update'), {
        preserveScroll: true,
    });
}

function updatePassword() {
    passwordForm.put(route('customer.password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
        },
    });
}

function logout() {
    router.post(route('logout'));
}
</script>

<template>
    <Head title="My Profile" />

    <div class="min-h-screen bg-gray-50 px-5 py-12">
        <div class="mx-auto max-w-4xl">

            <!-- Header -->
            <div class="mb-8">
                <p class="font-semibold text-orange-500">
                    My Account
                </p>

                <h1 class="mt-2 text-3xl font-black text-gray-900">
                    My Profile
                </h1>

                <p class="mt-2 text-gray-500">
                    Manage your personal information and password.
                </p>
            </div>

            <!-- Navigation -->
            <div class="mb-8 flex gap-3">
                <Link
                    :href="route('customer.dashboard')"
                    class="rounded-xl border border-gray-200 bg-white px-5 py-3 font-semibold text-gray-700 hover:border-orange-500"
                >
                    ← Dashboard
                </Link>

                <Link
                    :href="route('customer.profile.edit')"
                    class="rounded-xl bg-orange-500 px-5 py-3 font-semibold text-white"
                >
                    My Profile
                </Link>
                <button
                    @click="logout"
                    class="rounded-xl border border-red-200 bg-white px-5 py-3 font-semibold text-red-600 transition hover:bg-red-50"
                >
                    Log out
                </button>
            </div>

            <!-- Success Message -->
            <div
                v-if="page.props.flash?.success"
                class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-700"
            >
                {{ page.props.flash.success }}
            </div>

            <!-- Profile Form -->
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:p-8">

                <h2 class="text-xl font-black text-gray-900">
                    Personal Information
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    Update your name and email address.
                </p>

                <form @submit.prevent="updateProfile" class="mt-8 space-y-6">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Full Name
                        </label>

                        <input
                            v-model="profileForm.name"
                            type="text"
                            required
                            class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-orange-500 focus:ring-orange-500"
                        />

                        <p
                            v-if="profileForm.errors.name"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ profileForm.errors.name }}
                        </p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Email Address
                        </label>

                        <input
                            v-model="profileForm.email"
                            type="email"
                            required
                            class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-orange-500 focus:ring-orange-500"
                        />

                        <p
                            v-if="profileForm.errors.email"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ profileForm.errors.email }}
                        </p>
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="profileForm.processing"
                            class="rounded-xl bg-orange-500 px-7 py-3 font-bold text-white transition hover:bg-orange-600 disabled:opacity-50"
                        >
                            {{ profileForm.processing ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>

                </form>
            </div>

            <!-- Password Form -->
            <div class="mt-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:p-8">

                <h2 class="text-xl font-black text-gray-900">
                    Update Password
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    Choose a strong password to protect your account.
                </p>

                <form @submit.prevent="updatePassword" class="mt-8 space-y-6">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Current Password
                        </label>

                        <input
                            v-model="passwordForm.current_password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-orange-500 focus:ring-orange-500"
                        />

                        <p
                            v-if="passwordForm.errors.current_password"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ passwordForm.errors.current_password }}
                        </p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            New Password
                        </label>

                        <input
                            v-model="passwordForm.password"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-orange-500 focus:ring-orange-500"
                        />

                        <p
                            v-if="passwordForm.errors.password"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ passwordForm.errors.password }}
                        </p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Confirm New Password
                        </label>

                        <input
                            v-model="passwordForm.password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-orange-500 focus:ring-orange-500"
                        />
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="passwordForm.processing"
                            class="rounded-xl bg-gray-900 px-7 py-3 font-bold text-white transition hover:bg-orange-500 disabled:opacity-50"
                        >
                            {{ passwordForm.processing ? 'Updating...' : 'Update Password' }}
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</template>
