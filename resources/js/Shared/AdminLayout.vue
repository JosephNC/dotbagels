<template>
    <div>
        <portal-target name="dropdown" slim />
        <div class="md:flex md:flex-col">
            <div class="md:h-screen md:flex md:flex-col">
                <div class="md:flex md:flex-shrink-0">
                    <div class="bg-primary-800 md:flex-shrink-0 md:w-64 px-5 py-3  flex items-center justify-between md:justify-center">
                        <inertia-link href="/">
                            <logo color="fill-white" class="h-12" height="48" />
                        </inertia-link>
                        <dropdown class="md:hidden" placement="bottom-end">
                            <svg class="fill-white w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                            </svg>
                            <div slot="dropdown" class="mt-2 py-2 px-2 w-56 shadow-lg bg-white rounded">
                                <admin-menu />
                            </div>
                        </dropdown>
                    </div>

                    <div class="bg-white border-b w-full p-4 md:py-0 md:px-12 text-sm md:text-md flex justify-between items-center">
                        <div class="mt-1 mr-4">{{ $page.props.auth.user.name }}</div>
                        <dropdown placement="bottom-end">
                            <div class="flex items-center cursor-pointer select-none group">
                                <div class="text-gray-700 group-hover:text-primary-600 focus:text-primary-600 mr-1 whitespace-nowrap">
                                    <span>{{ $page.props.auth.user.first_name }}</span>
                                    <span class="hidden md:inline">{{ $page.props.auth.user.last_name }}</span>
                                </div>
                                <icon class="w-5 h-5 group-hover:fill-primary-600 fill-gray-700 focus:fill-primary-600" name="cheveron-down" />
                            </div>
                            <div slot="dropdown" class="mt-2 py-1 shadow-xl bg-white rounded text-sm">
                                <inertia-link class="block px-6 py-2 text-gray-500 hover:text-primary-800" :href="route('account')">My Account</inertia-link>
                                <!-- <inertia-link class="block px-6 py-2 text-gray-500 hover:text-primary-800" :href="route('users')">Manage Users</inertia-link> -->
                                <inertia-link class="block px-6 py-2 text-gray-500 hover:text-primary-800" :href="route('logout')" as="button" method="post">Logout</inertia-link>
                            </div>
                        </dropdown>
                    </div>
                </div>
                <div class="md:flex md:flex-grow md:overflow-hidden">
                    <admin-menu class="bg-white hidden md:block flex-shrink-0 w-64 py-5 overflow-y-auto border-r border-gray-200" />
                    <div class="md:flex-1 px-4 py-8 md:p-10 md:overflow-y-auto" scroll-region>
                        <div class="max-w-3xl mx-auto">
                            <flash-messages />
                            <slot />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Logo from '@/Shared/Logo'
import Dropdown from '@/Shared/Dropdown'
import AdminMenu from '@/Shared/AdminMenu'
import FlashMessages from '@/Shared/FlashMessages'

export default {
    components: {
        Dropdown,
        FlashMessages,
        Icon,
        Logo,
        AdminMenu,
    },
}
</script>
