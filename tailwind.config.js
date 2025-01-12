/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f6fef9',
          100: '#e3fbeb',
          200: '#caf8db',
          300: '#97f0b6',
          400: '#65e591',
          500: '#30b86a',  // Warna utama - Hijau natural
          600: '#25a65b',
          700: '#1b844a',
          800: '#17693c',
          900: '#145733',
        },
        // Warna untuk elemen sekunder - Coklat tanah
        secondary: {
          50: '#faf8f6',
          100: '#f5f0ea',
          200: '#e8dfd3',
          300: '#d5c3ae',
          400: '#bda185',
          500: '#a88b6e',  // Warna sekunder
          600: '#8c725b',
          700: '#735c4b',
          800: '#5f4c40',
          900: '#4f4037',
        },
        // Warna untuk success - Hijau sawah
        success: {
          50: '#f3faf7',
          100: '#e0f5ea',
          200: '#bae8d0',
          300: '#82d1aa',
          400: '#4db883',
          500: '#2a9d68',  // Success utama
          600: '#208255',
          700: '#1b6847',
          800: '#17533a',
          900: '#134431',
        },
        // Warna untuk warning - Kuning padi
        warning: {
          50: '#fffbeb',
          100: '#fef3c7',
          200: '#fde68a',
          300: '#fcd34d',
          400: '#f5b537',  // Warning utama - Kuning keemasan
          500: '#e09820',
          600: '#c47d18',
          700: '#9c6215',
          800: '#7c4f15',
          900: '#654214',
        },
        // Warna untuk danger - Merah bata
        danger: {
          50: '#fef2f2',
          100: '#fee2e2',
          200: '#fecaca',
          300: '#fca5a5',
          400: '#f87171',
          500: '#b84533',  // Danger utama - Merah bata
          600: '#953728',
          700: '#772c20',
          800: '#60231a',
          900: '#4d1c15',
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}