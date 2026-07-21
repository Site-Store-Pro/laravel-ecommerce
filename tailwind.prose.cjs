/** @type {import('tailwindcss').Config} */
const forms = require('@tailwindcss/forms');
const typography = require('@tailwindcss/typography');

module.exports = {
    // Safelist all prose classes so they are always included
    safelist: [
        { pattern: /^prose/ },
        { pattern: /^not-prose/ },
    ],
    content: [
        // Include a dummy content so tailwind doesn't tree-shake prose
        './resources/css/prose-safelist.html',
    ],
    plugins: [forms, typography],
};
