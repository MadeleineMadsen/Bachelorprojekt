const calendarDays = document.querySelectorAll('.calendar-day');
const eventCards = document.querySelectorAll('.mobile-event-card');
const noEvents = document.querySelector('.no-events');

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

        if (isAlreadyOpen) {
            return;
        }

        day.classList.add('is-selected');

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