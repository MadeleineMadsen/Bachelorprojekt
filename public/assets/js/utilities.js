// utilities.js
// Genbrugelige JavaScript funktioner
// Fx buttons, UI funktioner osv.

// Karusel til billeder af members
const carousel = document.querySelector('#memberCarousel');
const nextBtn = document.querySelector('.carousel-next');
const prevBtn = document.querySelector('.carousel-prev');
const searchInput = document.querySelector('#memberSearch');
const educationFilter = document.querySelector('#educationFilter');
const memberSlides = document.querySelectorAll('.member-slide');

function updateArrows() {
    const visibleSlidesAmount = Number(carousel.dataset.visibleSlides) || 2;

    const visibleSlides = [...memberSlides].filter(slide => slide.style.display !== 'none');

    if (visibleSlides.length <= visibleSlidesAmount) {
        prevBtn.style.visibility = 'hidden';
        nextBtn.style.visibility = 'hidden';
        return;
    }

    const maxScrollLeft = carousel.scrollWidth - carousel.clientWidth;

    prevBtn.style.visibility = carousel.scrollLeft <= 1 ? 'hidden' : 'visible';
    nextBtn.style.visibility = carousel.scrollLeft >= maxScrollLeft - 1 ? 'hidden' : 'visible';
}

function filterMembers() {
    const searchValue = searchInput.value.toLowerCase().trim();
    const selectedEducation = educationFilter.value.toLowerCase();

    memberSlides.forEach(slide => {
        const name = slide.dataset.name;
        const education = slide.dataset.education;

        const matchesName = name.includes(searchValue);
        const matchesEducation = selectedEducation === '' || education === selectedEducation;

        slide.style.display = matchesName && matchesEducation ? '' : 'none';
    });

    carousel.scrollLeft = 0;
    updateArrows();
}

function getSlideWidth() {
    const slide = document.querySelector('.member-slide:not([style*="display: none"])');
    const gap = parseInt(getComputedStyle(carousel).gap) || 0;

    return slide.offsetWidth + gap;
}

nextBtn.addEventListener('click', () => {
    carousel.scrollBy({
        left: getSlideWidth(),
        behavior: 'smooth'
    });

    setTimeout(updateArrows, 400);
});

prevBtn.addEventListener('click', () => {
    carousel.scrollBy({
        left: -getSlideWidth(),
        behavior: 'smooth'
    });

    setTimeout(updateArrows, 400);
});

if (searchInput) {
    searchInput.addEventListener('input', filterMembers);
}

if (educationFilter) {
    educationFilter.addEventListener('change', filterMembers);
}

carousel.addEventListener('scroll', updateArrows);
window.addEventListener('load', updateArrows);
window.addEventListener('resize', updateArrows);