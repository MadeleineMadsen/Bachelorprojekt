// ------------------------------------------------
// Kalender - mobil eventvisning
// ------------------------------------------------

// Henter elementer
const calendarDays = document.querySelectorAll(".calendar-day");
const eventCards = document.querySelectorAll(".mobile-event-card");
const eventList = document.querySelector(".mobile-event-list");
const noEvents = document.querySelector(".no-events");

// Klik på kalenderdag
calendarDays.forEach((day) => {
  day.addEventListener("click", () => {
    const selectedDate = day.dataset.date;

    // Tjekker om dagens events allerede er åbne
    const isAlreadyOpen = document.querySelector(
      `.mobile-event-card.is-visible[data-event-date="${selectedDate}"]`,
    );

    // Fjerner valgt styling fra alle dage
    calendarDays.forEach((day) => {
      day.classList.remove("is-selected");
    });

    // Skjuler alle eventkort
    eventCards.forEach((card) => {
      card.classList.remove("is-visible");
    });

    // Skjuler "ingen events"
    noEvents.style.display = "none";

    // Lukker eventlisten
    eventList.classList.remove("is-open");

    // Stop hvis samme dag klikkes igen
    if (isAlreadyOpen) {
      return;
    }

    // Marker valgt dag
    day.classList.add("is-selected");

    // Åbn eventlisten
    eventList.classList.add("is-open");

    let foundEvent = false;

    // Vis events for valgt dato
    eventCards.forEach((card) => {
      if (card.dataset.eventDate === selectedDate) {
        card.classList.add("is-visible");
        foundEvent = true;
      }
    });

    // Vis besked hvis ingen events findes
    if (!foundEvent && selectedDate) {
      noEvents.style.display = "block";
    }
  });
});

// ------------------------------------------------
// Desktop event preview slider
// ------------------------------------------------

// Henter previews i kalender grid
const gridEventPreviews = document.querySelectorAll(".desktop-event-preview");

// Loop gennem previews
gridEventPreviews.forEach((preview) => {
  const slides = preview.querySelectorAll(".grid-event-slide");
  const prevBtn = preview.querySelector(".grid-event-arrow-left");
  const nextBtn = preview.querySelector(".grid-event-arrow-right");

  // Stop hvis der kun er ét slide
  if (slides.length <= 1) {
    return;
  }

  let activeIndex = 0;

  // Vis aktivt slide
  function showSlide(index) {
    slides.forEach((slide) => {
      slide.classList.remove("is-active");
    });

    slides[index].classList.add("is-active");
  }

  // Næste slide
  nextBtn.addEventListener("click", (event) => {
    event.stopPropagation();

    activeIndex++;

    // Start forfra ved sidste slide
    if (activeIndex >= slides.length) {
      activeIndex = 0;
    }

    showSlide(activeIndex);
  });

  // Forrige slide
  prevBtn.addEventListener("click", (event) => {
    event.stopPropagation();

    activeIndex--;

    // Hop til sidste slide hvis index går under 0
    if (activeIndex < 0) {
      activeIndex = slides.length - 1;
    }

    showSlide(activeIndex);
  });
});
