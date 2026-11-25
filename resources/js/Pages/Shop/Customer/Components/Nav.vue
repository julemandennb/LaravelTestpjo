<script setup>
    import { Link ,router} from '@inertiajs/vue3';



   const props = defineProps({
    menuOn: {
        type: String,
        default: 'Dashboard',
    },
});
    function logout() {
        router.post(route('logout'));
    }

function menuOnColor(menu) {
    return props.menuOn === menu
        ? 'border-orange-500 bg-orange-500 text-white'
        : 'border-gray-200 bg-white text-gray-700 hover:border-orange-500 hover:bg-orange-50';
}

</script>


<template>
    <div class="mb-8 flex flex-wrap gap-3">
        <Link
            :href="route('customer.dashboard')"
            class="rounded-xl border px-5 py-3 font-semibold transition"
            :class="menuOnColor('Dashboard')"
        >
            Dashboard
        </Link>

        <Link
            :href="route('customer.profile.edit')"
            class="rounded-xl border px-5 py-3 font-semibold transition"
            :class="menuOnColor('My Profile')"
        >
            My Profile
        </Link>

        <Link
            :href="route('customer.orders.index')"
            class="rounded-xl border px-5 py-3 font-semibold transition"
            :class="menuOnColor('My Orders')"
        >
            My Orders
        </Link>

        <button
            @click="logout"
            type="button"
            class="rounded-xl border border-red-200 bg-white px-5 py-3 font-semibold text-red-600 transition hover:bg-red-50"
        >
            Log out
        </button>
    </div>
</template>
