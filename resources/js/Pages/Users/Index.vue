<template>
    <div>
        <h1 class="mb-8 font-bold text-3xl">Users</h1>

        <div class="mb-6 flex justify-between items-center">
            <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
                <label class="block text-gray-700">Role:</label>
                <select v-model="form.role" class="mt-1 w-full form-select">
                    <option value="all">All</option>
                    <option v-for="(value, key) in $page.props.settings.roles" :key="key" :value="key" v-text="value"></option>
                </select>
                <label class="mt-4 block text-gray-700">Trashed:</label>
                <select v-model="form.trashed" class="mt-1 w-full form-select">
                    <option value="only">Only Trashed</option>
                    <option value="with">With Trashed</option>
                    <option value="without">Without Trashed</option>
                </select>
            </search-filter>
            <inertia-link class="btn btn-primary" as="button" :href="route('users.create')">Create new user</inertia-link>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th class="px-6 py-5">User</th>
                    <th class="px-6 py-5">Role</th>
                    <th class="px-6 py-5" colspan="2">Created</th>
                </tr>
                <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm font-medium" :class="{ 'line-through bg-orange-100 opacity-50': user.deleted_at }">
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center focus:text-primary-500" :href="route('users.edit', user.id)">
                            <img v-if="user.photo" class="block w-10 h-10 rounded-full mr-3 -my-2 border-2 border-dotted p-2px border-accent-300" :src="user.photo" />
                            <div class="flex flex-col leading-tight">
                                <span class="">{{ user.name }}</span>
                                <span class="text-xs">{{ user.email }} <em v-if="! user.verified" class="text-gray">(Unverified)</em> </span>
                            </div>
                        </inertia-link>
                    </td>
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center" :href="route('users.edit', user.id)" tabindex="-1">
                            {{ user.role }}
                        </inertia-link>
                    </td>
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center" :href="route('users.edit', user.id)" tabindex="-1">
                            {{ user.created_at }}
                        </inertia-link>
                    </td>
                    <td class="border-t w-px">
                        <inertia-link class="px-6 flex items-center" :href="route('users.edit', user.id)" tabindex="-1">
                            <icon name="cheveron-right" class="block w-4 h-4 fill-gray-400" />
                        </inertia-link>
                    </td>
                </tr>
                <tr v-if="users.data.length === 0">
                    <td class="border-t px-6 py-5" colspan="4">No users found.</td>
                </tr>
            </table>
        </div>
        <pagination class="mt-6" :links="users.links" />
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import SearchFilter from '@/Shared/SearchFilter'

export default {
    metaInfo: { title: 'Users' },
    components: {
        Icon,
        Pagination,
        SearchFilter,
    },
    layout: Layout,
    props: {
        users: Object,
        filters: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search,
                role: this.filters.role ?? 'all',
                trashed: this.filters.trashed ?? 'with',
            },
        }
    },
    watch: {
        form: {
            handler: throttle(function () {
                let query = pickBy(this.form)
                this.$inertia.replace(this.route('users', Object.keys(query).length ? query : { remember: 'forget' }))
            }, 200),
            deep: true,
        },
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
    },
}
</script>
