// On page load or when changing themes, best to add inline in `head` to avoid FOUC
// This script is added to the <head> of the page in app.blade.php
// if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
//     document.documentElement.classList.add('dark');
// } else {
//     document.documentElement.classList.remove('dark')
// }

var themeToggleBtn = document.getElementById("theme-toggle");
if (themeToggleBtn) {
    var themeToggleDarkIcon = document.getElementById("theme-toggle-dark-icon");
    var themeToggleLightIcon = document.getElementById(
        "theme-toggle-light-icon"
    );

    // Function to set the icon state
    function setIconState() {
        if (document.documentElement.classList.contains("dark")) {
            themeToggleDarkIcon.classList.add("hidden");
            themeToggleLightIcon.classList.remove("hidden");
        } else {
            themeToggleDarkIcon.classList.remove("hidden");
            themeToggleLightIcon.classList.add("hidden");
        }
    }

    // Set initial state
    setIconState();

    themeToggleBtn.addEventListener("click", function () {
        // Toggle the theme
        if (localStorage.getItem("color-theme")) {
            // If light, switch to dark and save theme
            if (localStorage.getItem("color-theme") === "light") {
                document.documentElement.classList.add("dark");
                localStorage.setItem("color-theme", "dark");
            } else {
                document.documentElement.classList.remove("dark");
                localStorage.setItem("color-theme", "light");
            }
        } else {
            // If not set, check the system preference and toggle based on that
            if (document.documentElement.classList.contains("dark")) {
                document.documentElement.classList.remove("dark");
                localStorage.setItem("color-theme", "light");
            } else {
                document.documentElement.classList.add("dark");
                localStorage.setItem("color-theme", "dark");
            }
        }

        // Update icon state after click
        setIconState();
    });
}
