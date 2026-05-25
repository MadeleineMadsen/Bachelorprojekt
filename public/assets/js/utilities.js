// utilities.js
// Genbrugelige JavaScript funktioner
// Fx buttons, UI funktioner osv.

// ------------------------------------------------
// Medlemscarousel og filtrering
// ------------------------------------------------

const carousel = document.querySelector("#memberCarousel");
const nextBtn = document.querySelector(".carousel-next");
const prevBtn = document.querySelector(".carousel-prev");
const searchInput = document.querySelector("#memberSearch");
const educationFilter = document.querySelector("#educationFilter");
const memberSlides = document.querySelectorAll(".member-slide");

// Opdaterer synlighed på carousel-pile
function updateArrows() {
  if (!carousel || !prevBtn || !nextBtn) return;

  const visibleSlidesAmount = Number(carousel.dataset.visibleSlides) || 2;
  const visibleSlides = [...memberSlides].filter(
    (slide) => slide.style.display !== "none",
  );

  if (visibleSlides.length <= visibleSlidesAmount) {
    prevBtn.style.visibility = "hidden";
    nextBtn.style.visibility = "hidden";
    return;
  }

  const maxScrollLeft = carousel.scrollWidth - carousel.clientWidth;

  prevBtn.style.visibility = carousel.scrollLeft <= 1 ? "hidden" : "visible";
  nextBtn.style.visibility =
    carousel.scrollLeft >= maxScrollLeft - 1 ? "hidden" : "visible";
}

// Filtrerer medlemmer ud fra navn og uddannelse
function filterMemberElements(elements, resetCarousel = false) {
  if (!searchInput || !educationFilter) return;

  const searchValue = searchInput.value.toLowerCase().trim();
  const selectedEducation = educationFilter.value;

  elements.forEach((element) => {
    const name = element.dataset.name || "";
    const education = element.dataset.education || "";

    const matchesName = name.includes(searchValue);
    const matchesEducation =
      selectedEducation === "" || education === selectedEducation;

    element.style.display = matchesName && matchesEducation ? "" : "none";
  });

  if (resetCarousel && carousel) {
    carousel.scrollLeft = 0;
    updateArrows();
  }
}

// Filtrerer både carousel slides og medlemskort
function filterMembers() {
  const memberCards = document.querySelectorAll(".member-card");

  if (memberSlides.length > 0) {
    filterMemberElements(memberSlides, true);
  }

  if (memberCards.length > 0) {
    filterMemberElements(memberCards);
  }
}

// Finder bredden på et slide inkl. gap
function getSlideWidth() {
  if (!carousel) return 0;

  const slide = document.querySelector(
    '.member-slide:not([style*="display: none"])',
  );
  const gap = parseInt(getComputedStyle(carousel).gap) || 0;

  if (!slide) return 0;

  return slide.offsetWidth + gap;
}

// Næste slide
if (nextBtn && carousel) {
  nextBtn.addEventListener("click", () => {
    carousel.scrollBy({
      left: getSlideWidth(),
      behavior: "smooth",
    });

    setTimeout(updateArrows, 400);
  });
}

// Forrige slide
if (prevBtn && carousel) {
  prevBtn.addEventListener("click", () => {
    carousel.scrollBy({
      left: -getSlideWidth(),
      behavior: "smooth",
    });

    setTimeout(updateArrows, 400);
  });
}

// Event listeners til søgning og filter
if (searchInput) {
  searchInput.addEventListener("input", filterMembers);
}

if (educationFilter) {
  educationFilter.addEventListener("change", filterMembers);
}

// Opdaterer pile ved scroll, load og resize
if (carousel) {
  carousel.addEventListener("scroll", updateArrows);
  window.addEventListener("load", updateArrows);
  window.addEventListener("resize", updateArrows);
}

// ------------------------------------------------
// Toasts
// ------------------------------------------------

const toasts = document.querySelectorAll(".toast");

toasts.forEach((toast) => {
  const closeBtn = toast.querySelector(".toast-close");

  // Lukker toast med animation
  const closeToast = () => {
    toast.classList.add("hide");

    setTimeout(() => {
      toast.remove();
    }, 300);
  };

  closeBtn?.addEventListener("click", closeToast);

  // Lukker automatisk efter 4 sekunder
  setTimeout(closeToast, 4000);
});

// ------------------------------------------------
// Custom modals
// ------------------------------------------------

const openModalButtons = document.querySelectorAll("[data-modal-open]");
const closeModalButtons = document.querySelectorAll("[data-modal-close]");

// Åbner modal
openModalButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const modalId = button.dataset.modalOpen;
    const modal = document.getElementById(modalId);

    modal?.classList.add("show");
  });
});

// Lukker modal via knap
closeModalButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const modal = button.closest(".modal-overlay");

    modal?.classList.remove("show");
  });
});

// Lukker modal ved klik på overlay
document.addEventListener("click", (event) => {
  if (event.target.classList.contains("modal-overlay")) {
    event.target.classList.remove("show");
  }
});
