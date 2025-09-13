import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import aspectRatio from '@tailwindcss/aspect-ratio';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: 'class', // Enables class-based dark mode
  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        primary: {
          light: '#6C63FF',
          DEFAULT: '#5A54E8',
          dark: '#4B47C4',
        },
        secondary: {
          light: '#FFB6B9',
          DEFAULT: '#FF6B6B',
          dark: '#C94C4C',
        },
        neutral: {
          light: '#F5F5F5',
          DEFAULT: '#E5E5E5',
          dark: '#1F2937',
        },
        accent: '#22D3EE',
      },
      animation: {
        wiggle: 'wiggle 1s ease-in-out infinite',
      },
      keyframes: {
        wiggle: {
          transform: 'rotate(-3deg)' },
          '50%': { transform: 'rotate(3deg)' },
        },
      },
    },
  },
  plugins: [
    forms,
    typography,
    aspectRatio,
  ],
};
