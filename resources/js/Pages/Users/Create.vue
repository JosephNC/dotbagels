<template>
    <div>
        <h1 class="mb-8 font-bold text-3xl">
            <inertia-link class="text-primary-400 hover:text-primary-600" :href="route('users')">Users</inertia-link>
            <span class="text-primary-400 font-medium">/</span> Create
        </h1>
        <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
            <form @submit.prevent="store">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <text-input v-model="form.first_name" :error="form.errors.first_name" class="pr-6 pb-8 w-full lg:w-1/2" label="First Name" />
                    <text-input v-model="form.last_name" :error="form.errors.last_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Last Name" />
                    <text-input v-model="form.email" :error="form.errors.email" class="pr-6 pb-8 w-full lg:w-1/2" label="Email Address" />
                    <phone-input v-model="form.phone" :error="form.errors.phone" class="pr-6 pb-8 w-full lg:w-1/2" autocapitalize="off" label="Phone Number" :initialCountryCode="form.phone_country" @countryCode="phoneCountryCode" />
                    <file-input v-model="form.photo" :error="form.errors.photo" class="pr-6 pb-8 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
                    <select-input v-model="form.role" :error="form.errors.role" class="pr-6 pb-8 w-full lg:w-1/2" label="Role">
                        <option v-for="(value, key) in $page.props.settings.roles" :key="key" :value="key" v-text="value"></option>
                    </select-input>
                    <text-input v-model="form.password" :error="form.errors.password" class="pr-6 pb-8 w-full lg:w-1/2" type="text" autocomplete="new-password" label="Password" />
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn bg-indigo-500 text-center w-full py-3" @click="generatePassword">Generate Password</button>
                    </div>
                </div>
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
                    <checkbox-input :model="form.auto_verify" label="Auto verify user" labelClass="m-0" @change="changeAutoVerify" />
                    <loading-button :loading="form.processing" class="btn btn-primary ml-auto" type="submit">Create User</loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import PhoneInput from '@/Shared/PhoneInput'
import FileInput from '@/Shared/FileInput'
import SelectInput from '@/Shared/SelectInput'
import CheckboxInput from '@/Shared/CheckboxInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
    metaInfo: { title: 'Create User' },
    components: {
        FileInput,
        LoadingButton,
        SelectInput,
        CheckboxInput,
        TextInput,
        PhoneInput,
    },
    layout: Layout,
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                first_name: null,
                last_name: null,
                email: null,
                role: 'user',
                phone: null,
                phone_country: null,
                password: null,
                photo: null,
                auto_verify: false,
            }),
        }
    },
    methods: {
        store() {
            this.form.post(this.route('users.store'))
        },
        phoneCountryCode(countryCode) {
            this.form.phone_country = countryCode
        },
        changeAutoVerify() {
            this.form.auto_verify = ! this.form.auto_verify;
        }
    },
}
</script>
