<!-- Eventinfo-boks -->
<aside class="card-event-info">

    <!-- Lokation -->
    <div class="card-event-info-row">
        <div class="card-event-info-icon">
            <img src="/assets/img/icons/location.svg" alt="Pin ikon" class="card-event-info-icon-img">
        </div>

        <div class="card-event-info-text">
            <span class="card-event-info-label">Lokation</span>

            <!-- Eventets lokation -->
            <span class="card-event-info-value"><?= htmlspecialchars($event['event_location']) ?></span>
        </div>
    </div>

    <!-- Dato og tidspunkt -->
    <div class="card-event-info-row">
        <div class="card-event-info-icon">
            <img src="/assets/img/icons/clock_icon.svg" alt="Ur ikon" class="card-event-info-icon-img">
        </div>

        <div class="card-event-info-text">
            <span class="card-event-info-label">Tid</span>

            <!-- Eventets dato, starttid og evt. sluttid -->
            <span class="card-event-info-value">
                <?= htmlspecialchars($event['dato']) ?><br>
                KL. <?= htmlspecialchars(substr($event['event_time'], 0, 5)) ?>
                <?php if (!empty($event['event_end_time'])): ?>–<?= htmlspecialchars(substr($event['event_end_time'], 0, 5)) ?><?php endif; ?>
            </span>
        </div>
    </div>

    <!-- Deltagerantal -->
    <div class="card-event-info-row">
        <div class="card-event-info-icon">
            <img src="/assets/img/icons/persons_icon.svg" alt="Person ikon" class="card-event-info-icon-img">
        </div>

        <div class="card-event-info-text">
            <span class="card-event-info-label">Deltagere</span>

            <!-- Antal tilmeldte deltagere -->
            <span class="card-event-info-value"><?= htmlspecialchars($event['participant_count'] ?? '0') ?>
                deltager</span>
        </div>
    </div>

    <!-- Tilmelding vises kun for brugere der er logget ind -->
    <?php if (isset($_SESSION['user'])): ?>
        <form method="POST" action="/event_register">

            <!-- CSRF-beskyttelse -->
            <?php csrf_input(); ?>

            <!-- Eventets id sendes med formularen -->
            <input type="hidden" name="event_id" value="<?= htmlspecialchars($event['event_pk']) ?>">

            <!-- Admin kan ikke tilmelde sig events -->
            <?php if (($_SESSION['user']['role_fk'] ?? null) == 1): ?>
                <button type="submit" class="btn btn-primary" disabled>Tilmeld dig eventet</button>

                <!-- Hvis brugeren allerede er tilmeldt, kan de framelde sig -->
            <?php elseif (!empty($isRegistered)): ?>
                <input type="hidden" name="action" value="unregister">
                <button type="submit" class="btn btn-secondary">Frameld dig eventet</button>

                <!-- Hvis brugeren ikke er tilmeldt, kan de tilmelde sig -->
            <?php else: ?>
                <input type="hidden" name="action" value="register">
                <button type="submit" class="btn btn-primary">Tilmeld dig eventet</button>
            <?php endif; ?>
        </form>

        <!-- Hvis brugeren ikke er logget ind, sendes de til login -->
    <?php else: ?>
        <a href="/login" class="btn btn-primary">Log ind for at tilmelde</a>
    <?php endif; ?>
</aside>