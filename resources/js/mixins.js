export default {
    methods: {
        route: window.route,
        randomInt(min, max) {
            if (min > max) [min, max] = [max, min]

            return Math.floor(Math.random() * (max - min + 1) + min)
        },
        generatePassword() {
            const chars = [
                'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz', // 52 letters
                '0123456789', // 10 numbers
                'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', // 62 either
                '~!@#$%^&*()_-+=[{]}|;:,.<>/?', // 28 symbols
            ]

            const letters = this.randomInt(2, 9)
            const numbers = this.randomInt(3, 8)
            const either = this.randomInt(4, 7)
            const symbols = this.randomInt(5, 6)

            const password = [letters, numbers, either, symbols]
                .map((len, i) =>
                    Array(len)
                        .fill(chars[i])
                        .map(x => x[Math.floor(Math.random() * x.length)])
                        .join(''),
                )
                .concat()
                .join('')
                .split('')
                .sort(() => 0.5 - Math.random())
                .join('')

            this.form.password = password
        },

        moneyFormat(number) {
            const control = this.settings.currency_data,
                decimals = control.decimals,
                decimal_sep = control.decimal_sep,
                thousand_sep = control.thousand_sep,
                currency_sym = control.symbol,
                currency_pos = control.currency_pos

            let money

            number = this.numberFormat(this.decimalFormat(number), decimals, decimal_sep, thousand_sep)

            switch (currency_pos) {
                case 'left':
                    money = currency_sym + number
                    break
                case 'left_space':
                    money = currency_sym + ' ' + number
                    break
                case 'right':
                    money = number + currency_sym
                    break
                case 'right_space':
                    money = number + ' ' + currency_sym
                    break
                default:
                    money = currency_sym + number
                    break
            }

            return money
        },

        decimalFormat(number) {
            const control = this.settings.currency_data,
                decimals = control.decimals,
                decimal_sep = control.decimal_sep,
                thousand_sep = control.thousand_sep,
                reg = new RegExp(/[,]/g),
                has_comma = reg.test(number)

            if (thousand_sep == '.') {
                if (decimal_sep == ',' && has_comma === true) {
                    number = (number + '').replace(/[,]/g, '!')
                    number = (number + '').replace(/[.]/g, ',')
                    number = (number + '').replace(/[!]/g, '.')
                } else if (decimal_sep == '') {
                    number = (number + '').replace(/[.]/g, ',')
                }
            } else {
                number = (number + '').replace(thousand_sep, '')
                number = decimals == 0 ? number : (number + '').replace(decimal_sep, '.')
            }

            number = (number + '').replace(/[^0-9.]/g, '')
            return parseFloat(number)
        },

        /**
         * Format a number with grouped thousands
         *
         * @param number The number being formatted
         * @param decimals Sets the number of decimal points
         * @param dec_point Sets the separator for the decimal point
         * @param thousands_sep Sets the thousands separator
         * @returns string A formatted version of number
         */
        numberFormat(number, decimals, dec_point, thousands_sep) {
            // Strip all characters but numerical ones.
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
                dec = typeof dec_point === 'undefined' ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec)
                    return '' + (Math.round(n * k) / k).toFixed(prec)
                }
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
            if (s[0].length > 3) s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)

            if ((s[1] || '').length < prec) {
                s[1] = s[1] || ''
                s[1] += new Array(prec - s[1].length + 1).join('0')
            }

            return s.join(dec)
        },

        isEmpty(value) {
            return !value || value === '0' || !Boolean(value)
        },

        sleep(ms = 1000) {
            return new Promise(res => setTimeout(res, ms))
        },
        onlyGreaterThanZero(evt) {
            const charCode = evt.which ? evt.which : evt.keyCode
            const value = evt.target.value

            // Check if the text already contains the . character
            if (charCode == 48 && this.isEmpty(value)) {
                evt.preventDefault()
            } else {
                return true
            }
        },
        onlyNumberFormat(evt) {
            const charCode = evt.which ? evt.which : evt.keyCode

            if (!(charCode > 31 && (charCode < 48 || charCode > 57))) return true

            evt.preventDefault()
        },
        onlyDecimalFormat(evt) {
            const charCode = evt.which ? evt.which : evt.keyCode
            const value = evt.target.value

            // Check if the text already contains the . character
            if (charCode == 46 && value.indexOf(this.settings.currency_data.decimal_sep) === -1) return true

            if (!(charCode > 31 && (charCode < 48 || charCode > 57))) return true

            evt.preventDefault()
        },
    },
}
