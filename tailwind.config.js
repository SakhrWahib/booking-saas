/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: ['./resources/views/**/*.blade.php', './resources/js/**/*.js'],
    // ألوان تُبنى ديناميكيًا في JavaScript (مثل bg-${period.color}-50 في نموذج الحجز)
    safelist: [
        { pattern: /^(bg|text|border)-(yellow|blue|purple|green|red|orange|gray)-(50|100|200|300|500|600|700|800)$/, variants: ['hover'] },
    ],
    theme: {
        extend: {
            colors: {
                brand: 'var(--brand-color, #7c3aed)',
                primary: {
                    50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 300: '#7dd3fc', 400: '#38bdf8',
                    500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e',
                },
            },
            fontFamily: { sans: ['Cairo', 'sans-serif'], cairo: ['Cairo', 'sans-serif'] },
        },
    },
    plugins: [],
};
