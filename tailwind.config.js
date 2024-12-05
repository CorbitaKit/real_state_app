/** @type {import('tailwindcss').Config} */
import forms from '@tailwindcss/forms';
export default {
  prefix: 'tw-',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    forms,
  ],
  corePlugins: {
    visibility: true
  }
}

