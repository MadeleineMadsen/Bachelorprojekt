<?php
require_once __DIR__ . '/../../App/Models/EventModel.php';
$eventModel = new EventModel();
$events     = $eventModel->getLatest(3);
?>

<main class="container">


    <div class="grid">
        <?php foreach ($events as $event): ?>
            <?php include __DIR__ . '/components/_card_event_sm.php'; ?>
        <?php endforeach; ?>
    </div>

</main>
