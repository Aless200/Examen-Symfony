/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/*/.js",
  ],
  darkMode: 'class',
  theme: {
    extend: {},
  },
  plugins: [
      require('flowbite/plugin'),
      require('@tailwindcss/forms')
  ],
}
