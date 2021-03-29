<template>
    <ul class="">
        <li class="inline-block list-none mr-8">
            <inertia-link class="flex items-center group" :href="route('dashboard')">
                <!-- <icon name="dashboard" class="w-4 h-4 mr-2" :class="isUrl('') ? 'fill-primary' : 'fill-gray-500 group-hover:fill-primary'" /> -->
                <div :class="['', isUrl('') ? 'text-primary' : 'text-gray-500 group-hover:text-primary']">Dashboard</div>
            </inertia-link>
        </li>
        <li class="inline-block list-none mr-8">
            <inertia-link class="flex items-center group" :href="route('organizations')">
                <!-- <icon name="office" class="w-4 h-4 mr-2" :class="isUrl('organizations') ? 'fill-primary' : 'fill-gray-500 group-hover:fill-primary'" /> -->
                <div :class="['', isUrl('organizations') ? 'text-primary' : 'text-gray-500 group-hover:text-primary']">Activities</div>
            </inertia-link>
        </li>
        <li class="inline-block list-none mr-8">
            <inertia-link class="flex items-center group" :href="route('contacts')">
                <!-- <icon name="users" class="w-4 h-4 mr-2" :class="isUrl('contacts') ? 'fill-primary' : 'fill-gray-500 group-hover:fill-primary'" /> -->
                <div :class="['', isUrl('contacts') ? 'text-primary' : 'text-gray-500 group-hover:text-primary']">Rewards</div>
            </inertia-link>
        </li>
        <li class="inline-block list-none mr-8">
            <inertia-link class="flex items-center group" :href="route('reports')">
                <!-- <icon name="printer" class="w-4 h-4 mr-2" :class="isUrl('reports') ? 'fill-primary' : 'fill-gray-500 group-hover:fill-primary'" /> -->
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
