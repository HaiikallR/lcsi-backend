/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
        fontFamily: {
            'display': ['Sora', 'sans-serif'],
            'body': ['DM Sans', 'sans-serif'],
        },
    },
  },
  plugins: [],
}