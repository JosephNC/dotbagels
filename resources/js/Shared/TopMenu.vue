<template>
    <ul class="">
        <li class="inline-block list-none mr-8">
            <inertia-link class="flex items-center group" :href="route('dashboard')">
                <div :class="['', isUrl('') ? 'text-primary' : 'text-gray-500 group-hover:text-primary']">Dashboard</div>
            </inertia-link>
        </li>
        <li class="inline-block list-none mr-8">
            <inertia-link class="flex items-center group" :href="route('activities')">
                <div :class="['', isUrl('activities') ? 'text-primary' : 'text-gray-500 group-hover:text-primary']">Activities</div>
            </inertia-link>
        </li>
        <li class="inline-block list-none mr-8">
            <inertia-link class="flex items-center group" :href="route('rewards')">
                <div :class="['', isUrl('rewards') ? 'text-primary' : 'text-gray-500 group-hover:text-primary']">Rewards</div>
            </inertia-link>
        </li>
        <li class="inline-block list-none mr-8">
            <inertia-link class="flex items-center group" :href="route('reports')">
                <div :class="['', isUrl('reports') ? 'text-primary' : 'text-gray-500 group-hover:text-primary']">Reports</div>
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
