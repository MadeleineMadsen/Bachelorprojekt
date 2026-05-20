const select     = document.querySelector('.events-filter-select');
const input      = document.querySelector('.events-filter-input');
const cards      = Array.from(document.querySelectorAll('.card-event-list'));
const pagination = document.getElementById('events-pagination');

const EVENTS_PER_PAGE = 6;
let currentPage = 1;

function getFilteredCards() {
    const category = select ? select.value : '';
    const search   = input  ? input.value.toLowerCase().trim() : '';
    return cards.filter(card => {
        const matchCategory = !category || card.dataset.category === category;
        const matchSearch   = !search   || card.dataset.title.includes(search);
        return matchCategory && matchSearch;
    });
}

function renderPage() {
    const filtered   = getFilteredCards();
    const totalPages = Math.ceil(filtered.length / EVENTS_PER_PAGE) || 1;

    if (currentPage > totalPages) currentPage = totalPages;

    const start = (currentPage - 1) * EVENTS_PER_PAGE;
    const end   = start + EVENTS_PER_PAGE;

    cards.forEach(card => { card.style.display = 'none'; });
    filtered.slice(start, end).forEach(card => { card.style.display = ''; });

    renderPagination(totalPages);
}

function getPageNumbers(current, total) {
    if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);

    if (current <= 4) {
        return [1, 2, 3, 4, 5, '...', total];
    } else if (current >= total - 3) {
        return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
    } else {
        return [1, '...', current - 1, current, current + 1, '...', total];
    }
}

function renderPagination(totalPages) {
    if (!pagination) return;

    if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }

    let html = '';

    html += `<button class="pagination-btn pagination-prev" ${currentPage === 1 ? 'disabled' : ''} aria-label="Forrige side">&#8249;</button>`;

    getPageNumbers(currentPage, totalPages).forEach(p => {
        if (p === '...') {
            html += `<span class="pagination-ellipsis">&hellip;</span>`;
        } else {
            html += `<button class="pagination-btn pagination-page${p === currentPage ? ' active' : ''}" data-page="${p}">${p}</button>`;
        }
    });

    html += `<button class="pagination-btn pagination-next" ${currentPage === totalPages ? 'disabled' : ''} aria-label="Næste side">&#8250;</button>`;

    pagination.innerHTML = html;

    pagination.querySelector('.pagination-prev').addEventListener('click', () => {
        if (currentPage > 1) { currentPage--; renderPage(); scrollToList(); }
    });
    pagination.querySelector('.pagination-next').addEventListener('click', () => {
        if (currentPage < totalPages) { currentPage++; renderPage(); scrollToList(); }
    });
    pagination.querySelectorAll('.pagination-page').forEach(btn => {
        btn.addEventListener('click', () => {
            currentPage = Number(btn.dataset.page);
            renderPage();
            scrollToList();
        });
    });
}

function scrollToList() {
    const list = document.getElementById('events-list');
    if (list) list.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

if (select) select.addEventListener('change', () => { currentPage = 1; renderPage(); });
if (input)  input.addEventListener('input',  () => { currentPage = 1; renderPage(); });

renderPage();
