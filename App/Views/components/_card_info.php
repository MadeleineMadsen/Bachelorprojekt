<div class="card-event-info">
    <div class="card-event-info__row">
        <div class="card-event-info__icon">
            <img src="/assets/img/placering_icon.png" alt="" class="card-event-info__icon-img">
        </div>
        <div class="card-event-info__text">
            <span class="card-event-info__label">Lokation</span>
            <span class="card-event-info__value"><?= htmlspecialchars($event['event_location']) ?></span>
        </div>
    </div>
    <div class="card-event-info__row">
        <div class="card-event-info__icon">
            <img src="/assets/img/ur_icon.png" alt="" class="card-event-info__icon-img">
        </div>
        <div class="card-event-info__text">
            <span class="card-event-info__label">Tid</span>
            <span class="card-event-info__value">
                <?= htmlspecialchars(date('l d. F', strtotime($event['event_date']))) ?><br>
                KL. <?= htmlspecialchars(substr($event['event_time'], 0, 5)) ?>
                <?php if (!empty($event['event_end_time'])): ?>- <?= htmlspecialchars(substr($event['event_end_time'], 0, 5)) ?><?php endif; ?>
            </span>
        </div>
    </div>
    <div class="card-event-info__row">
        <div class="card-event-info__icon">
            <img src="/assets/img/person_icon.png" alt="" class="card-event-info__icon-img">
        </div>
        <div class="card-event-info__text">
            <span class="card-event-info__label">Deltagere</span>
            <span class="card-event-info__value"><?= htmlspecialchars($event['participant_count'] ?? '0') ?> deltager</span>
        </div>
    </div>
    <a href="#" class="btn btn-primary">Tilmeld dig eventet</a>
</div>
