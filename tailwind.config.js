/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        poppins : ['Poppins'], 
        dancing : ['Dancing Script'],
        lobster : ['Lobster'],
        ptSerif : ['PT Serif'],
        bebas : ['Bebas Neue'],
        lugrasimo : ['Lugrasimo'],
    },
  },
},
  plugins: [],
}

