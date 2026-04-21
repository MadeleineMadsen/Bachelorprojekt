// app.js
// Global JavaScript til hele appen
// Fx navigation, burgermenu og initialisering


document.addEventListener('DOMContentLoaded', () => {
    const burgerBtn = document.getElementById('burgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    if (!burgerBtn || !mobileMenu) return;

    burgerBtn.addEventListener('click', () => {
        const isOpen = burgerBtn.classList.toggle('open');
        mobileMenu.classList.toggle('open', isOpen);

        burgerBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        burgerBtn.setAttribute('aria-label', isOpen ? 'Luk menu' : 'Åbn menu');
    });
});