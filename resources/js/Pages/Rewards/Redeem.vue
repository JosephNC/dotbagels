<template>
    <div>
        <div class="flex flex-col justify-between mt-2 mb-6">
            <h3 class="text-3xl leading-tight font-bold text-gray-900 text-center">Redeem a code</h3>

            <div class="mt-5 shadow-lg bg-gray-100 rounded-md border">
                <form @submit.prevent="submit" autocomplete="off">
                    <div class="bg-white space-y-6 sm:p-20">
                        <div class="text-center uppercase">Enter the code</div>

                        <text-input v-model="form.redeem_code" :error="form.errors.redeem_code" className="text-center font-semibold p-5 text-2xl" />

                        <div class="flex justify-center">
                            <loading-button v-if="!canRedeem" :loading="form.processing" class="btn btn-primary" type="submit">Check Code</loading-button>
                            <loading-button v-if="canRedeem" :loading="form.processing" class="btn btn-primary" type="submit">Redeem Code</loading-button>
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
            canRedeem: false,
            form: this.$inertia.form({
                redeem_code: null,
            }),
        }
    },
    methods: {
        submit() {
            if (this.canRedeem) this.form.put(this.route('rewards.redeem'))
            else
                this.form.get(this.route('rewards.check'), {
                    onFinish: async (...a) => {
                        console.log(a)
                    },
                })
        },
    },
}
</script>
