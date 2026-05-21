const calendarDays  = document.querySelectorAll('.calendar-day');
const eventCards    = document.querySelectorAll('.mobile-event-card');
const eventList     = document.querySelector('.mobile-event-list');
const noEvents      = document.querySelector('.no-events');

calendarDays.forEach(day => {
    day.addEventListener('click', () => {
        const selectedDate = day.dataset.date;

        const isAlreadyOpen = document.querySelector(
            `.mobile-event-card.is-visible[data-event-date="${selectedDate}"]`
        );

        calendarDays.forEach(day => {
            day.classList.remove('is-selected');
        });

        eventCards.forEach(card => {
            card.classList.remove('is-visible');
        });

        noEvents.style.display = 'none';
        eventList.classList.remove('is-open');

        if (isAlreadyOpen) {
            return;
        }

        day.classList.add('is-selected');
        eventList.classList.add('is-open');

        let foundEvent = false;

        eventCards.forEach(card => {
            if (card.dataset.eventDate === selectedDate) {
                card.classList.add('is-visible');
                foundEvent = true;
            }
        });

        if (!foundEvent && selectedDate) {
            noEvents.style.display = 'block';
        }
    });
});

const gridEventPreviews = document.querySelectorAll('.desktop-event-preview');

gridEventPreviews.forEach(preview => {
    const slides = preview.querySelectorAll('.grid-event-slide');
    const prevBtn = preview.querySelector('.grid-event-arrow-left');
    const nextBtn = preview.querySelector('.grid-event-arrow-right');

    if (slides.length <= 1) {
        return;
    }

    let activeIndex = 0;

    function showSlide(index) {
        slides.forEach(slide => {
            slide.classList.remove('is-active');
        });

        slides[index].classList.add('is-active');
    }

    nextBtn.addEventListener('click', event => {
        event.stopPropagation();

        activeIndex++;

        if (activeIndex >= slides.length) {
            activeIndex = 0;
        }

        showSlide(activeIndex);
    });

    prevBtn.addEventListener('click', event => {
        event.stopPropagation();

        activeIndex--;

        if (activeIndex < 0) {
            activeIndex = slides.length - 1;
        }

        showSlide(activeIndex);
    });
});