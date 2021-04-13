<template>
    <div>
        <h3 class="font-extrabold text-3xl text-center mb-10">Loyalty Programme</h3>

        <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
            <form @submit.prevent="store" autocomplete="off">
                <div class>
                    <div class="p-8">
                        <h4 class="font-bold text-lg mb-3">Terminology</h4>
                        <div class="pl-6 -mr-6 -mb-8 flex flex-wrap">
                            <text-input v-model="form.terminology.one" :error="form.errors['terminology.one']" class="pr-6 pb-8 w-full lg:w-1/2" label="Singular Form" placeholder="Point" />
                            <text-input v-model="form.terminology.other" :error="form.errors['terminology.other']" class="pr-6 pb-8 w-full lg:w-1/2" label="Plural Form" placeholder="Points" />
                        </div>
                    </div>

                    <div class="border-t border-gray-300"></div>

                    <div class="p-8">
                        <h4 class="font-bold text-lg mb-3">Accrual Rule</h4>
                        <div class="pl-6 -mr-6 -mb-8 flex flex-wrap">
                            <select-input v-model="form.accrual_type" :error="form.errors.accrual_type" class="pr-6 pb-8 w-full lg:w-1/3" label="Earn By">
                                <option v-for="(value, key) in accrual_types" :key="key" :value="key" v-text="value"></option>
                            </select-input>

                            <text-input v-if="form.accrual_type == 'VISIT'" v-model="form.amount" :error="form.errors.amount" class="pr-6 pb-8 w-full lg:w-1/3" :label="`Minimum Purchase (${settings.currency})`" :placeholder="moneyFormat(0)" @keypress="onlyDecimalFormat" />

                            <text-input v-if="form.accrual_type == 'SPEND'" v-model="form.amount" :error="form.errors.amount" class="pr-6 pb-8 w-full lg:w-1/3" :label="`Amount Spent (${settings.currency})`" :placeholder="moneyFormat(0)" @keypress="onlyDecimalFormat" />

                            <text-input
                                v-model="form.points"
                                :error="form.errors.points"
                                class="pr-6 pb-8 w-full lg:w-1/3"
                                :label="`${getTerminologyFor(2)} Earned`"
                                :placeholder="`1 ${getTerminologyFor(1)}`"
                                @keypress="
                                    onlyNumberFormat($event)
                                    onlyGreaterThanZero($event)
                                "
                            />
                        </div>
                    </div>

                    <div class="border-t border-gray-300"></div>

                    <div class="p-8">
                        <h4 class="font-bold text-lg mb-3">Redeeming Rewards</h4>
                        <div class="text-center text-red my-5">{{ form.errors.reward_tiers }}</div>

                        <div v-for="(tier, tier_k) in form.reward_tiers" :key="tier_k" class="pl-6 -mr-6 -mb-8 flex flex-wrap">
                            <select-input v-model="tier.scope" :error="form.errors[`reward_tiers.${tier_k}.scope`]" class="pr-6 pb-8 w-full lg:w-1/2" label="Reward Type">
                                <option v-for="(value, key) in discount_scopes" :key="key" :value="key" v-text="value"></option>
                            </select-input>

                            <text-input
                                v-model="tier.points"
                                :error="form.errors[`reward_tiers.${tier_k}.points`]"
                                class="pr-6 pb-8 w-full lg:w-1/2"
                                label="Reward Points"
                                :placeholder="`10 ${getTerminologyFor(10)}`"
                                @keypress="
                                    onlyNumberFormat($event)
                                    onlyGreaterThanZero($event)
                                "
                            />

                            <select-input
                                v-model="tier.discount_type"
                                :error="form.errors[`reward_tiers.${tier_k}.discount_type`]"
                                class="pr-6 pb-8 w-full lg:w-1/2"
                                label="Discount Type"
                                @change="
                                    tier.discount_type = $event.target.value
                                    generateTierName(tier)
                                "
                            >
                                <option v-for="(value, key) in discount_types" :key="key" :value="key" v-text="value"></option>
                            </select-input>

                            <text-input v-if="tier.discount_type == 'FIXED_AMOUNT'" v-model="tier.discount_value" :error="form.errors[`reward_tiers.${tier_k}.discount_value`]" class="pr-6 pb-8 w-full lg:w-1/2" :label="`Discount Value (${settings.currency})`" :placeholder="moneyFormat(0)" @keypress="onlyDecimalFormat" @keyup="generateTierName(tier)" />

                            <text-input v-if="tier.discount_type == 'FIXED_PERCENTAGE'" v-model="tier.discount_value" :error="form.errors[`reward_tiers.${tier_k}.discount_value`]" class="pr-6 pb-8 w-full lg:w-1/2" label="Discount Value (%)" placeholder="0%" @keypress="onlyDecimalFormat" @keyup="generateTierName(tier)" />

                            <text-input v-if="tier.discount_type == 'FIXED_AMOUNT'" v-model="tier.name" :error="form.errors[`reward_tiers.${tier_k}.name`]" class="pr-6 pb-8 w-full" label="Reward Name" placeholder="off entire sale" @input="tier.nameHasChanged = true" />

                            <text-input v-if="tier.discount_type == 'FIXED_PERCENTAGE'" v-model="tier.name" :error="form.errors[`reward_tiers.${tier_k}.name`]" class="pr-6 pb-8 w-full" label="Reward Name" placeholder="% off entire sale" @input="tier.nameHasChanged = true" />

                            <div class="border-b border-red-200 mb-16 w-full relative">
                                <inertia-link href="#" @click.prevent="removeTier(tier_k)" class="text-xs text-red-400 hover:text-red-500 font-bold bg-red-200 hover:bg-red-300 py-1 px-4 rounded-t absolute -top-6 left-72">Remove</inertia-link>
                            </div>
                        </div>

                        <inertia-link v-if="showAddNewTierButton" as="button" href="#" class="flex items-center justify-center mx-auto group my-10" @click.prevent="addNewTierButton">
                            <icon class="w-4 h-4 mr-2 fill-primary group-hover:fill-primary-300" name="add-outline" />
                            <div class="text-xs font-semibold text-primary group-hover:text-primary-300 leading-tight">Add a New Reward</div>
                        </inertia-link>
                    </div>

                    <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
                        <loading-button :loading="form.processing" class="btn btn-primary ml-auto" type="submit">Create Program</loading-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import Icon from '@/Shared/Icon'
import LoyaltyLogo from '@/Shared/LoyaltyLogo'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
    metaInfo: { title: 'Create Loyalty Programme' },
    layout: Layout,
    components: {
        Icon,
        LoyaltyLogo,
        LoadingButton,
        SelectInput,
        TextInput,
        TrashedMessage,
    },
    props: {
        settings: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                accrual_type: 'VISIT',
                points: null,
                amount: null,
                reward_tiers: [],
                terminology: {
                    one: 'Point',
                    other: 'Points',
                },
            }),
            showWizard: false,
            showAddNewTierButton: true,
            accrual_types: {
                VISIT: 'Visit',
                SPEND: 'Amount Spent',
                // ITEM_VARIATION: 'A Specific Item',
                // CATEGORY: 'Any Item in a Category',
            },
            discount_scopes: {
                ORDER: 'Discount Entire Sale',
                // ITEM_VARIATION: 'Discount a Specific Item',
                // CATEGORY: 'Discount a Category',
            },
            discount_types: {
                FIXED_PERCENTAGE: 'Percentage',
                FIXED_AMOUNT: 'Fixed Amount',
                // ITEM_VARIATION: 'Discount a Specific Item',
                // CATEGORY: 'Discount a Category',
            },
            reward_tiers_temp: {
                scope: 'ORDER',
                name: null,
                nameHasChanged: false,
                points: null,
                discount_type: 'FIXED_PERCENTAGE',
                discount_value: null,
            },
        }
    },
    methods: {
        getStarted() {
            // Remove intro
            this.$refs.intro.remove()

            // Start the wizard
            this.showWizard = true
        },
        store() {
            this.form.post(this.route('loyalty.store'))
        },
        addNewTierButton() {
            this.form.reward_tiers.push({ ...this.reward_tiers_temp })
            // this.showAddNewTierButton = false
        },
        removeTier(key) {
            this.form.reward_tiers.splice(key, 1)
            // if ( this.tier.length == 0 ) return

            // this.showAddNewTierButton = true
        },
        generateTierName(tier) {
            // only generate if not editted/touched
            if (tier.nameHasChanged) return

            if (this.isEmpty(tier.discount_value)) {
                tier.name = ''
                return
            }

            let format

            switch (tier.discount_type) {
                default:
                case 'FIXED_AMOUNT':
                    format = this.moneyFormat(tier.discount_value)
                    break
                case 'FIXED_PERCENTAGE':
                    format = this.decimalFormat(tier.discount_value)
                    format = `${isNaN(format) ? '' : format}%`
                    break
            }

            tier.name = `${format} off entire sale`
        },
        getTerminologyFor(value) {
            const def = ['Point', 'Points']
            let terminology

            if (parseInt(value) > 1) {
                terminology = this.isEmpty(this.form.terminology.other) ? def[1] : this.form.terminology.other
            } else {
                terminology = this.isEmpty(this.form.terminology.one) ? def[0] : this.form.terminology.one
            }

            return terminology
        },
    },
}
</script>
