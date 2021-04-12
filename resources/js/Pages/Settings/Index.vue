<template>
    <div>
        <div class="flex flex-col justify-between mt-2 mb-6">
            <h3 class="text-3xl leading-tight font-bold text-gray-900">Settings</h3>

            <!-- <div class="mt-5 shadow bg-gray-100 rounded-md">
                <form @submit.prevent="update">
                    <div class="p-8 -mr-6 -mb-8 flex flex-col flex-wrap">
                        <inline-text-input v-model="form.api_id" :error="form.errors.api_id" class="pr-6 pb-8 w-full lg:w-1/2" label="Square API ID" />
                        <inline-text-input v-model="form.api_token" :error="form.errors.api_token" class="pr-6 pb-8 w-full lg:w-1/2" label="Square API Token" />
                    </div>
                    <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
                        <loading-button :loading="form.processing" class="btn btn-primary" type="submit">Save</loading-button>
                    </div>
                </form>
            </div> -->

            <div class="mt-5 shadow-lg bg-gray-100 rounded-md border">
                <form @submit.prevent="update">
                    <div>
                        <div class="md:grid md:grid-cols-3">
                            <div class="md:col-span-1">
                                <div class="p-6">
                                    <h3 class="leading-0 text-lg font-bold">Square API</h3>
                                    <p class="mt-3 text-sm text-gray-600">Get the credentials from your <a target="_blank" href="https://developer.squareup.com/apps/" class="text-primary font-bold">squareup developer app</a>.</p>
                                </div>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <div class="sm:overflow-hidden">
                                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                                        <text-input v-model="form.square_app_id" :error="form.errors.square_app_id" class="px-3" label="App ID" />
                                        <text-input v-model="form.square_access_token" :error="form.errors.square_access_token" class="px-3" label="Access Token" type="password" />
                                        <checkbox-input :model="form.square_sandbox" :error="form.errors.square_sandbox" class="px-3" label="Sandbox Mode" @change="toggleMode" />
                                    </div>
                                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                        <div class="inline-flex justify-center">
                                            <loading-button :loading="form.processing" class="btn btn-primary" type="submit">Save</loading-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import CheckboxInput from '@/Shared/CheckboxInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
    metaInfo: { title: 'Settings' },
    components: {
        LoadingButton,
        TextInput,
        CheckboxInput,
    },
    layout: Layout,
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                square_app_id: this.$page.props.settings.square_app_id,
                square_access_token: this.$page.props.settings.square_access_token,
                square_sandbox: this.$page.props.settings.square_sandbox ?? true,
            }),
        }
    },
    methods: {
        update() {
            this.form.put(this.route('settings.update'))
        },
        toggleMode() {
            this.form.square_sandbox = ! this.form.square_sandbox
        }
    },
}
</script>
