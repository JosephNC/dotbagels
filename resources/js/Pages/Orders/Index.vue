<template>
    <div>
        <h1 class="mb-8 font-bold text-3xl">Orders</h1>

        <div class="mb-6 flex justify-between items-center">
            <search-filter v-model="form.search" :dropdown="false" class="w-full max-w-md mr-4" @reset="reset" />
            <form @submit.prevent="sync"><loading-button :loading="syncForm.processing" class="btn btn-indigo" type="submit">{{ syncForm.processing ? 'Synchronizing' : 'Synchronize Data' }}</loading-button></form>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <tr class="font-bold">
                    <th class="px-6 py-5 text-left">Order</th>
                    <th class="px-6 py-5 text-center">Status</th>
                    <th class="px-6 py-5 text-right w-32">Price</th>
                </tr>
                <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm font-medium">
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex flex-col justify-center focus:text-primary-500" href="#">
                            <p class="">{{ order.name }}</p>
                            <em class="text-gray-400 text-xs font-bold">{{ order.id }}</em>
                        </inertia-link>
                    </td>
                    <td class="border-t text-center">
                        <inertia-link class="px-6 py-4 focus:text-primary-500" href="#">
                            {{ order.state }}
                        </inertia-link>
                    </td>
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center justify-end" href="#" tabindex="-1">
                            {{ order.price }}
                        </inertia-link>
                    </td>
                </tr>
                <tr v-if="orders.data.length === 0">
                    <td class="border-t px-6 py-5" colspan="4">No orders found.</td>
                </tr>
            </table>
        </div>
        <pagination class="mt-6" :links="orders.links" />
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
import LoadingButton from '@/Shared/LoadingButton'

export default {
    metaInfo: { title: 'Orders' },
    components: {
        Icon,
        Pagination,
        SearchFilter,
        LoadingButton,
    },
    layout: Layout,
    props: {
        orders: Object,
        filters: Object,
        settings: Object,
    },
    data() {
        return {
            syncForm: this.$inertia.form(),
            form: {
                search: this.filters.search,
            },
        }
    },
    watch: {
        form: {
            handler: throttle(function () {
                let query = pickBy(this.form)
                this.$inertia.replace(this.route('orders', Object.keys(query).length ? query : {}))
            }, 200),
            deep: true,
        },
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
        sync() {
            this.syncForm.put(this.route('square.sync'), {
                onFinish: async () => {
                    await this.sleep(5000)

                    this.$inertia.reload()
                }
            })
        },
        getLink(item) {
            const host = this.settings.square_sandbox ? 'squareupsandbox' : 'squareup'
            return `https://${host}.com/dashboard/items/library/${item.id}`
        },
    },
}
</script>
