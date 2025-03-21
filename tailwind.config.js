/** @type {import('tailwindcss').Config} */
module.exports = {
  corePlugins: {
    preflight: false,
  },
  content: ['./views/**/*.php', './assets/src/**/*.js'],
  prefix: 'merkai-',
  theme: {
    extend: {
      colors: {
        'c-brown': '#D9A464',
        'c-blue': '#3B82F6',
        'c-orange': '#FFBF72',
      },
    },
  },
  plugins: [
  ],
}

