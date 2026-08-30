const THEME_KEY = 'readora-theme';

function getStoredTheme() {
  return localStorage.getItem(THEME_KEY) || 'system';
}

function getSystemTheme() {
  return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

function getEffectiveTheme(preference) {
  if (preference === 'system' || !preference) {
    return getSystemTheme();
  }
  return preference;
}

function applyTheme(preference) {
  const effective = getEffectiveTheme(preference);
  const html = document.documentElement;

  html.classList.toggle('dark', effective === 'dark');
  html.dataset.theme = preference || 'system';

  document.querySelectorAll('[data-theme-icon]').forEach((icon) => {
    icon.classList.toggle('hidden', icon.dataset.themeIcon !== (preference || 'system'));
  });

  document.querySelectorAll('[data-theme-option]').forEach((option) => {
    const active = option.dataset.themeOption === (preference || 'system');
    option.classList.toggle('font-medium', active);
    option.classList.toggle('text-gold-600', active);
    option.classList.toggle('dark:text-gold-400', active);
  });
}

function setTheme(preference) {
  localStorage.setItem(THEME_KEY, preference);
  
  document.documentElement.classList.add('theme-transition');
  applyTheme(preference);

  setTimeout(() => {
    document.documentElement.classList.remove('theme-transition');
  }, 300);

  window.dispatchEvent(new CustomEvent('theme-changed', { detail: { preference, effective: getEffectiveTheme(preference) } }));
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
  const stored = getStoredTheme();
  if (stored === 'system') {
    applyTheme('system');
  }
});

window.ReadOraTheme = {
  get: getStoredTheme,
  set: setTheme,
  getEffective: () => getEffectiveTheme(getStoredTheme()),
};

document.addEventListener('DOMContentLoaded', () => {
  applyTheme(getStoredTheme());

  document.querySelectorAll('[data-theme-menu]').forEach((menu) => {
    const toggle = menu.querySelector('[data-theme-menu-toggle]');
    const panel = menu.querySelector('[data-theme-menu-panel]');

    toggle?.addEventListener('click', () => {
      const isOpen = !panel.classList.toggle('hidden');
      toggle.setAttribute('aria-expanded', String(isOpen));
    });

    menu.querySelectorAll('[data-theme-option]').forEach((option) => {
      option.addEventListener('click', () => {
        setTheme(option.dataset.themeOption);
        panel.classList.add('hidden');
        toggle?.setAttribute('aria-expanded', 'false');
      });
    });
  });

  document.addEventListener('click', (event) => {
    document.querySelectorAll('[data-theme-menu]').forEach((menu) => {
      if (!menu.contains(event.target)) {
        menu.querySelector('[data-theme-menu-panel]')?.classList.add('hidden');
        menu.querySelector('[data-theme-menu-toggle]')?.setAttribute('aria-expanded', 'false');
      }
    });
  });

  document.querySelectorAll('[data-mobile-menu-toggle]').forEach((toggle) => {
    const menu = document.getElementById(toggle.getAttribute('aria-controls'));

    toggle.addEventListener('click', () => {
      const isOpen = !menu.classList.toggle('hidden');
      toggle.setAttribute('aria-expanded', String(isOpen));
    });
  });
});
