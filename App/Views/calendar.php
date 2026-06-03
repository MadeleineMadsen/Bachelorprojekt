<?php
$calendarEvents = $calendarEvents ?? [];
$isAdmin = $isAdmin ?? false;

// Sætter tidszonen til dansk tid
date_default_timezone_set('Europe/Copenhagen');

// Dags dato
$todayDate = date('Y-m-d');

// Tjekker om der er valgt en specifik dato eller måned/år i URL'en
$hasSelectedDate = isset($_GET['date']);
$hasMonthYear = isset($_GET['month']) && isset($_GET['year']);

// Finder hvilken dato, måned og år kalenderen skal vise
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

// Events og brugerens tilmeldte events
$events = $calendarEvents;
$registeredIds = $registeredEventIds ?? [];

// Opretter datoobjekt for den viste måned
$date = DateTime::createFromFormat('!Y-n-j', "$year-$month-1");

// Fallback hvis datoen ikke kan oprettes
if (!$date) {
    $date = new DateTime('first day of this month');
}

// Sikrer at måned og år kommer fra det gyldige datoobjekt
$month = (int) $date->format('n');
$year = (int) $date->format('Y');

// Forrige og næste måned
$prev = (clone $date)->modify('-1 month');
$next = (clone $date)->modify('+1 month');

// Kalenderberegninger
$daysInMonth = (int) $date->format('t');
$startOffset = (int) $date->format('N') - 1; // mandag - 0, tirsdag - 1 osv.
$totalCells = ceil(($startOffset + $daysInMonth) / 7) * 7;

// Danske månedsnavne
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

<!-- Kalender side -->
<main class="calendar-page">

    <!-- Kalender container -->
    <section class="calendar-container">

        <!-- Kalender header -->
        <section class="calendar-header">
            <h1 class="calendar-header">KALENDER</h1>

            <div class="calendar-actions">

                <!-- Knapper -->
                <div class="buttons">
                    <?php if ($isAdmin): ?>
                        <a class="btn btn-primary" href="/event_create">
                            OPRET EVENT
                        </a>
                    <?php endif; ?>

                    <a class="btn btn-secondary" href="/calendar?date=<?= $todayDate ?>">
                        I DAG
                    </a>
                </div>

                <!-- Pile til forrige/næste måned -->
                <div class="calendar-arrows">
                    <a class="calendar-arrow"
                        href="/calendar?month=<?= $prev->format('n') ?>&year=<?= $prev->format('Y') ?>">
                        <img src="/assets/img/icons/arrow-left.svg" alt="Forrige måned">
                    </a>

                    <a class="calendar-arrow"
                        href="/calendar?month=<?= $next->format('n') ?>&year=<?= $next->format('Y') ?>">
                        <img src="/assets/img/icons/arrow-right.svg" alt="Næste måned">
                    </a>
                </div>
            </div>
        </section>

        <!-- Månedstitel -->
        <h2 class="calendar-month">
            <strong><?= $monthNames[$month] ?></strong> <?= $year ?>
        </h2>

        <!-- Ugedage -->
        <div class="calendar-weekdays">
            <?php foreach (['MAN', 'TIR', 'ONS', 'TOR', 'FRE', 'LØR', 'SØN'] as $day): ?>
                <div class="calendar-weekday"><?= $day ?></div>
            <?php endforeach; ?>
        </div>

        <!-- Kalender grid -->
        <div class="calendar-grid">
            <?php for ($cell = 0; $cell < $totalCells; $cell++): ?>

                <?php
                // Beregner dag og dato for hver celle
                $day = $cell - $startOffset + 1;
                $isCurrentMonth = $day >= 1 && $day <= $daysInMonth;

                $dateKey = $isCurrentMonth
                    ? sprintf('%04d-%02d-%02d', $year, $month, $day)
                    : null;

                // Finder events for den aktuelle dato
                $dayEvents = $dateKey && isset($events[$dateKey])
                    ? $events[$dateKey]
                    : [];
                ?>

                <!-- Kalenderdag -->
                <button class="calendar-day <?= !$isCurrentMonth ? 'calendar-day-muted' : '' ?>
                <?= $dateKey && $dateKey === $selectedDate ? 'is-selected' : '' ?>
                <?= $dateKey && $dateKey === $todayDate ? 'is-today' : '' ?>" type="button"
                    data-date="<?= $dateKey ?>">

                    <span class="calendar-date">
                        <?= $isCurrentMonth ? $day : '' ?>
                    </span>

                    <!-- Events på dagen -->
                    <?php if (!empty($dayEvents)): ?>
                        <span class="mobile-event-marker"></span>

                        <div class="desktop-event-preview">
                            <?php foreach ($dayEvents as $event): ?>
                                <?php $isRegistered = in_array($event['pk'], $registeredIds); ?>
                                <p class="<?= $isRegistered ? 'calendar-event-registered' : '' ?>">
                                    <?= htmlspecialchars($event['title'] ?? 'Event uden titel') ?>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </button>
            <?php endfor; ?>
        </div>

        <!-- Mobil listevisning af events -->
        <section class="mobile-event-list">
            <h2>EVENT</h2>

            <!-- Loop gennem events i den viste måned -->
            <?php foreach ($events as $eventDate => $dayEvents): ?>
                <?php
                $eventDateObj = new DateTime($eventDate);

                // Springer events over, hvis de ikke ligger i den viste måned
                if ((int) $eventDateObj->format('n') !== $month || (int) $eventDateObj->format('Y') !== $year) {
                    continue;
                }
                ?>

                <?php foreach ($dayEvents as $event): ?>
                    <?php $isRegistered = in_array($event['pk'], $registeredIds); ?>

                    <!-- Link til eventside -->
                    <a href="/event_page?id=<?= urlencode($event['pk']) ?>" class="mobile-event-card-link">

                        <!-- Eventkort vises på mobil når datoen er valgt -->
                        <article class="mobile-event-card <?= $eventDate === $selectedDate ? 'is-visible' : '' ?>"
                            data-event-date="<?= $eventDate ?>">

                            <div class="mobile-event-date">
                                <strong><?= $eventDateObj->format('j') ?></strong>

                                <span><?= mb_substr($monthNames[$month], 0, 3) ?></span>
                            </div>

                            <div class="calendar-img-wrap">
                                <img src="<?= $event['image'] ?>" alt="Billede af eventet">

                                <?php if ($isRegistered): ?>
                                    <span class="calendar-registered-label">TILMELDT</span>
                                <?php endif; ?>
                            </div>

                            <p><?= htmlspecialchars($event['title']) ?></p>
                        </article>
                    </a>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <!-- Vises hvis der ikke er events på den valgte dag -->
            <p class="no-events" style="<?= $selectedDate && empty($events[$selectedDate]) ? 'display: block;' : '' ?>">
                Ingen events
            </p>
        </section>
    </section>
</main>