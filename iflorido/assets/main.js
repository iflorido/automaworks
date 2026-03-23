(function() {
  const root = document.documentElement;
  const toggle = document.getElementById('themeToggle');
  const savedTheme = localStorage.getItem('theme');
  const preferredTheme = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
  const activeTheme = savedTheme || preferredTheme;
  root.setAttribute('data-bs-theme', activeTheme);

  if (toggle) {
    toggle.addEventListener('click', function() {
      const next = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-bs-theme', next);
      localStorage.setItem('theme', next);
    });
  }
})();