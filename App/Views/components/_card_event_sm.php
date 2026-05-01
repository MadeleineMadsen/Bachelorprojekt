<article class="card-event-sm">

    <div class="card-event-sm-img-wrap">
        <div class="card-event-sm-date">
            <span class="card-event-sm-day"><?= htmlspecialchars($event['date_day']) ?></span>
            <span class="card-event-sm-month"><?= htmlspecialchars($event['date_month_da']) ?></span>
        </div>
        <img
            src="/assets/img/<?= htmlspecialchars($event['event_image']) ?>"
            alt="<?= htmlspecialchars($event['event_title']) ?>"
            class="card-event-sm-img"
        >
    </div>

    <div class="card-event-sm-body">

        <?php if (!empty($event['event_category'])): ?>
            <div class="card-event-sm-category">
                <span class="card-event-sm-tag"><?= htmlspecialchars($event['event_category']) ?></span>
            </div>
        <?php endif; ?>

        <h3 class="card-event-sm-title"><?= htmlspecialchars($event['event_title']) ?></h3>

        <p class="card-event-sm-desc"><?= htmlspecialchars($event['event_description']) ?></p>

        <div class="card-event-sm-meta">
            <div class="card-event-sm-meta-items">
                <span class="card-event-sm-meta-item">
                    <img src="/assets/img/ur_icon.png" alt="" class="card-event-sm-meta-icon">
                    <?= htmlspecialchars(substr($event['event_time'], 0, 5)) ?>
                    <?php if (!empty($event['event_end_time'])): ?>- <?= htmlspecialchars(substr($event['event_end_time'], 0, 5)) ?><?php endif; ?>
                </span>
                <span class="card-event-sm-meta-item">
                    <img src="/assets/img/placering_icon.png" alt="" class="card-event-sm-meta-icon">
                    <?= htmlspecialchars($event['event_location']) ?>
                </span>
                <span class="card-event-sm-meta-item">
                    <img src="/assets/img/person_icon.png" alt="" class="card-event-sm-meta-icon">
                    <?= htmlspecialchars($event['participant_count'] ?? '0') ?> deltagere
                </span>
            </div>
            <a href="/eventside?id=<?= htmlspecialchars($event['event_pk']) ?>" class="btn btn-secondary">Læs mere</a>
        </div>

    </div>

</article>
