<?php
$event = $event ?? [];
?>

<!-- Eventkort til eventoversigt -->
<article class="card-event-list" data-category="<?= htmlspecialchars($event['category_fk'] ?? '') ?>"
    data-title="<?= htmlspecialchars(strtolower($event['event_title'])) ?>">

    <?php
    // Finder eventets billede, hvis der er uploadet et
    $imgRaw = $event['event_image'] ?? '';

    // Opretter korrekt billedsti, uanset om stien allerede starter med /
    $imgSrc = !empty($imgRaw)
        ? (str_starts_with($imgRaw, '/') ? $imgRaw : '/assets/img/' . $imgRaw)
        : null;
    ?>

    <!-- Wrapper til billede og datobadge -->
    <div class="card-event-list-img-wrap <?= $imgSrc ? '' : 'card-event-list-img-wrap--no-img' ?>">

        <!-- Dato vist ovenpå billedområdet -->
        <div class="card-event-list-date">
            <span class="card-event-list-day"><?= htmlspecialchars($event['date_day']) ?></span>
            <span class="card-event-list-month"><?= htmlspecialchars($event['date_month_da']) ?></span>
        </div>

        <!-- Eventbillede vises kun hvis der findes et billede -->
        <?php if ($imgSrc): ?>
            <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Billede af eventet" class="card-event-list-img"
                onerror="this.style.display='none'; this.parentElement.classList.add('card-event-list-img-wrap--no-img');">
        <?php endif; ?>
    </div>

    <!-- Indhold i eventkortet -->
    <div class="card-event-list-body">

        <!-- Eventkategori -->
        <div class="card-event-list-category">
            <span class="card-event-list-tag"><?= htmlspecialchars($event['category_name'] ?? '') ?></span>
        </div>

        <!-- Eventtitel -->
        <h3 class="card-event-list-title"><?= htmlspecialchars($event['event_title']) ?></h3>

        <!-- Kort beskrivelse af eventet -->
        <p class="card-event-list-desc"><?= htmlspecialchars($event['event_description']) ?></p>

        <!-- Nederste del med eventinfo og handlinger -->
        <div class="card-event-list-meta">

            <!-- Liste med eventinformation -->
            <div class="card-event-list-meta-items">

                <!-- Tidspunkt -->
                <span class="card-event-list-meta-item">
                    <img src="/assets/img/icons/clock_icon.svg" alt="Ur ikon" class="card-event-list-meta-icon">

                    <!-- Starttid -->
                    <?= htmlspecialchars(substr($event['event_time'], 0, 5)) ?>

                    <!-- Sluttid vises kun hvis den findes -->
                    <?php if (!empty($event['event_end_time'])): ?>-
                        <?= htmlspecialchars(substr($event['event_end_time'], 0, 5)) ?><?php endif; ?>
                </span>

                <!-- Lokation -->
                <span class="card-event-list-meta-item">
                    <img src="/assets/img/icons/location.svg" alt="Pin ikon" class="card-event-list-meta-icon">

                    <?= htmlspecialchars($event['event_location']) ?>
                </span>

                <!-- Antal deltagere -->
                <span class="card-event-list-meta-item">
                    <img src="/assets/img/icons/persons_icon.svg" alt="Person ikon" class="card-event-list-meta-icon">

                    <?= htmlspecialchars($event['participant_count'] ?? '0') ?> deltagere
                </span>
            </div>

            <!-- Handlinger for bruger og admin -->
            <div class="card-event-list-actions">

                <!-- Link til eventside. Admin får teksten "Se event", brugere får "Læs mere" -->
                <a href="/event_page?id=<?= htmlspecialchars($event['event_pk']) ?>"
                    class="btn <?= (isset($isAdmin) && $isAdmin) ? 'btn-primary' : 'btn-secondary' ?> card-event-list-link"><?= (isset($isAdmin) && $isAdmin) ? 'Se event' : 'Læs mere' ?></a>

                <!-- Adminhandlinger vises kun for admin -->
                <?php if (isset($isAdmin) && $isAdmin): ?>

                    <!-- Link til redigering af event -->
                    <a href="/event_edit?id=<?= htmlspecialchars($event['event_pk']) ?>"
                        class="btn btn-secondary">Rediger</a>

                    <!-- Formular til sletning af event -->
                    <form id="deleteEventForm-<?= htmlspecialchars($event['event_pk']) ?>" method="POST"
                        action="/event_delete">

                        <!-- CSRF-beskyttelse -->
                        <?php csrf_input(); ?>

                        <!-- Eventets id sendes med formularen -->
                        <input type="hidden" name="event_id" value="<?= htmlspecialchars($event['event_pk']) ?>">

                        <!-- Åbner bekræftelsesmodal før eventet slettes -->
                        <button type="button" class="btn btn-delete"
                            data-modal-open="deleteEventModal-<?= htmlspecialchars($event['event_pk']) ?>">
                            Slet
                        </button>
                    </form>

                    <?php
                    // Data til bekræftelsesmodalen
                    $modalId = 'deleteEventModal-' . $event['event_pk'];
                    $formId = 'deleteEventForm-' . $event['event_pk'];
                    $title = 'Slet event?';
                    $text = 'Er du sikker på, at du vil slette dette event? Deltagere får besked på mail.';
                    $confirmText = 'Ja, slet event';

                    // Genbrugelig modal til bekræftelse af sletning
                    include __DIR__ . '/../micro/___confirm_modal.php';
                    ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
</article>