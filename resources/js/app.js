const themeStorageKey = 'readora-theme';

const resolveTheme = (theme) => {
    if (theme === 'system') {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    return theme === 'dark' ? 'dark' : 'light';
};

const applyTheme = (theme) => {
    const resolvedTheme = resolveTheme(theme);

    document.documentElement.classList.toggle('dark', resolvedTheme === 'dark');
    document.documentElement.dataset.theme = theme;
};

window.readoraTheme = {
    current() {
        return localStorage.getItem(themeStorageKey) || 'system';
    },

    set(theme) {
        localStorage.setItem(themeStorageKey, theme);
        applyTheme(theme);
    },
};

applyTheme(window.readoraTheme.current());

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    if (window.readoraTheme.current() === 'system') {
        applyTheme('system');
    }
});
