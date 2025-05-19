// main.js
document.addEventListener('DOMContentLoaded', () => {
    // 1) Apply stored theme
    const theme = localStorage.getItem('theme') || 'light';
    document.body.classList.add(theme);
  
    // 2) Wire up the theme toggle button
    const toggle = document.getElementById('themeToggle');
    if (toggle) {
      toggle.addEventListener('click', () => {
        const next = document.body.classList.contains('light') ? 'dark' : 'light';
        document.body.classList.replace(
          document.body.classList.contains('light') ? 'light' : 'dark',
          next
        );
        localStorage.setItem('theme', next);
      });
    }
  
    // 3) Update header nav based on login state
    const userName = localStorage.getItem('userName');
    if (userName) {
      // ... hide login/register, show welcome & logout ...
    }
  
    // 4) Restore saved filters if on the products page
    const saved = localStorage.getItem('savedFilters');
    if (saved) {
      // ... parse & apply ...
    }
  });
  