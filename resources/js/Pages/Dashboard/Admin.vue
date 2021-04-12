<template>
    <div class="flex flex-col justify-between mb-6">
        <h3 class="text-3xl leading-snug font-extrabold text-gray-900">Welcome back {{ $page.props.auth.user.first_name }},</h3>
        <h3 class="text-2xl leading-snug font-extrabold text-gray-900">Here is your overview & analytics</h3>

        <h4 class="text-xl leading-tight font-bold text-gray-900 mt-10 mb-3">Today</h4>

        <div class="flex mb-2">
            <div class="bg-white flex flex-col justify-between px-8 py-6 shadow border border-gray-200 rounded-lg w-full mr-1">
                <h3 class="text-lg leading-tight font-medium mb-1">Users</h3>
                <inertia-link v-if="! isEmpty(dashboard.total_users_today)" :href="route('users')" class="flex items-center group mt-1">
                    <div class="text-2xl font-bold text-accent group-hover:text-accent-300">{{ dashboard.total_users_today }} newly registered</div>
                    <icon class="w-5 h-5 ml-7 fill-accent group-hover:fill-accent-300" name="arrow-thin-right" />
                </inertia-link>
                <p v-if="isEmpty(dashboard.total_users_today)" class="text-2xl text-gray-400 font-bold mt-1">No users yet today</p>
            </div>

            <div class="bg-white flex flex-col justify-between px-8 py-6 shadow border border-gray-200 rounded-lg w-full ml-1">
                <h3 class="text-lg leading-tight font-medium mb-1">Agents</h3>
                <inertia-link v-if="! isEmpty(dashboard.total_agents_today)" :href="route('users')" class="flex items-center group mt-1">
                    <div class="text-2xl font-bold text-accent group-hover:text-accent-300">{{ dashboard.total_agents_today }} newly registered</div>
                    <icon class="w-5 h-5 ml-7 fill-accent group-hover:fill-accent-300" name="arrow-thin-right" />
                </inertia-link>
                <p v-if="isEmpty(dashboard.total_agents_today)" class="text-2xl text-gray-400 font-bold mt-1">No agents yet today</p>
            </div>

            <!-- <div class="bg-white flex flex-col justify-between px-8 py-6 shadow border border-gray-200 rounded-lg w-full ml-1">
                <h3 class="text-lg leading-tight font-medium mb-1">Activities</h3>
                <p class="text-2xl text-gray-400 font-bold mt-1">No activities yet today</p>
            </div> -->
        </div>

        <div class="bg-white flex flex-col justify-between px-8 py-6 shadow border border-gray-200 rounded-lg w-full">
            <h3 class="text-lg leading-tight font-medium mb-1">Top rewards</h3>
            <p class="text-2xl text-gray-400 font-bold mt-1">No rewards redeemed today</p>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import Icon from '@/Shared/Icon'

export default {
    metaInfo: { title: 'Dashboard' },
    layout: Layout,
    components: {
        Icon,
    },
    props: {
        dashboard: Object,
    },
    data() {
        return {
            // total_users_today: this.dashboard.total_users_today,
        }
    },
    methods: {},
}
</script>
