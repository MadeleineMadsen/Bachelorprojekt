<main class="container">


    <div class="grid">
        <?php foreach ($events as $event): ?>
            <?php include __DIR__ . '/components/_card_event_sm.php'; ?>
        <?php endforeach; ?>
    </div>

</main>
