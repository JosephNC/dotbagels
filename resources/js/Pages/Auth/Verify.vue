<template>
    <div class="p-6 bg-primary-50 min-h-screen flex justify-center items-center md:items-start">
        <div class="w-full max-w-md md:mt-16">
            <flash-messages />

            <form class="bg-white rounded-md shadow-lg overflow-hidden" @submit.prevent="verify">
                <!-- <img src="/images/logo.png" class="block mx-auto w-20 max-w-min fill-white" height="50" /> -->

                <div class="px-8 pb-4">
                    <div class="flex flex-col justify-center items-center mt-5">
                        <img class="block w-18 h-18 rounded-full mb-2" :src="$page.props.auth.user.photo" />

                        <h1 class="text-center font-bold text-xl">{{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</h1>
                    </div>

                    <div class="mx-auto mt-2 mb-8 w-24 border-b-2" />

                    <p class="text-sm text-center">Account awaiting verification.</p>
                    <p class="text-sm text-center">Didn't receive the email? <inertia-link class="text-sm p-0 text-primary-200 font-bold" :href="route('verification.send')" method="post" as="button">Resend it!</inertia-link></p>
                </div>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-100 flex justify-center items-center">
                    <!-- <loading-button :loading="form.processing" class="btn btn-primary" type="submit" :href="route('login')">Logout</loading-button> -->
                    <inertia-link class="btn btn-primary" :href="route('logout')" method="post" as="button">Logout</inertia-link>
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
    metaInfo: { title: 'Verify Account' },
    components: {
        LoadingButton,
        Logo,
        TextInput,
        CheckboxInput,
        FlashMessages,
    },
}
</script>
