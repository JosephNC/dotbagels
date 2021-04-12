<template>
    <div>
        <div class="flex justify-between mt-2 mb-6">
            <div class="pb-2 border-b w-full mr-10">
                <h3 class="text-2xl leading-tight font-semibold text-gray-900">Dashboard</h3>
                <p class="text-sm text-gray-500">Overview</p>
            </div>
            <div class="bg-white shadow rounded-lg w-56">
                <div class="px-4 py-2 flex justify-between content-center">
                    <h2 class="text-4xl font-bold text-primary mr-2 leadning-tight">{{ $page.props.auth.user.points }}</h2>
                    <p class="text-sm text-gray-500 ml-2 leading-tight">Points accumulated</p>
                </div>
            </div>

            <!-- <div class="ml-1 w-1/2">
                    <div class="px-4 py-5 sm:px-6">
                        <h2 class="text-lg leading-6 font-semibold">Upcoming Rewards</h2>
                        <p class="text-sm text-gray-500">Spend at least $10 to earn 1 point toward a free item.</p>
                    </div>
                </div> -->
        </div>

        <div class="bg-white shadow overflow-hidden rounded-lg p-5">
            <div class="mb-6">
                <h3 class="leading-6 font-medium text-gray-900">Upcoming Rewards</h3>
                <!-- <p class="text-sm text-gray-500">Spend at least $10 to earn 1 point toward a free item.</p> -->
            </div>

            <div class="border-gray-200">
                <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <ul v-if="upcomingRewards.length" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                        <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                            <div class="w-0 flex-1 flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-2 flex-1 w-0 truncate text-lg font-semibold">One free slice</span>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span href="#" class="font-medium text-primary hover:text-primary-600">20 points</span>
                            </div>
                        </li>
                        <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                            <div class="w-0 flex-1 flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-2 flex-1 w-0 truncate text-lg font-semibold">One free pie</span>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span href="#" class="font-medium text-primary hover:text-primary-600">30 points</span>
                            </div>
                        </li>
                    </ul>
                    <p v-else class="text-center mt-20 mb-5">No upcoming rewards.</p>
                </div>
            </div>
        </div>

        <div class="my-8"></div>

        <div class="bg-white shadow overflow-hidden rounded-lg p-5">
            <div class="flex justify-between mb-6">
                <h3 class="leading-6 font-medium text-gray-900">Recent Activities</h3>
                <inertia-link class="flex items-center group" :href="route('activities')">
                    <span v-if="activities.length" class="font-medium text-primary hover:text-primary">See all</span>
                </inertia-link>
            </div>

            <div class="border-gray-200">
                <div class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <ul v-if="recentActivities.length" class="divide-y divide-gray-200">
                        <li class="flex items-center justify-between text-sm py-2">
                            <div class="w-0 flex-1 flex items-center">
                                <div class="flex flex-col items-center font-semibold text-gray-400 leading-3 mr-5">
                                    <span class="text-xs block">JUN</span>
                                    <span class="text-medium block">12</span>
                                </div>

                                <div class="flex flex-col items-center">
                                    <span class="block truncate font-semibold">Signed up for the Mailing List and Earned 2 Points.</span>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span href="#" class="font-medium text-primary hover:text-primary-600">+2 Points</span>
                            </div>
                        </li>

                        <li class="flex items-center justify-between text-sm py-2">
                            <div class="w-0 flex-1 flex items-center">
                                <div class="flex flex-col items-center font-semibold text-gray-400 leading-3 mr-5">
                                    <span class="text-xs block">JUN</span>
                                    <span class="text-medium block">01</span>
                                </div>

                                <div class="flex flex-col items-center">
                                    <span class="block truncate font-semibold">Earned 5 Points at DJ NY</span>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span href="#" class="font-medium text-primary hover:text-primary-600">+5 Points</span>
                            </div>
                        </li>
                    </ul>
                    <p v-else class="text-center mt-20 mb-5">No activities.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'

export default {
    metaInfo: { title: 'Dashboard' },
    layout: Layout,
    data() {
        return {
            upcomingRewards: [],
            activities: [],
            recentActivities: [],
        }
    },
    created() {},
}
</script>
