<template>
    <div class="bg-white p-14 rounded-lg">
        <div ref="intro" class="flex flex-col items-center text-center">
            <h3 class="mb-5 text-4xl leading-snug font-extrabold text-gray-900">Loyalty Programme</h3>

            <loyalty-logo class="my-8" />

            <h3 class="mb-8 text-xl font-black leading-tight">
                Reward your customers with loyalty rewards or offers <br />
                and build repeat business.
            </h3>

            <h4 class="mb-4 text-lg font-black leading-tight">How it works:</h4>

            <ol class="list-decimal mb-6 leading-normal">
                <li>Customise how your customers earn points</li>
                <li>Enrol them at checkout with a phone number</li>
                <li>Redeem their rewards from your point of sale</li>
            </ol>

            <inertia-link class="btn btn-primary px-10 py-3" as="button" :href="route('loyalty.create')">Get started</inertia-link>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import LoyaltyLogo from '@/Shared/LoyaltyLogo'

export default {
    metaInfo: { title: 'Loyalty Programme' },
    layout: Layout,
    components: {
        LoyaltyLogo,
    }
}
</script>
