// app.js
// Global JavaScript til hele appen
// Fx navigation, burgermenu og initialisering


// ------------------------------------------------
// Burgermenu
// ------------------------------------------------
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


// ------------------------------------------------
// Fjern aktivt link på "om" når "kontakt" klikkes og omvendt
// ------------------------------------------------

function updateActiveNav() {
    if (window.location.pathname !== '/about') return;

    const navLinks = document.querySelectorAll('.bottom-nav-link');

    navLinks.forEach(link => {
        link.classList.remove('active');

        if (window.location.hash === '#contact') {
            if (link.getAttribute('href') === '/about#contact') {
                link.classList.add('active');
            }
        } else {
            if (link.getAttribute('href') === '/about') {
                link.classList.add('active');
            }
        }
    });
}

updateActiveNav();
window.addEventListener('hashchange', updateActiveNav);