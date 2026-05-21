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

        <div class="filter-container">
            <form class="search-form" action="" onsubmit="return false;">

                <div class="search-field">
                    <input type="text" placeholder="SØG">

                    <button type="button" aria-label="Søg">
                        <img src="/assets/img/icons/search_icon.png" alt="">
                    </button>
                </div>

                <select class="filter-select">
                    <option value="" disabled selected hidden>KATEGORIER</option>
                    <option value="">ALLE</option>

                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['category_pk']) ?>">
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endforeach; ?>

                    <img src="/assets/img/icons/arrow-down.svg" alt="" class="education-filter-arrow">
                </select>
                <div class="events-filter-search">
                    <input type="text" placeholder="SØG" class="events-filter-input">
                    <button type="button" class="events-filter-btn">
                        <img src="/assets/img/icons/search_icon.png" alt="Søg ikon">
                    </button>
                </div>
            </div>
        </div>
    </section>

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