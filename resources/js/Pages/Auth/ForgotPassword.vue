<template>
    <div class="p-6 bg-primary-50 min-h-screen flex justify-center items-center md:items-start">
        <div class="w-full max-w-md md:mt-16">
            <flash-messages />
            <!-- <logo class="block mx-auto w-full max-w-xs fill-white" height="50" /> -->

            <form class="bg-white rounded-md shadow-lg overflow-hidden" @submit.prevent="sendLink">
                <img src="/images/logo.png" class="block mx-auto w-20 max-w-min fill-white" height="50" />

                <div class="px-8 pb-4">
                    <h1 class="text-center font-bold text-xl">Reset Password</h1>

                    <div class="mx-auto mt-2 mb-8 w-24 border-b-2" />

                    <text-input v-model="form.email" :error="form.errors.email" class="mt-3" placeholder="Email Address" type="email" autofocus autocapitalize="off" />

                </div>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-100 flex justify-between items-center">
                    <inertia-link class="text-sm" :href="route('login')">Go back</inertia-link>

                    <loading-button :loading="form.processing" class="btn btn-primary" type="submit">Send password reset link</loading-button>
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
import FlashMessages from '@/Shared/FlashMessages'

export default {
    metaInfo: { title: 'Forgot Password' },
    components: {
        LoadingButton,
        Logo,
        TextInput,
        CheckboxInput,
        FlashMessages
    },
    data() {
        return {
            form: this.$inertia.form({ email: '' }),
        }
    },
    methods: {
        sendLink() {
            this.form
                // .transform(data => ({
                //     ...data,
                //     remember: data.remember ? 'on' : '',
                // }))
                .post(this.route('password.email'))
        },
    },
}
</script>
