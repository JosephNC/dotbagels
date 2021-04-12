<template>
    <div class="h-screen">
        <portal-target name="dropdown" slim />

        <div class="w-full bg-white shadow flex flex-shrink-0 justify-between">
            <!-- <div class="w-36 flex items-center justify-between"> -->
            <div class="bg-primary-800 md:flex-shrink-0 md:w-80 px-5 py-3 flex items-center justify-between md:justify-center">
                <inertia-link href="/"><logo class="h-14" height="56" /></inertia-link>
            </div>

            <div class="w-full px-8 text-sm flex justify-between items-center">
                <!-- <div class="mr-4">{{ $page.props.auth.user.account.name }}</div> -->

                <user-menu class="w-full" />

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

        <div class="flex-1 max-w-3xl m-auto py-8 md:overflow-y-auto px-4 md:px-0" scroll-region>
            <flash-messages />
            <slot />
        </div>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Logo from '@/Shared/Logo'
import Dropdown from '@/Shared/Dropdown'
import UserMenu from '@/Shared/UserMenu'
import FlashMessages from '@/Shared/FlashMessages'

export default {
    components: {
        Dropdown,
        FlashMessages,
        Icon,
        Logo,
        UserMenu,
    },
}
</script>
