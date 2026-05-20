<?php
date_default_timezone_set('Europe/Copenhagen');

$todayDate = date('Y-m-d');

$hasSelectedDate = isset($_GET['date']);
$hasMonthYear = isset($_GET['month']) && isset($_GET['year']);

if ($hasSelectedDate) {
    $selectedDate = $_GET['date'];

    $selectedDateObj = new DateTime($selectedDate);
    $month = (int) $selectedDateObj->format('n');
    $year = (int) $selectedDateObj->format('Y');

} elseif ($hasMonthYear) {
    $selectedDate = null;

    $month = (int) $_GET['month'];
    $year = (int) $_GET['year'];

} else {
    $selectedDate = $todayDate;

    $month = (int) date('n');
    $year = (int) date('Y');
}


$events = $calendarEvents;
$registeredIds = $registeredEventIds ?? [];






$date = DateTime::createFromFormat('!Y-n-j', "$year-$month-1");

if (!$date) {
    $date = new DateTime('first day of this month');
}

$month = (int) $date->format('n');
$year = (int) $date->format('Y');

$prev = (clone $date)->modify('-1 month');
$next = (clone $date)->modify('+1 month');

$daysInMonth = (int) $date->format('t');
$startOffset = (int) $date->format('N') - 1; // mandag - 0, tirsdag - 1 osv.
$totalCells = ceil(($startOffset + $daysInMonth) / 7) * 7;

$monthNames = [
    1 => 'JANUAR',
    2 => 'FEBRUAR',
    3 => 'MARTS',
    4 => 'APRIL',
    5 => 'MAJ',
    6 => 'JUNI',
    7 => 'JULI',
    8 => 'AUGUST',
    9 => 'SEPTEMBER',
    10 => 'OKTOBER',
    11 => 'NOVEMBER',
    12 => 'DECEMBER',
];

?>
<main>
    <section class="calendar-container">
        <div class="calendar-header">
            <h1 class="kalender_header">KALENDER</h1>

            <!-- Knapper & pile -->
            <div class="calendar-actions">

                <div class="buttons">
                    <?php if ($isAdmin): ?>
                        <a class="btn btn-primary" href="/event_opret">
                            OPRET EVENT
                        </a>
                    <?php endif; ?>
                    <a class="btn btn-secondary" href="/kalender?date=<?= $todayDate ?>">
                        I DAG
                    </a>
                </div>

                <div class="calendar-arrows">
                    <a class="calendar-arrow"
                        href="/kalender?month=<?= $prev->format('n') ?>&year=<?= $prev->format('Y') ?>">
                        <img src="/assets/img/icons/arrow-left.png" alt="Forrige måned">
                    </a>
                    <a class="calendar-arrow"
                        href="/kalender?month=<?= $next->format('n') ?>&year=<?= $next->format('Y') ?>">
                        <img src="/assets/img/icons/arrow-right.png" alt="Næste måned">
                    </a>
                </div>
            </div>
        </div>

        <!-- Månedstitel -->

        <h2 class="calendar-month">
            <strong><?= $monthNames[$month] ?></strong> <?= $year ?>
        </h2>

        <!-- Kalender grid -->

        <div class="calendar-grid">
            <?php for ($cell = 0; $cell < $totalCells; $cell++): ?>
                <?php
                $day = $cell - $startOffset + 1;
                $isCurrentMonth = $day >= 1 && $day <= $daysInMonth;

                $dateKey = $isCurrentMonth
                    ? sprintf('%04d-%02d-%02d', $year, $month, $day)
                    : null;

                $dayEvents = $dateKey && isset($events[$dateKey])
                    ? $events[$dateKey]
                    : [];
                ?>

                <button class="calendar-day <?= !$isCurrentMonth ? 'calendar-day-muted' : '' ?>
                <?= $dateKey && $dateKey === $selectedDate ? 'is-selected' : '' ?>
                <?= $dateKey && $dateKey === $todayDate ? 'is-today' : '' ?>" type="button"
                    data-date="<?= $dateKey ?>">
                    <span class="calendar-date">
                        <?= $isCurrentMonth ? $day : '' ?>
                    </span>

                    <?php if (!empty($dayEvents)): ?>
                        <span class="mobile-event-marker"></span>

                        <div class="desktop-event-preview">
                            <?php foreach ($dayEvents as $event): ?>
                                <?php $isTilmeldt = in_array($event['pk'], $registeredIds); ?>
                                <p class="<?= $isTilmeldt ? 'calendar-event-tilmeldt' : '' ?>">
                                    <?= htmlspecialchars($event['title'] ?? 'Event uden titel') ?>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </button>
            <?php endfor; ?>
        </div>

        <!-- Mobil listevisning -->

        <section class="mobile-event-list">
            <h2>EVENT</h2>

            <?php foreach ($events as $eventDate => $dayEvents): ?>
                <?php
                $eventDateObj = new DateTime($eventDate);

                if ((int) $eventDateObj->format('n') !== $month || (int) $eventDateObj->format('Y') !== $year) {
                    continue;
                }
                ?>

                <?php foreach ($dayEvents as $event): ?>
                    <?php $isTilmeldt = in_array($event['pk'], $registeredIds); ?>
                    <article class="mobile-event-card <?= $eventDate === $selectedDate ? 'is-visible' : '' ?>"
                        data-event-date="<?= $eventDate ?>">
                        <div class="mobile-event-date">
                            <strong><?= $eventDateObj->format('j') ?></strong>
                            <span><?= mb_substr($monthNames[$month], 0, 3) ?></span>
                        </div>

                        <div class="calendar-img-wrap">
                            <img src="<?= $event['image'] ?>" alt="">
                            <?php if ($isTilmeldt): ?>
                                <span class="calendar-tilmeldt-label">TILMELDT</span>
                            <?php endif; ?>
                        </div>
                        <p><?= htmlspecialchars($event['title']) ?></p>
                    </article>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <!-- Hvis ingen events på valgte dag -->
            <p class="no-events" style="<?= $selectedDate && empty($events[$selectedDate]) ? 'display: block;' : '' ?>">
                Ingen events
            </p>
        </section>
    </section>
</main>