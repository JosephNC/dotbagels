<template>
    <div class="p-6 bg-primary-50 min-h-screen flex justify-center items-center md:items-start">
        <div class="w-full max-w-md md:mt-16">
            <flash-messages />

            <form class="bg-white rounded-md shadow-lg overflow-hidden" @submit.prevent="register">
                <img src="/images/logo.png" class="block mx-auto w-20 max-w-min fill-white" height="50" />

                <div class="px-8 pb-4">
                    <h1 class="text-center font-bold text-xl">Create an account</h1>

                    <div class="mx-auto mt-2 mb-8 w-24 border-b-2" />

                    <text-input v-model="form.email" :error="form.errors.email" class="mt-3" placeholder="Email Address" type="email" autofocus autocapitalize="off" @input="form.clearErrors('email')" />

                    <text-input v-model="form.first_name" :error="form.errors.first_name" class="mt-3" placeholder="First Name" type="text" autocapitalize="off" @input="form.clearErrors('first_name')" />

                    <text-input v-model="form.last_name" :error="form.errors.last_name" class="mt-3" placeholder="Last Name" type="text" autocapitalize="off" @input="form.clearErrors('last_name')" />

                    <phone-input v-model="form.phone" :error="form.errors.phone" class="mt-3" autocapitalize="off" @input="form.clearErrors('phone')" @countryCode="phoneCountryCode" />

                    <text-input v-model="form.password" :error="form.errors.password" class="mt-3" placeholder="Password" type="password" @input="form.clearErrors('phone_number')" />

                    <checkbox-input :model="form.tos" :error="form.errors.tos" class="mt-5" label="By clicking register, you agree to our Terms of Service?" @change="changeTos" />
                </div>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-100 flex justify-between items-center">
                    <inertia-link class="text-sm" :href="route('login')">Log into account</inertia-link>

                    <loading-button :loading="form.processing" class="btn btn-primary" type="submit">Register</loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Logo from '@/Shared/Logo'
import TextInput from '@/Shared/TextInput'
import CheckboxInput from '@/Shared/CheckboxInput'
import PhoneInput from '@/Shared/PhoneInput'
import LoadingButton from '@/Shared/LoadingButton'
import FlashMessages from '@/Shared/FlashMessages'

export default {
    metaInfo: { title: 'Create an account' },
    components: {
        LoadingButton,
        Logo,
        TextInput,
        PhoneInput,
        CheckboxInput,
        FlashMessages,
    },
    data() {
        return {
            form: this.$inertia.form({
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                phone_country: '',
                password: '',
                tos: false,
            }),
        }
    },
    methods: {
        changeTos(checked) {
            this.form.tos = checked
            this.form.clearErrors('tos')
        },
        register() {
            // Clear all errors
            // this.form.clearErrors()

            // this.$page.props.flash.error = null
            // this.$page.props.errors = null

            // Send the form
            this.form
                .transform((data) => ({
                    ...data,
                    tos: data.tos ? 'on' : '',
                }))
                .post(this.route('register'))
        },
        phoneCountryCode( countryCode ) {
            this.form.phone_country = countryCode
        }
    },
}
</script>
