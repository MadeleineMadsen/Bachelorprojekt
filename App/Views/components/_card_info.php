<div class="card-event-info">
    <div class="card-event-info-row">
        <div class="card-event-info-icon">
            <img src="/assets/img/icons/placering_icon.png" alt="" class="card-event-info-icon-img">
        </div>
        <div class="card-event-info-text">
            <span class="card-event-info-label">Lokation</span>
            <span class="card-event-info-value"><?= htmlspecialchars($event['event_location']) ?></span>
        </div>
    </div>
    <div class="card-event-info-row">
        <div class="card-event-info-icon">
            <img src="/assets/img/icons/ur_icon.png" alt="" class="card-event-info-icon-img">
        </div>
        <div class="card-event-info-text">
            <span class="card-event-info-label">Tid</span>
            <span class="card-event-info-value">
                <?= htmlspecialchars($event['dato']) ?><br>
                KL. <?= htmlspecialchars(substr($event['event_time'], 0, 5)) ?>
                <?php if (!empty($event['event_end_time'])): ?>–<?= htmlspecialchars(substr($event['event_end_time'], 0, 5)) ?><?php endif; ?>
            </span>
        </div>
    </div>
    <div class="card-event-info-row">
        <div class="card-event-info-icon">
            <img src="/assets/img/icons/person_icon.png" alt="" class="card-event-info-icon-img">
        </div>
        <div class="card-event-info-text">
            <span class="card-event-info-label">Deltagere</span>
            <span class="card-event-info-value"><?= htmlspecialchars($event['participant_count'] ?? '0') ?> deltager</span>
        </div>
    </div>
    <a href="#" class="btn btn-primary">Tilmeld dig eventet</a>
</div>
