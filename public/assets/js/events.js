const select = document.querySelector('.events-filter-select');
const input  = document.querySelector('.events-filter-input');
const cards  = document.querySelectorAll('.card-event-list');

if (select && input && cards.length) {
    function filterEvents() {
        const category = select.value;
        const search   = input.value.toLowerCase().trim();

        cards.forEach(card => {
            const matchCategory = !category || card.dataset.category === category;
            const matchSearch   = !search   || card.dataset.title.includes(search);
            card.style.display  = matchCategory && matchSearch ? '' : 'none';
        });
    }

    select.addEventListener('change', filterEvents);
    input.addEventListener('input', filterEvents);
}

// Validerings besked af glemt billede i event-oprettelse
// Valideringsbesked af glemt billede i event-oprettelse
const eventForm = document.getElementById('eventForm');

if (eventForm) {
    eventForm.addEventListener('submit', function (e) {
        const imageInput = document.getElementById('profile_image');
        const imageError = document.getElementById('imageError');

        if (!imageInput.files || imageInput.files.length === 0) {
            e.preventDefault();

            imageError.style.display = 'block';
            imageInput.closest('.upload-box').classList.add('input-error');

            return false;
        }

        imageError.style.display = 'none';
        imageInput.closest('.upload-box').classList.remove('input-error');
    });
}