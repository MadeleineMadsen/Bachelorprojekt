// app.js
// Global JavaScript til hele appen
// Fx navigation, burgermenu og initialisering

// ------------------------------------------------
// Burgermenu
// ------------------------------------------------

document.addEventListener("DOMContentLoaded", () => {
  // Henter elementer
  const burgerBtn = document.getElementById("burgerBtn");
  const mobileMenu = document.getElementById("mobileMenu");

  // Stop hvis elementerne ikke findes
  if (!burgerBtn || !mobileMenu) return;

  // Åbn/luk menu ved klik
  burgerBtn.addEventListener("click", () => {
    const isOpen = burgerBtn.classList.toggle("open");
    mobileMenu.classList.toggle("open", isOpen);

    // Opdater accessibility attributter
    burgerBtn.setAttribute("aria-expanded", isOpen ? "true" : "false");
    burgerBtn.setAttribute("aria-label", isOpen ? "Luk menu" : "Åbn menu");
  });
});

// ------------------------------------------------
// Fjern aktivt link på "om" når "kontakt" klikkes og omvendt
// ------------------------------------------------

function updateActiveNav() {
  // Kør kun på om siden
  if (window.location.pathname !== "/about") return;

  // Henter footer navigation links
  const navLinks = document.querySelectorAll(".bottom-nav-link");

  navLinks.forEach((link) => {
    // Fjerner eksisterende active class
    link.classList.remove("active");

    // Aktivér kontakt link hvis hash er #contact
    if (window.location.hash === "#contact") {
      if (link.getAttribute("href") === "/about#contact") {
        link.classList.add("active");
      }
      // Ellers aktivér om os link
    } else {
      if (link.getAttribute("href") === "/about") {
        link.classList.add("active");
      }
    }
  });
}

// Kører funktionen ved load
updateActiveNav();

// Kører funktionen når URL hash ændres
window.addEventListener("hashchange", updateActiveNav);
