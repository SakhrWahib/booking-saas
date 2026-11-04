/** @type {import('tailwindcss').Config} */
export default {
    content: ['./resources/views/**/*.blade.php', './resources/js/**/*.js'],
    theme: {
        extend: {
            colors: { brand: 'var(--brand-color, #7c3aed)' },
            fontFamily: { sans: ['Cairo', 'sans-serif'] },
        },
    },
    plugins: [],
};
