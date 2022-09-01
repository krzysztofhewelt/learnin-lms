const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  darkMode: 'class',
  content: [
      './public/index.html',
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
  ],
  theme: {
    extend: {
        fontFamily: {
            sans: ['Nunito', ...defaultTheme.fontFamily.sans],
        },

        colors: {
            bgmain: '#ececfa'
        }
    },
  },

    variants: {
        extend: {
            opacity: ['disabled'],
        }
    },

    plugins: [
      require('@tailwindcss/forms')({
          useFormClasses: true,
      }),
  ],
}
