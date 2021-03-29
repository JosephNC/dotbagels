<template>
    <button :disabled="loading" class="flex items-center" :class="[ loading ? `opacity-80 ${loadingClass}` : '' ]">
        <div v-if="loading" class="btn-spinner mr-2" />
        <slot />
    </button>
</template>

<script>
export default {
    props: {
        loading: Boolean,
        loadingClass: String,
    },
}
</script>
