
<main>

    <div class="events-hero">
        <h1 class="events-hero-title">EVENTS</h1>
    </div>

    <?php
    $bannerText = '  KOMMENDE EVENTS';
    include __DIR__ . '/micro/___banner.php';
    ?>

        <div class="events-filter">
            <h2 class="events-filter-title">KOMMENDE EVENTS</h2>
            <div class="events-filter-controls">
                <select class="events-filter-select">
                    <option value="" disabled selected hidden>KATEGORIER</option>
                    <option value="">ALLE</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['category_pk']) ?>">
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="events-filter-search">
                    <input type="text" placeholder="SØG" class="events-filter-input">
                    <button type="button" class="events-filter-btn">
                        <img src="/assets/img/icons/search_icon.png" alt="Søg">
                    </button>
                </div>
            </div>
        </div>

        <div class="events-list" id="events-list">
            <?php if (empty($events)): ?>
                <p class="events-empty">Ingen events endnu</p>
            <?php endif; ?>
            <?php foreach ($events as $event): ?>
                <?php include __DIR__ . '/components/_card_list.php'; ?>
            <?php endforeach; ?>
        </div>

        <div class="events-pagination" id="events-pagination"></div>

</main>

