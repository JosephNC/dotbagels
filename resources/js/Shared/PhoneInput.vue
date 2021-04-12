<template>
    <div>
        <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
        <input :id="id" ref="input" v-bind="$attrs" class="form-input" :class="{ error: error }" type="tel" :value="value" @input="input" @blur="blur" @keypress="numberOnly" />
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script>
import 'intl-tel-input/build/css/intlTelInput.css'
import intlTelInput from 'intl-tel-input'

export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `phone-input-${this._uid}`
            },
        },
        value: String,
        initialCountryCode: {
            type: String,
            default() {
                return 'US'
            },
        },
        placeholder: String,
        label: String,
        error: String,
    },
    data() {
        return {
            iti: null,
        }
    },
    mounted() {
        this.iti = intlTelInput(this.$refs.input, {
            nationalMode: true,
            formatOnDisplay: true,
            separateDialCode: false,
            initialCountry: this.initialCountryCode,
            autoplaceholder: 'aggressive',
            customContainer: 'w-full',
            utilsScript: '/js/libs/intl-tel-input-utils.min.js',
            customPlaceholder: (exp) => 'e.g. ' + exp,
        })

        this.iti.promise.then(() => {
            const parent = this.$refs.input.parentNode
            const list = parent.querySelector('ul.iti__country-list')
            const width = this.$refs.input.offsetWidth + 'px'
            // parent.style.width = width
            // parent.style.position = 'absolute'

            if (list != null) {
                list.style.width = width
                list.style.whiteSpace = 'initial'
            }

            // Fix country code if not detected
            if (!this.iti.getSelectedCountryData().iso2) this.iti.setCountry(this.initialCountryCode)
        })

        // When the country dropdown is changed
        this.$refs.input.addEventListener('countrychange', () => this.$emit('countryCode', this.iti.getSelectedCountryData().iso2.toUpperCase()))
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
        input() {
            if (typeof intlTelInputUtils === 'undefined') return // utils are lazy loaded, so must check

            const phoneNumber = this.iti.getNumber(intlTelInputUtils.numberFormat.E164)

            // sometimes the phoneNumber is an object :)
            if (typeof phoneNumber === 'string') this.iti.setNumber(phoneNumber) // will autoformat if formatOnDisplay=true

            this.$emit('input', this.$refs.input.value)

            this.$emit('countryCode', this.iti.getSelectedCountryData().iso2.toUpperCase())
        },
        blur() {
            if (!!!this.$refs.input.value.trim()) return

            let errorMsg = ''

            if (!this.iti.isValidNumber()) {
                const errorMap = ['Invalid number', 'Invalid country code', 'Too short', 'Too long', 'Invalid number']

                errorMsg = errorMap[this.iti.getValidationError()]

                errorMsg = errorMsg == undefined ? errorMap[0] : errorMsg
            }

            this.$emit('onValidation', errorMsg)
        },
        numberOnly(evt) {
            evt = evt ? evt : window.event
            var charCode = evt.which ? evt.which : evt.keyCode

            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                evt.preventDefault()
            } else {
                return true
            }
        },
    },
}
</script>