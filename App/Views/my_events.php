<!-- Mine events side -->
<main class="my-events-page">

    <!-- Hero sektion -->
    <section class="my-events-hero">
        <h1 class="my-events-hero-title">MINE EVENTS</h1>
    </section>

    <!-- Overskrift -->
    <section class="my-events-filter">
        <h2 class="events-filter-title">TILMELDTE EVENTS</h2>
    </section>

    <!-- Liste med events -->
    <section class="events-list">

        <!-- Vis besked hvis brugeren ikke er tilmeldt events -->
        <?php if (empty($events)): ?>
            <p class="events-empty">Du er ikke tilmeldt nogen events endnu.</p>
            <a href="/events" class="btn btn-secondary">SE EVENTS</a>
        <?php else: ?>

            <!-- Loop gennem brugerens events -->
            <?php foreach ($events as $event): ?>

                <!-- Eventkort -->
                <?php include __DIR__ . '/components/_card_list.php'; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>