/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        usepmaroon: '#800000',
        usepgold: '#FFD700',
      },
    },
  },
  safelist: [
    'text-usepmaroon',
    'border-usepmaroon',
    'bg-usepmaroon',
    'hover:text-usepmaroon/80',
    'hover:bg-usepmaroon/10',
    'text-usepgold',
    'border-usepgold',
    'bg-usepgold',
    'hover:text-usepgold/80',
    'hover:bg-usepgold/10',
  ],
  plugins: [],
};
