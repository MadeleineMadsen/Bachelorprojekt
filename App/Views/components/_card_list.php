<article class="card-event-list" data-category="<?= htmlspecialchars($event['category_fk'] ?? '') ?>"
    data-title="<?= htmlspecialchars(strtolower($event['event_title'])) ?>">
    <?php
    $imgRaw = $event['event_image'] ?? '';
    $imgSrc = !empty($imgRaw)
        ? (str_starts_with($imgRaw, '/') ? $imgRaw : '/assets/img/' . $imgRaw)
        : null;
    ?>
    <div class="card-event-list-img-wrap <?= $imgSrc ? '' : 'card-event-list-img-wrap--no-img' ?>">
        <div class="card-event-list-date">
            <span class="card-event-list-day"><?= htmlspecialchars($event['date_day']) ?></span>
            <span class="card-event-list-month"><?= htmlspecialchars($event['date_month_da']) ?></span>
        </div>
        <?php if ($imgSrc): ?>
        <img src="<?= htmlspecialchars($imgSrc) ?>"
            alt="Billede af eventet" class="card-event-list-img"
            onerror="this.style.display='none'; this.parentElement.classList.add('card-event-list-img-wrap--no-img');">
        <?php endif; ?>
    </div>
    <div class="card-event-list-body">
        <div class="card-event-list-category">
            <span class="card-event-list-tag"><?= htmlspecialchars($event['category_name'] ?? '') ?></span>
        </div>
        <h3 class="card-event-list-title"><?= htmlspecialchars($event['event_title']) ?></h3>
        <p class="card-event-list-desc"><?= htmlspecialchars($event['event_description']) ?></p>
        <div class="card-event-list-meta">
            <div class="card-event-list-meta-items">
                <span class="card-event-list-meta-item">
                    <img src="/assets/img/icons/ur_icon.png" alt="Ur ikon" class="card-event-list-meta-icon">
                    <?= htmlspecialchars(substr($event['event_time'], 0, 5)) ?>
                    <?php if (!empty($event['event_end_time'])): ?>-
                        <?= htmlspecialchars(substr($event['event_end_time'], 0, 5)) ?><?php endif; ?>
                </span>
                <span class="card-event-list-meta-item">
                    <img src="/assets/img/icons/placering_icon.png" alt="Pin ikon" class="card-event-list-meta-icon">
                    <?= htmlspecialchars($event['event_location']) ?>
                </span>
                <span class="card-event-list-meta-item">
                    <img src="/assets/img/icons/person_icon.png" alt="Person ikon" class="card-event-list-meta-icon">
                    <?= htmlspecialchars($event['participant_count'] ?? '0') ?> deltagere
                </span>
            </div>
            <div class="card-event-list-actions">
                <a href="/event_page?id=<?= htmlspecialchars($event['event_pk']) ?>"
                    class="btn <?= (isset($isAdmin) && $isAdmin) ? 'btn-primary' : 'btn-secondary' ?> card-event-list-link"><?= (isset($isAdmin) && $isAdmin) ? 'Se event' : 'Læs mere' ?></a>
                <?php if (isset($isAdmin) && $isAdmin): ?>
                    <a href="/event_edit?id=<?= htmlspecialchars($event['event_pk']) ?>"
                        class="btn btn-secondary">Rediger</a>
                    <form id="deleteEventForm-<?= htmlspecialchars($event['event_pk']) ?>" method="POST"
                        action="/event_delete">
                        <?php csrf_input(); ?>
                        <input type="hidden" name="event_id" value="<?= htmlspecialchars($event['event_pk']) ?>">
                        <button type="button" class="btn btn-delete"
                            data-modal-open="deleteEventModal-<?= htmlspecialchars($event['event_pk']) ?>">
                            Slet
                        </button>
                    </form>

                    <?php
                    $modalId = 'deleteEventModal-' . $event['event_pk'];
                    $formId = 'deleteEventForm-' . $event['event_pk'];
                    $title = 'Slet event?';
                    $text = 'Er du sikker på, at du vil slette dette event? Deltagere får besked på mail.';
                    $confirmText = 'Ja, slet event';

                    include __DIR__ . '/../micro/___confirm_modal.php';
                    ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
</article>