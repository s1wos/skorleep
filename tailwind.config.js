/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.{vue,js,ts,jsx,tsx,blade.php}",
  ],
  theme: {
    extend: { animation: {
        pingSlow: "ping 5s cubic-bezier(0, 0, 0.2, 1) infinite",
        spinSlow: "spin 1.5s linear infinite",
        spinReverse: "spinReverse 1s linear infinite",
      },
      keyframes: {
        spinReverse: {
          from: { transform: "rotate(0deg)" },
          to: { transform: "rotate(-360deg)" },
        },
      },
    }
  },
  plugins: [],
}