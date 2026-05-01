
<main>

    <div class="events-hero container">
        <h1 class="events-hero-title">EVENTS</h1>
    </div>

    <div class="container">

        <div class="events-filter">
            <h2 class="events-filter-title">KOMMENDE EVENTS</h2>
            <div class="events-filter-controls">
                <select class="events-filter-select">
                    <option value="">KATEGORIER</option>
                    <option value="socialt">Socialt</option>
                    <option value="fest">Fest</option>
                    <option value="turnering">Turnering</option>
                </select>
                <div class="events-filter-search">
                    <input type="text" placeholder="SØG" class="events-filter-input">
                    <button type="button" class="events-filter-btn">
                        <img src="/assets/img/search_icon.png" alt="Søg">
                    </button>
                </div>
            </div>
        </div>

        <div class="events-list">
            <?php foreach ($events as $event): ?>
                <?php include __DIR__ . '/components/_card_list.php'; ?>
            <?php endforeach; ?>
        </div>

    </div>

</main>
