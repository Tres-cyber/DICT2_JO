/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "../public/**/*.{php,html,twig}",
    "./src/**/*.{js,ts,tsx,jsx,html,vue}",
  ],
  theme: {
    fontFamily: {
      sans: 'ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"',
      serif:
        '"Noto Serif", ui-serif, Georgia, Cambria, "Times New Roman", Times, serif',
      mono: 'ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace',
    },
    extend: {},
  },
  plugins: [require("@headlessui/tailwindcss")],
};
