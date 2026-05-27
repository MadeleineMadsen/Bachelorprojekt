<!-- Events side -->
<main class="events-page">

    <!-- Hero sektion -->
    <section class="events-hero">
        <h1 class="events-hero-title">EVENTS</h1>
    </section>

    <!-- Banner -->
    <?php
    $bannerText = 'KOMMENDE EVENTS';
    include __DIR__ . '/micro/___banner.php';
    ?>

    <!-- Filter og søgning -->
    <section class="events-filter">
        <h2 class="events-filter-title">KOMMENDE EVENTS</h2>

        <div class="filter-container">

            <!-- Søge- og filterformular -->
            <form class="search-form" action="" onsubmit="return false;">

                <!-- Søgefelt -->
                <div class="search-field">
                    <input type="text" placeholder="SØG">

                    <button type="button" aria-label="Søg">
                        <img src="/assets/img/icons/search.svg" alt="">
                    </button>
                </div>

                <!-- Kategori filter -->
                <select class="filter-select">
                    <option value="" disabled selected hidden>KATEGORIER</option>
                    <option value="">ALLE</option>

                    <!-- Loop gennem kategorier -->
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['category_pk']) ?>">
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endforeach; ?>

                    <img src="/assets/img/icons/arrow-down.svg" alt="" class="education-filter-arrow">
                </select>
            </form>
        </div>
    </section>

    <!-- Event liste -->
    <section class="events-list" id="events-list">

        <!-- Vis besked hvis der ikke findes events -->
        <?php if (empty($events)): ?>
            <p class="events-empty">Ingen events endnu</p>
        <?php endif; ?>

        <!-- Loop gennem events -->
        <?php foreach ($events as $event): ?>

            <!-- Eventkort -->
            <?php include __DIR__ . '/components/_card_list.php'; ?>
        <?php endforeach; ?>
    </section>

    <!-- Pagination -->
    <div class="events-pagination" id="events-pagination"></div>
</main>