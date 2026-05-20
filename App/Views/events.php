<!-- Hero section -->
<section class="events-hero">
    <h1 class="events-hero-title">EVENTS</h1>
</section>

<!-- Banner -->
<?php
$bannerText = 'KOMMENDE EVENTS';
include __DIR__ . '/micro/___banner.php';
?>

<main>
    <section class="events-filter">
        <h2 class="events-filter-title">KOMMENDE EVENTS</h2>
        <div class="events-filter-controls">
            <div class="events-filter-select-wrap">
                <select class="events-filter-select">
                    <option value="" disabled selected hidden>KATEGORIER</option>
                    <option value="">ALLE</option>

                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['category_pk']) ?>">
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <img src="/assets/img/icons/arrow-down.svg" alt="" class="events-filter-arrow">
            </div>
            <div class="events-filter-search">
                <input type="text" placeholder="SØG" class="events-filter-input">
                <button type="button" class="events-filter-btn">
                    <img src="/assets/img/icons/search_icon.png" alt="Søg">
                </button>
            </div>
        </div>
    </section>

    <section class="events-list" id="events-list">
        <?php foreach ($events as $event): ?>
            <?php include __DIR__ . '/components/_card_list.php'; ?>
        <?php endforeach; ?>
    </section>

</main>