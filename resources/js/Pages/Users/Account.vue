<template>
    <div>
        <div class="mb-8 flex justify-start max-w-3xl">
            <h1 class="font-bold text-3xl">Account Settings</h1>
            <img v-if="$page.props.auth.user.photo" class="block w-8 h-8 rounded-full ml-4" :src="$page.props.auth.user.photo" />
        </div>

        <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
            <form @submit.prevent="update">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <text-input v-model="form.first_name" :error="form.errors.first_name" class="pr-6 pb-8 w-full lg:w-1/2" label="First Name" />
                    <text-input v-model="form.last_name" :error="form.errors.last_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Last Name" />
                    <text-input v-model="form.email" :error="form.errors.email" class="pr-6 pb-8 w-full lg:w-1/2" label="Email Address" readonly />
                    <phone-input v-model="form.phone" :error="form.errors.phone" class="pr-6 pb-8 w-full lg:w-1/2" autocapitalize="off" label="Phone Number" :initialCountryCode="form.phone_country" @countryCode="phoneCountryCode" />
                    <file-input v-model="form.photo" :error="form.errors.photo" class="pr-6 pb-8 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
                    <text-input v-model="form.password" :error="form.errors.password" class="pr-6 pb-8 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Password" />
                </div>
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
                    <loading-button :loading="form.processing" class="btn btn-primary ml-auto" type="submit">Update</loading-button>
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
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
    metaInfo() {
        return {
            title: `${this.form.first_name} ${this.form.last_name}`,
        }
    },
    components: {
        FileInput,
        LoadingButton,
        SelectInput,
        TextInput,
        PhoneInput,
        TrashedMessage,
    },
    layout: Layout,
    props: {
        user: Object,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                _method: 'put',
                first_name: this.$page.props.auth.user.first_name,
                last_name: this.$page.props.auth.user.last_name,
                email: this.$page.props.auth.user.email,
                phone: this.$page.props.auth.user.phone,
                phone_country: this.$page.props.auth.user.phone_country,
                password: null,
                photo: null,
            }),
        }
    },
    methods: {
        update() {
            this.form.post(this.route('account.update'), {
                onSuccess: () => this.form.reset('password', 'photo'),
            })
        },
        phoneCountryCode( countryCode ) {
            this.form.phone_country = countryCode
        }
    },
}
</script>
