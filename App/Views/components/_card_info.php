<aside class="card-event-info">

    <div class="card-event-info-row">
        <div class="card-event-info-icon">
            <img src="/assets/img/icons/placering_icon.png" alt="Pin ikon" class="card-event-info-icon-img">
        </div>

        <div class="card-event-info-text">
            <span class="card-event-info-label">Lokation</span>

            <span class="card-event-info-value"><?= htmlspecialchars($event['event_location']) ?></span>
        </div>
    </div>

    <div class="card-event-info-row">
        <div class="card-event-info-icon">
            <img src="/assets/img/icons/ur_icon.png" alt="Ur ikon" class="card-event-info-icon-img">
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
            <img src="/assets/img/icons/person_icon.png" alt="Person ikon" class="card-event-info-icon-img">
        </div>

        <div class="card-event-info-text">
            <span class="card-event-info-label">Deltagere</span>

            <span class="card-event-info-value"><?= htmlspecialchars($event['participant_count'] ?? '0') ?>
                deltager</span>
        </div>
    </div>

    <?php if (isset($_SESSION['user'])): ?>
        <form method="POST" action="/event_register">
            <?php csrf_input(); ?>
            <input type="hidden" name="event_id" value="<?= htmlspecialchars($event['event_pk']) ?>">
            <?php if (($_SESSION['user']['role_fk'] ?? null) == 1): ?>
                <button type="submit" class="btn btn-primary" disabled>Tilmeld dig eventet</button>
            <?php elseif (!empty($isRegistered)): ?>
                <input type="hidden" name="action" value="unregister">
                <button type="submit" class="btn btn-secondary">Frameld dig eventet</button>
            <?php else: ?>
                <input type="hidden" name="action" value="register">
                <button type="submit" class="btn btn-primary">Tilmeld dig eventet</button>
            <?php endif; ?>
        </form>

    <?php else: ?>
        <a href="/login" class="btn btn-primary">Log ind for at tilmelde</a>
    <?php endif; ?>
</aside>