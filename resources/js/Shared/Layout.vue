<template>
    <admin-layout v-if="$page.props.admin_layout">
        <slot />
    </admin-layout>
    <user-layout v-else>
        <slot />
    </user-layout>
</template>

<script>
import AdminLayout from '@/Shared/AdminLayout'
import UserLayout from '@/Shared/UserLayout'

export default {
    components: {
        AdminLayout,
        UserLayout,
    }
}
</script>
