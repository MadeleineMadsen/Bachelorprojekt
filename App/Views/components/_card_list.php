<article class="card-event-list">
    <div class="card-event-list__date">
        <span class="card-event-list__day"><?= htmlspecialchars(date('d', strtotime($event['event_date']))) ?></span>
        <span class="card-event-list__month"><?= htmlspecialchars(strtoupper(date('M', strtotime($event['event_date'])))) ?></span>
    </div>
    <img
        src="/assets/img/<?= htmlspecialchars($event['event_image']) ?>"
        alt="<?= htmlspecialchars($event['event_title']) ?>"
        class="card-event-list__img"
    >
    <div class="card-event-list__body">
        <span class="card-event-list__tag"><?= htmlspecialchars($event['event_category'] ?? '') ?></span>
        <h3 class="card-event-list__title"><?= htmlspecialchars($event['event_title']) ?></h3>
        <p class="card-event-list__desc"><?= htmlspecialchars($event['event_description']) ?></p>
        <div class="card-event-list__meta">
            <span class="card-event-list__meta-item">
                <img src="/assets/img/ur_icon.png" alt="" class="card-event-list__meta-icon">
                <?= htmlspecialchars(substr($event['event_time'], 0, 5)) ?>
                <?php if (!empty($event['event_end_time'])): ?>- <?= htmlspecialchars(substr($event['event_end_time'], 0, 5)) ?><?php endif; ?>
            </span>
            <span class="card-event-list__meta-item">
                <img src="/assets/img/placering_icon.png" alt="" class="card-event-list__meta-icon">
                <?= htmlspecialchars($event['event_location']) ?>
            </span>
            <span class="card-event-list__meta-item">
                <img src="/assets/img/person_icon.png" alt="" class="card-event-list__meta-icon">
                <?= htmlspecialchars($event['participant_count'] ?? '0') ?> deltagere
            </span>
        </div>
        <a href="/eventside?id=<?= htmlspecialchars($event['event_pk']) ?>" class="btn btn-nav">Læs mere</a>
    </div>
</article>
