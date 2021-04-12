<template>
    <div>
        <label class="form-label flex items-center" :class="labelClass" :for="id">
            <input :id="id" v-bind="$attrs" v-model="model" :class="{ error: error }" type="checkbox" @change="$emit('change', $event.target.checked)" />
            <!-- <span class="checkbox" @click="this.$refs.input.click()"></span> -->
            <span class="checkbox"></span><span>{{ label }}</span>
        </label>
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `checkbox-input-${this._uid}`
            },
        },
        model: {
            type: Boolean,
            default() {
                return false
            },
        },
        label: String,
        labelClass: String,
        error: String,
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
    },
}
</script>
