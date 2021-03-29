<template>
    <div class="p-6 bg-primary-50 min-h-screen flex justify-center items-center md:items-start">
        <div class="w-full max-w-md md:mt-16">
            <form class="bg-white rounded-md shadow-lg overflow-hidden" @submit.prevent="sendLink">
                <img src="/images/logo.png" class="block mx-auto w-20 max-w-min fill-white" height="50" />

                <div class="px-8 pb-4">
                    <h1 class="text-center font-bold text-xl">Change Password</h1>

                    <div class="mx-auto mt-2 mb-8 w-24 border-b-2" />

                    <input v-model="form.token" type="hidden" readonly autocapitalize="off" />
                    <text-input v-model="form.email" :error="form.errors.email" class="mt-3" type="email" readonly autocapitalize="off" />
                    <text-input v-model="form.password" :error="form.errors.password" class="mt-3" placeholder="Password" type="password" autofocus autocapitalize="off" />
                    <text-input v-model="form.password_confirmation" :error="form.errors.password_confirmation" class="mt-3" placeholder="Retype Password" type="password" autocapitalize="off" />

                </div>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-100 flex justify-center items-center">
                    <loading-button :loading="form.processing" class="btn btn-primary" type="submit">Reset password</loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Logo from '@/Shared/Logo'
import TextInput from '@/Shared/TextInput'
import CheckboxInput from '@/Shared/CheckboxInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
    metaInfo: { title: 'Reset Password' },
    components: {
        LoadingButton,
        Logo,
        TextInput,
        CheckboxInput,
    },
    data() {
        return {
            form: this.$inertia.form({
                email: this.$page.props.email,
                token: this.$page.props.token,
                password: '',
                password_confirmation: '',
            }),
        }
    },
    methods: {
        sendLink() {
            this.form
                // .transform(data => ({
                //     ...data,
                //     remember: data.remember ? 'on' : '',
                // }))
                .post(this.route('password.update'))
        },
    },
}
</script>
