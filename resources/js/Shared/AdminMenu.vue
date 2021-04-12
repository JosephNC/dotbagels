<template>
    <div>
        <div class="mb-1">
            <inertia-link :href="route('dashboard')" class="flex items-center group px-7 py-3 hover:bg-primary-50" :class="{ 'bg-primary-50': isUrl('') }">
                <icon :class="[ isUrl('') ? 'fill-primary' : 'fill-gray-500 group-hover:fill-primary' ]" class="w-5 h-5 mr-7" name="dashboard"/>
                <div :class="[ 'font-semibold text-sm', isUrl('') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' ]">Dashboard</div>
            </inertia-link>
        </div>

        <div class="mb-1">
            <inertia-link :href="route('rewards.input')" class="flex items-center group px-7 py-3 hover:bg-primary-50" :class="{ 'bg-primary-50': isUrl('rewards.input') }">
                <icon :class="[ isUrl('rewards.input') ? 'fill-primary' : 'fill-gray-500 group-hover:fill-primary' ]" class="w-5 h-5 mr-7" name="ticket"/>
                <div :class="[ 'font-semibold text-sm', isUrl('rewards.input') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' ]">Redeem Code</div>
            </inertia-link>
        </div>

        <div v-if="$page.props.admin_layout" class="mb-1">
            <inertia-link :href="route('loyalty')" class="flex items-center group px-7 py-3 hover:bg-primary-50" :class="{ 'bg-primary-50': isUrl('loyalty') }">
                <icon :class="[ isUrl('loyalty') ? 'fill-primary' : 'fill-gray-500 group-hover:fill-primary' ]" class="w-5 h-5 mr-7" name="gift"/>
                <div :class="[ 'font-semibold text-sm', isUrl('loyalty') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' ]">Loyalty</div>
            </inertia-link>
        </div>

        <div v-if="$page.props.admin_layout" class="mb-1">
            <inertia-link :href="route('items')" class="flex items-center group px-7 py-3 hover:bg-primary-50" :class="{ 'bg-primary-50': isUrl('items') }">
                <icon :class="[ isUrl('items') ? 'fill-primary' : 'fill-gray-500 group-hover:fill-primary' ]" class="w-5 h-5 mr-7" name="items"/>
                <div :class="[ 'font-semibold text-sm', isUrl('items') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' ]">Items</div>
            </inertia-link>
        </div>
        <div v-if="$page.props.admin_layout" class="mb-1">
            <inertia-link :href="route('orders')" class="flex items-center group px-7 py-3 hover:bg-primary-50" :class="{ 'bg-primary-50': isUrl('orders') }">
                <icon :class="[ isUrl('orders') ? 'fill-primary' : 'fill-gray-500 group-hover:fill-primary' ]" class="w-5 h-5 mr-7" name="shopping-cart"/>
                <div :class="[ 'font-semibold text-sm', isUrl('orders') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' ]">Orders</div>
            </inertia-link>
        </div>

        <div v-if="$page.props.admin_layout" class="mb-1">
            <inertia-link :href="route('users')" class="flex items-center group px-7 py-3 hover:bg-primary-50" :class="{ 'bg-primary-50': isUrl('users') }">
                <icon :class="[ isUrl('users') ? 'fill-primary' : 'fill-gray-500 group-hover:fill-primary' ]" class="w-5 h-5 mr-7" name="users"/>
                <div :class="[ 'font-semibold text-sm', isUrl('users') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' ]">Users</div>
            </inertia-link>
        </div>

        <div v-if="$page.props.admin_layout" class="mb-1">
            <inertia-link :href="route('settings')" class="flex items-center group px-7 py-3 hover:bg-primary-50" :class="{ 'bg-primary-50': isUrl('settings') }">
                <icon :class="[ isUrl('settings') ? 'fill-primary' : 'fill-gray-500 group-hover:fill-primary' ]" class="w-5 h-5 mr-7" name="settings"/>
                <div :class="[ 'font-semibold text-sm', isUrl('settings') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' ]">Settings</div>
            </inertia-link>
        </div>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'

export default {
    components: {
        Icon,
    },
    methods: {
        isUrl(...urls) {
            let currentUrl = this.$page.url.substr(1)

            return (urls[0] === '') ? currentUrl === '' : urls.filter(url => currentUrl.startsWith(url.replace('.', '/'))).length
        },
    },
}
</script>
