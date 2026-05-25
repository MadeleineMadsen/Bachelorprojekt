<!-- Event side -->
<main class="event-page">

    <!-- Hero sektion -->
    <section class="event-page-hero">
        <?php $evtImg = $event['event_image'] ?? '';

        // Finder eventbillede eller fallback billede
        $evtSrc = !empty($evtImg) ? (str_starts_with($evtImg, '/') ? $evtImg : '/assets/img/' . $evtImg) : '/assets/img/placeholder.webp'; ?>

        <img src="<?= htmlspecialchars($evtSrc) ?>" alt="Billede af event" class="event-page-hero-img">

        <!-- Overlay med eventinfo -->
        <div class="event-page-hero-overlay">
            <span class="event-page-hero-presenter">GBG SOCIAL PRÆSENTERER</span>

            <h1 class="event-page-hero-title"><?= htmlspecialchars($event['event_title']) ?></h1>

            <span class="event-page-hero-date"><?= htmlspecialchars(strtoupper($dato)) ?></span>
        </div>
    </section>

    <!-- Event indhold -->
    <section class="event-page-layout">

        <!-- Venstre indhold -->
        <div class="event-page-layout-content">

            <!-- Undertitel eller fallback titel -->
            <?php if (!empty($event['event_subtitle'])): ?>
                <h2 class="event-page-layout-heading"><?= htmlspecialchars($event['event_subtitle']) ?></h2>
            <?php else: ?>
                <h2 class="event-page-layout-heading"><?= htmlspecialchars($event['event_title']) ?></h2>
            <?php endif; ?>

            <!-- Event beskrivelse -->
            <p class="event-page-layout-desc"><?= nl2br(htmlspecialchars($event['event_description'])) ?></p>

            <!-- Forventninger / bullet points -->
            <?php if (!empty($event['event_expectations'])): ?>
                <div class="event-page-layout-expectations">
                    <p class="event-page-layout-expectations-label">Det kan du forvente:</p>

                    <ul class="event-page-layout-list">
                        <?php foreach (explode("\n", trim($event['event_expectations'])) as $punkt): ?>
                            <?php if (trim($punkt)): ?>
                                <li><?= htmlspecialchars(trim($punkt)) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <aside class="event-page-layout-sidebar">
            <?php include __DIR__ . '/components/_card_info.php'; ?>
        </aside>
    </section>

    <!-- Om eventet -->
    <section class="event-page-about">
        <div class="container">
            <h2 class="event-page-about-heading">Om eventet</h2>

            <p class="event-page-about-text"><?= nl2br(htmlspecialchars($event['event_description'])) ?></p>
        </div>
    </section>

    <!-- Event galleri -->
    <section class="event-gallery">
        <div class="event-gallery-header">
            <h2 class="event-gallery-title">FRA VORES EVENTS</h2>

            <a href="https://www.instagram.com/_ekdigital/" class="event-gallery-link btn-nav">SE FLERE BILLEDER</a>
        </div>

        <!-- Galleri billeder -->
        <div class="event-gallery-grid">
            <img src="/assets/img/fredagsbar-udenfor.webp" alt="Fredagsbar" class="event-gallery-img">

            <img src="/assets/img/guldbar-hygge.webp" alt="Guldbar" class="event-gallery-img">

            <img src="/assets/img/fredagsbar-udenfor2.webp" alt="Fredagsbar udenfor" class="event-gallery-img">

            <img src="/assets/img/tøser.webp" alt="Vejledere" class="event-gallery-img">
        </div>
    </section>

    <!-- Deltagere -->
    <?php if (!empty($participants)): ?>

        <?php
        // Antal deltagere og limits til mobil/desktop
        $participantCount = count($participants);

        $mobileLimit = 4;
        $desktopLimit = 13;

        $mobileRemaining = $participantCount - $mobileLimit;
        $desktopRemaining = $participantCount - $desktopLimit;
        ?>

        <section class="event-participants">
            <h2 class="participants-heading">DELTAGERE</h2>

            <!-- Mobil visning -->
            <div class="participants-grid participants-mobile">
                <?php foreach (array_slice($participants, 0, $mobileLimit) as $p): ?>
                    <?php
                    $img = !empty($p['user_profile_image'])
                        ? '/assets/img/uploads/' . $p['user_profile_image']
                        : '/assets/img/uploads/default_profile_image.webp';
                    ?>

                    <img src="<?= htmlspecialchars($img) ?>" alt="" class="profile-img profile-medium participants-profile">
                <?php endforeach; ?>

                <!-- Viser antal resterende deltagere -->
                <?php if ($mobileRemaining > 0): ?>
                    <div class="participants-more">+<?= $mobileRemaining ?></div>
                <?php endif; ?>
            </div>

            <!-- Desktop visning -->
            <div class="participants-grid participants-desktop">
                <?php foreach (array_slice($participants, 0, $desktopLimit) as $p): ?>
                    <?php
                    $img = !empty($p['user_profile_image'])
                        ? '/assets/img/uploads/' . $p['user_profile_image']
                        : '/assets/img/uploads/default_profile_image.webp';
                    ?>

                    <img src="<?= htmlspecialchars($img) ?>" alt="" class="profile-img profile-medium participants-profile">
                <?php endforeach; ?>

                <!-- Viser antal resterende deltagere -->
                <?php if ($desktopRemaining > 0): ?>
                    <div class="participants-more">+<?= $desktopRemaining ?></div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
</main>