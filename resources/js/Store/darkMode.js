import { defineStore } from 'pinia'

export const useDarkModeStore = defineStore('darkMode', {
    state: () => ({
        isDark: localStorage.getItem('darkMode') === 'true',
    }),
    actions: {
        toggleDarkMode() {
            this.isDark = !this.isDark;
            localStorage.setItem('darkMode', this.isDark);
            this.updateDocumentClass();
        },
        initDarkMode() {
            // Check system preference on first load
            if (localStorage.getItem('darkMode') === null) {
                this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                localStorage.setItem('darkMode', this.isDark);
            }
            this.updateDocumentClass();
        },
        updateDocumentClass() {
            if (this.isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }
})
