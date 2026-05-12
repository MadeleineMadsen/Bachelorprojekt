<main>
    <div class="events-hero">
        <h1 class="events-hero-title">MINE EVENTS</h1>
    </div>



    <div class="events-filter">
        <h2 class="events-filter-title">TILMELDTE EVENTS</h2>
    </div>

    <section class="events-list">
        <?php if (empty($events)): ?>
            <p class="events-empty">Du er ikke tilmeldt nogen events endnu. <a href="/events">Se kommende events</a></p>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <?php include __DIR__ . '/components/_card_list.php'; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>
