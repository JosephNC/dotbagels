<template>
    <div>
        <!-- <div class="flex flex-wrap">
            <label v-if="label" class="form-label w-20 bg-gray-200 text-black" :for="id">{{ label }}:</label>
            <input :id="id" ref="input" v-bind="$attrs" class="form-input" :class="{ error: error }" :type="type" :value="value" @input="$emit('input', $event.target.value)" :placeholder="placeholder" />
        </div>
        <div v-if="error" class="form-error">{{ error }}</div> -->
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `text-input-${this._uid}`
            },
        },
        type: {
            type: String,
            default: 'text',
        },
        placeholder: String,
        value: String,
        label: String,
        error: String,
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end)
        },
    },
}
</script>
