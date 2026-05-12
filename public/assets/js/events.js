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
