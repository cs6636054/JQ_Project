document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('menu-toggle');
    const sideNav = document.getElementById('nav-bar');
    const overlay = document.getElementById('overlay');

    function toggleMenu() {
        sideNav.classList.toggle('open');
        overlay.classList.toggle('visible');
    }

    toggleButton.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);
});