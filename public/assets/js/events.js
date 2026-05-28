// ------------------------------------------------
// Event filtrering og pagination
// ------------------------------------------------

// Henter elementer
const select = document.querySelector(".events-filter-select");
const input = document.querySelector(".events-filter-input");
const cards = Array.from(document.querySelectorAll(".card-event-list"));
const pagination = document.getElementById("events-pagination");

// Antal events per side
const EVENTS_PER_PAGE = 6;
let currentPage = 1;

// Finder events der matcher søgning og kategori
function getFilteredCards() {
  const category = select ? select.value : "";
  const search = input ? input.value.toLowerCase().trim() : "";
  return cards.filter((card) => {
    const matchCategory = !category || card.dataset.category === category;
    const matchSearch = !search || card.dataset.title.includes(search);
    return matchCategory && matchSearch;
  });
}

// Viser den aktuelle side med events
function renderPage() {
  const filtered = getFilteredCards();
  const totalPages = Math.ceil(filtered.length / EVENTS_PER_PAGE) || 1;

  if (currentPage > totalPages) currentPage = totalPages;

  const start = (currentPage - 1) * EVENTS_PER_PAGE;
  const end = start + EVENTS_PER_PAGE;

  // Skjuler alle kort
  cards.forEach((card) => {
    card.style.display = "none";
  });

  // Viser kun kort for den aktuelle side
  filtered.slice(start, end).forEach((card) => {
    card.style.display = "";
  });

  renderPagination(totalPages);
}

// Opretter sidetal med ellipsis
function getPageNumbers(current, total) {
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);

  if (current <= 4) {
    return [1, 2, 3, 4, 5, "...", total];
  } else if (current >= total - 3) {
    return [1, "...", total - 4, total - 3, total - 2, total - 1, total];
  } else {
    return [1, "...", current - 1, current, current + 1, "...", total];
  }
}

// Renderer pagination knapper
function renderPagination(totalPages) {
  if (!pagination) return;

  if (totalPages <= 1) {
    pagination.innerHTML = "";
    return;
  }

  let html = "";

  // Forrige side
  html += `<button class="pagination-btn pagination-prev" ${currentPage === 1 ? "disabled" : ""} aria-label="Forrige side"><img src="/assets/img/icons/arrow-left.svg" alt="Forrige side"></button>`;

  // Sidetal
  getPageNumbers(currentPage, totalPages).forEach((p) => {
    if (p === "...") {
      html += `<span class="pagination-ellipsis">&hellip;</span>`;
    } else {
      html += `<button class="pagination-btn pagination-page${p === currentPage ? " active" : ""}" data-page="${p}">${p}</button>`;
    }
  });

  // Næste side
  html += `<button class="pagination-btn pagination-next" ${currentPage === totalPages ? "disabled" : ""} aria-label="Næste side"><img src="/assets/img/icons/arrow-right.svg" alt="Næste side"></button>`;

  pagination.innerHTML = html;

  // Klik på forrige side
  pagination.querySelector(".pagination-prev").addEventListener("click", () => {
    if (currentPage > 1) {
      currentPage--;
      renderPage();
      scrollToList();
    }
  });

  // Klik på næste side
  pagination.querySelector(".pagination-next").addEventListener("click", () => {
    if (currentPage < totalPages) {
      currentPage++;
      renderPage();
      scrollToList();
    }
  });

  // Klik på specifikt sidetal
  pagination.querySelectorAll(".pagination-page").forEach((btn) => {
    btn.addEventListener("click", () => {
      currentPage = Number(btn.dataset.page);
      renderPage();
      scrollToList();
    });
  });
}

// Scroller tilbage til eventlisten
function scrollToList() {
  const list = document.getElementById("events-list");
  if (list) list.scrollIntoView({ behavior: "smooth", block: "start" });
}

// ------------------------------------------------
// Validering af billede ved event-oprettelse
// ------------------------------------------------

const eventForm = document.getElementById("eventForm");

if (eventForm) {
  eventForm.addEventListener("submit", function (e) {
    const imageInput = document.getElementById("profile_image");
    const imageError = document.getElementById("imageError");

    const uploadPreview = document.getElementById("uploadPreview");
    const hasExistingImage = uploadPreview && uploadPreview.style.display !== "none" && uploadPreview.src !== "";

    // Stop formularen hvis der ikke er uploadet billede og der ikke er et eksisterende
    if (!hasExistingImage && (!imageInput.files || imageInput.files.length === 0)) {
      e.preventDefault();

      imageError.style.display = "block";
      imageInput.closest(".upload-box").classList.add("input-error");

      return false;
    }

    // Fjerner fejl hvis billede er valgt
    imageError.style.display = "none";
    imageInput.closest(".upload-box").classList.remove("input-error");
  });
}

// ------------------------------------------------
// Event listeners til filter og søgning
// ------------------------------------------------

if (select)
  select.addEventListener("change", () => {
    currentPage = 1;
    renderPage();
  });

if (input)
  input.addEventListener("input", () => {
    currentPage = 1;
    renderPage();
  });

// Initial visning
renderPage();
