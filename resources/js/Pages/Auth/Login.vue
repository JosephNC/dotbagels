<template>
    <div class="p-6 bg-primary-50 min-h-screen flex justify-center items-center md:items-start">
        <div class="w-full max-w-md md:mt-16">
            <!-- <logo class="block mx-auto w-full max-w-xs fill-white" height="50" /> -->

            <form class="bg-white rounded-md shadow-lg overflow-hidden" @submit.prevent="login">
                <img src="/images/logo.png" class="block mx-auto w-20 max-w-min fill-white" height="50" />

                <div class="px-8 pb-4">
                    <h1 class="text-center font-bold text-xl">Welcome Back!</h1>

                    <div class="mx-auto mt-2 mb-8 w-24 border-b-2" />

                    <text-input v-model="form.email" :error="form.errors.email" class="mt-3" placeholder="Email Address" type="email" autofocus autocapitalize="off" />

                    <div class="mt-3 relative">
                        <text-input v-model="form.password" :error="form.errors.password" placeholder="Password" type="password" />
                        <inertia-link class="text-xs absolute top-0 right-0 pl-0 p-3 leading-tight" :href="route('password.request')">Reset password?</inertia-link>
                    </div>
                    
                    <checkbox-input :model="form.remember" class="mt-5" label="Remember Me" @change="changeRemember" />
                </div>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-100 flex justify-between items-center">
                    <inertia-link class="text-sm" :href="route('register')">Create an account</inertia-link>

                    <loading-button :loading="form.processing" class="btn btn-primary" type="submit">Login</loading-button>
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
    metaInfo: { title: 'Login' },
    components: {
        LoadingButton,
        Logo,
        TextInput,
        CheckboxInput,
    },
    data() {
        return {
            form: this.$inertia.form({
                email: '',
                password: '',
                remember: false,
            }),
        }
    },
    methods: {
        changeRemember(checked) {
            this.form.remember = checked;
            this.form.errors.remember = '';
        },
        login() {
            // Clear all errors
            // this.form.clearErrors()

            this.form
                .transform(data => ({
                    ...data,
                    remember: data.remember ? 'on' : '',
                }))
                .post(this.route('login'))
        },
    },
}
</script>
