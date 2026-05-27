<!-- Eventkort -->
<article class="card-event-sm">

    <!-- Wrapper til eventbillede og datobadge -->
    <div class="card-event-sm-img-wrap">

        <!-- Dato vist ovenpå billedet -->
        <div class="card-event-sm-date">

            <!-- Dag -->
            <span class="card-event-sm-day"><?= htmlspecialchars($event['date_day']) ?></span>

            <!-- Måned -->
            <span class="card-event-sm-month"><?= htmlspecialchars($event['date_month_da']) ?></span>
        </div>

        <!-- Eventbillede -->
        <img src="/assets/img/<?= htmlspecialchars($event['event_image']) ?>"
            alt="<?= htmlspecialchars($event['event_title']) ?>" class="card-event-sm-img">
    </div>

    <!-- Indhold i eventkort -->
    <div class="card-event-sm-body">

        <!-- Eventkategori vises kun hvis den findes -->
        <?php if (!empty($event['event_category'])): ?>
            <div class="card-event-sm-category">

                <!-- Kategori-tag -->
                <span class="card-event-sm-tag"><?= htmlspecialchars($event['event_category']) ?></span>
            </div>
        <?php endif; ?>

        <!-- Eventtitel -->
        <h3 class="card-event-sm-title"><?= htmlspecialchars($event['event_title']) ?></h3>

        <!-- Kort beskrivelse af event -->
        <p class="card-event-sm-desc"><?= htmlspecialchars($event['event_description']) ?></p>

        <!-- Nederste del med info og knap -->
        <div class="card-event-sm-meta">

            <!-- Liste med eventinformation -->
            <div class="card-event-sm-meta-items">

                <!-- Tidspunkt -->
                <span class="card-event-sm-meta-item">

                    <!-- Ikon -->
                    <img src="/assets/img/icons/clock_icon.svg" alt="Ur ikon" class="card-event-sm-meta-icon">

                    <!-- Starttid -->
                    <?= htmlspecialchars(substr($event['event_time'], 0, 5)) ?>

                    <!-- Sluttid vises kun hvis den findes -->
                    <?php if (!empty($event['event_end_time'])): ?>-
                        <?= htmlspecialchars(substr($event['event_end_time'], 0, 5)) ?><?php endif; ?>
                </span>

                <!-- Lokation -->
                <span class="card-event-sm-meta-item">

                    <!-- Ikon -->
                    <img src="/assets/img/icons/location.svg" alt="Pin ikon" class="card-event-sm-meta-icon">

                    <!-- Adresse/lokation -->
                    <?= htmlspecialchars($event['event_location']) ?>
                </span>

                <!-- Antal deltagere -->
                <span class="card-event-sm-meta-item">

                    <!-- Ikon -->
                    <img src="/assets/img/icons/persons_icon.svg" alt="Person ikon" class="card-event-sm-meta-icon">

                    <!-- Deltagerantal -->
                    <?= htmlspecialchars($event['participant_count'] ?? '0') ?> deltagere
                </span>
            </div>

            <!-- Link til single event side -->
            <a href="/event_page?id=<?= htmlspecialchars($event['event_pk']) ?>"
                class="btn btn-secondary card-event-sm-link">Læs mere</a>
        </div>
    </div>
</article>