const colors = require('tailwindcss/colors') 
 
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php', 
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: { 
                danger: colors.red,
                primary: colors.yellow,
                success: colors.green,
                warning: colors.orange,
            }, 
        },
    },
    plugins: [
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography'), 
    ],
}