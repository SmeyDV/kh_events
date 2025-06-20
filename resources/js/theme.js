// Function to apply theme changes
function applyTheme(theme) {
  if (theme === 'dark') {
    document.documentElement.classList.add('dark');
    localStorage.setItem('color-theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('color-theme', 'light');
  }
}

// Function to toggle theme and icons
function toggleTheme(darkIcon, lightIcon) {
  darkIcon.classList.toggle('hidden');
  lightIcon.classList.toggle('hidden');

  const currentTheme = localStorage.getItem('color-theme')
    ? (localStorage.getItem('color-theme') === 'light' ? 'dark' : 'light')
    : (document.documentElement.classList.contains('dark') ? 'light' : 'dark');

  applyTheme(currentTheme);
}

// Function to initialize a theme toggle button
function initThemeToggleButton(btnId, darkIconId, lightIconId) {
  const btn = document.getElementById(btnId);
  const darkIcon = document.getElementById(darkIconId);
  const lightIcon = document.getElementById(lightIconId);

  if (btn && darkIcon && lightIcon) {
    // Set initial icon visibility
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      lightIcon.classList.remove('hidden');
    } else {
      darkIcon.classList.remove('hidden');
    }

    // Add click listener
    btn.addEventListener('click', () => toggleTheme(darkIcon, lightIcon));
  }
}

// Initialize both buttons
initThemeToggleButton('theme-toggle', 'theme-toggle-dark-icon', 'theme-toggle-light-icon');
initThemeToggleButton('theme-toggle-responsive', 'theme-toggle-dark-icon-responsive', 'theme-toggle-light-icon-responsive'); 