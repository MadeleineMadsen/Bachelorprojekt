<article class="card-event-list">
    <div class="card-event-list-img-wrap">
        <div class="card-event-list-date">
            <span class="card-event-list-day"><?= htmlspecialchars($event['date_day']) ?></span>
            <span class="card-event-list-month"><?= htmlspecialchars($event['date_month_da']) ?></span>
        </div>
        <img
            src="/assets/img/<?= htmlspecialchars($event['event_image']) ?>"
            alt="<?= htmlspecialchars($event['event_title']) ?>"
            class="card-event-list-img"
        >
    </div>
    <div class="card-event-list-body">
        <div class="card-event-list-category">
            <span class="card-event-list-tag"><?= htmlspecialchars($event['event_category'] ?? '') ?></span>
        </div>
        <h3 class="card-event-list-title"><?= htmlspecialchars($event['event_title']) ?></h3>
        <p class="card-event-list-desc"><?= htmlspecialchars($event['event_description']) ?></p>
        <div class="card-event-list-meta">
            <div class="card-event-list-meta-items">
                <span class="card-event-list-meta-item">
                    <img src="/assets/img/ur_icon.png" alt="" class="card-event-list-meta-icon">
                    <?= htmlspecialchars(substr($event['event_time'], 0, 5)) ?>
                    <?php if (!empty($event['event_end_time'])): ?>- <?= htmlspecialchars(substr($event['event_end_time'], 0, 5)) ?><?php endif; ?>
                </span>
                <span class="card-event-list-meta-item">
                    <img src="/assets/img/placering_icon.png" alt="" class="card-event-list-meta-icon">
                    <?= htmlspecialchars($event['event_location']) ?>
                </span>
                <span class="card-event-list-meta-item">
                    <img src="/assets/img/person_icon.png" alt="" class="card-event-list-meta-icon">
                    <?= htmlspecialchars($event['participant_count'] ?? '0') ?> deltagere
                </span>
            </div>
            <a href="/eventside?id=<?= htmlspecialchars($event['event_pk']) ?>" class="btn btn-secondary">Læs mere</a>
        </div>
    </div>
</article>
