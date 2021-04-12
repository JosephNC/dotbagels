<template>
    <ul class="text-base">
        <li class="inline-block list-none">
            <inertia-link class="flex items-center group px-8" :href="route('dashboard')">
                <div :class="[ 'font-semibold', isUrl('') ? 'text-primary' : 'text-gray-500 group-hover:text-primary']">Dashboard</div>
            </inertia-link>
        </li>
        <li class="inline-block list-none">
            <inertia-link class="flex items-center group px-8" :href="route('activities')">
                <div :class="[ 'font-semibold', isUrl('activities') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' ]">Activities</div>
            </inertia-link>
        </li>
        <li class="inline-block list-none">
            <inertia-link class="flex items-center group px-8" :href="route('rewards')">
                <div :class="[ 'font-semibold', isUrl('rewards') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' ]">Rewards</div>
            </inertia-link>
        </li>
        <li class="inline-block list-none">
            <inertia-link class="flex items-center group px-8" :href="route('reports')">
                <div :class="[ 'font-semibold', isUrl('reports') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' ]">Reports</div>
            </inertia-link>
        </li>
    </ul>
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

            return urls[0] === '' ? currentUrl === '' : urls.filter(url => currentUrl.startsWith(url)).length
        },
    },
}
</script>
