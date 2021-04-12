const tcolors = require('tailwindcss/colors')
const { colors, maxWidth, spacing, inset } = require('tailwindcss/defaultTheme')

module.exports = {
    future: {
        removeDeprecatedGapUtilities: true,
        purgeLayersByDefault: true,
    },
    purge: {
        enable: true,
        mode: 'all',
        preserveHtmlElements: false,
        options: {
            blocklist: [/^debug-/],
            keyframes: true,
            fontFace: true,
        },

        content: [
            // prettier-ignore
            './resources/**/*.blade.php',
            './resources/**/*.js',
            './resources/**/*.vue',
        ],
    },
    darkMode: false, // or 'media' or 'class'
    theme: {
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            black: tcolors.black,
            white: tcolors.white,
            red: tcolors.red,
            orange: tcolors.orange,
            yellow: tcolors.yellow,
            green: tcolors.green,
            blue: tcolors.blue,
            // gray: tcolors.blueGray,
            indigo: {
                100: '#e6e8ff',
                300: '#b2b7ff',
                400: '#7886d7',
                500: '#6574cd',
                600: '#5661b3',
                800: '#2f365f',
                900: '#191e38',
            },
        },
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#1b5125',
                    25: '#eff3f0',
                    50: '#e8eee9',
                    100: '#bbcbbe',
                    200: '#8da892',
                    300: '#5f8566',
                    400: '#32623b',
                    500: '#1b5125',
                    600: '#184921',
                    700: '#16411e',
                    800: '#13391a',
                    900: '#103116',
                },

                accent: {
                    DEFAULT: '#e19100',
                    50: '#f9e9cc',
                    100: '#f3d399',
                    200: '#edbd66',
                    300: '#e7a733',
                    400: '#e49c1a',
                    500: '#e19100',
                    600: '#cb8300',
                    700: '#b47400',
                    800: '#9e6600',
                    900: '#875700',
                },

                gray: {
                    DEFAULT: colors.gray[500],
                    ...colors.gray,
                    50: '#fbfbfb',
                },

                red: {
                    DEFAULT: colors.red[500],
                    ...colors.red,
                    50: '#FFF7F7',
                },

                green: {
                    DEFAULT: colors.green[500],
                    ...colors.green,
                    50: '#F7FFF7',
                },
            },
            zIndex: {
                9999: '9999',
            },
            spacing: {
                '2px': '2px',
                '3px': '3px',
                '5px': '5px',
                '100px': '100px',
                '3/2': `calc( ${spacing['3']} / 2 )`, // Eqv 6px
                '5/2': `calc( ${spacing['5']} / 2 )`, // Eqv 10px
            },
            minWidth: {
                '0': maxWidth[0],
                xl: maxWidth.xl,
                ...maxWidth,
            },
            inset: {
                ...spacing,
                ...inset,
            },
            borderRadius: {
                '1/2': '50%',
            },
            keyframes: {
                dropdown: {
                    from: {
                        transform: 'translate(8px,-8px) scale(0.8)',
                        opacity: 0.5,
                    },
                    to: {
                        transform: 'translate(0,0) scale(1)',
                        opacity: 1,
                    },
                },
                dropup: {
                    from: {
                        transform: 'translate(0,0) scale(1)',
                        opacity: 1,
                    },
                    to: {
                        transform: 'translate(8px,-8px) scale(0.8)',
                        opacity: 0,
                    },
                },
            },
            animation: {
                'spin-fast': 'spin 500ms linear infinite',
                'spin-xfast': 'spin 200ms linear infinite',
                dropdown: 'dropdown 100ms ease-out',
                dropup: 'dropup 100ms ease-in',
            },
            transitionTimingFunction: {
                // ease: 'cubic-bezier(0.25, 0.1, 0.25, 1)',
                ease: 'ease',
            },
            fontFamily: {
                sans: ['Nunito Sans', 'sans-serif'],
            },
            // fontFamily: {
            //     sans: ['Cerebri Sans', ...fontFamily.sans],
            // },
            // fontFamily: {
            //     sans: ['Nunito', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', '"Helvetica Neue"', 'Arial', '"Noto Sans"', 'sans-serif', '"Apple Color Emoji"', '"Segoe UI Emoji"', '"Segoe UI Symbol"', '"Noto Color Emoji"'],
            // },
            // fontSize: {
            //     xs: ['8px', '10px'],
            //     sm: ['10px', '14px'],
            //     base: ['14px', '20px'],
            //     // md: ["14px", "24px"],
            //     lg: ['18px', '24px'],
            //     xl: ['20px', '28px'],
            // },
            fontWeight: {
                hairline: 200,
                'extra-light': 200,
                thin: 300,
                light: 400,
                normal: 500,
                medium: 600,
                semibold: 700,
                bold: 800,
                extrabold: 900,
                'extra-bold': 900,
                black: 900,
            },
            borderColor: theme => ({
                DEFAULT: theme('colors.gray.200', 'currentColor'),
            }),
            boxShadow: theme => ({
                outline: '0 0 0 2px ' + theme('colors.indigo.500'),
            }),
            fill: theme => theme('colors'),
        },
    },
    variants: {
        extend: {
            fill: ['hover', 'focus', 'group-hover'],
            outline: ['hover', 'focus', 'active'],
            appearance: ['hover', 'focus', 'active'],
            backgroundColor: ['active'],
            // border: ['hover', 'focus', 'active'],
        },
    },
    plugins: [],
}