<main class="eventside-page">

    <div class="eventside-hero">
        <?php $evtImg = $event['event_image'] ?? ''; $evtSrc = !empty($evtImg) ? (str_starts_with($evtImg, '/') ? $evtImg : '/assets/img/' . $evtImg) : '/assets/img/placeholder.webp'; ?>
        <img src="<?= htmlspecialchars($evtSrc) ?>" alt="Billede af event" class="eventside-hero-img">
        <div class="eventside-hero-overlay">
            <span class="eventside-hero-presenter">GBG SOCIAL PRÆSENTERER</span>
            <h1 class="eventside-hero-title"><?= htmlspecialchars($event['event_title']) ?></h1>
            <span class="eventside-hero-date"><?= htmlspecialchars(strtoupper($dato)) ?></span>
        </div>
    </div>

    <div class="eventside-layout">
        <div class="eventside-layout-content">
                <?php if (!empty($event['event_subtitle'])): ?>
                    <h2 class="eventside-layout-heading"><?= htmlspecialchars($event['event_subtitle']) ?></h2>
                <?php else: ?>
                    <h2 class="eventside-layout-heading"><?= htmlspecialchars($event['event_title']) ?></h2>
                <?php endif; ?>
                <p class="eventside-layout-desc"><?= nl2br(htmlspecialchars($event['event_description'])) ?></p>
                <?php if (!empty($event['event_expectations'])): ?>
                    <div class="eventside-layout-expectations">
                        <p class="eventside-layout-expectations-label">Det kan du forvente:</p>
                        <ul class="eventside-layout-list">
                            <?php foreach (explode("\n", trim($event['event_expectations'])) as $punkt): ?>
                                <?php if (trim($punkt)): ?>
                                    <li><?= htmlspecialchars(trim($punkt)) ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        <aside class="eventside-layout-sidebar">
            <?php include __DIR__ . '/components/_card_info.php'; ?>
        </aside>
    </div>

    <section class="eventside-om">
        <div class="container">
            <h2 class="eventside-om-heading">Om eventet</h2>
            <p class="eventside-om-text"><?= nl2br(htmlspecialchars($event['event_description'])) ?></p>
        </div>
    </section>

    <section class="event-gallery">
        <div class="event-gallery-header">
            <h2 class="event-gallery-title">FRA VORES EVENTS</h2>
            <a href="#" class="event-gallery-link">SE FLERE BILLEDER →</a>
        </div>
        <div class="event-gallery-grid">
            <img src="/assets/img/fredagsbar-udenfor.webp" alt="Fredagsbar" class="event-gallery-img">
            <img src="/assets/img/guldbar-hygge.webp" alt="Guldbar" class="event-gallery-img">
            <img src="/assets/img/fredagsbar-udenfor2.webp" alt="Fredagsbar udenfor" class="event-gallery-img">
            <img src="/assets/img/tøser.webp" alt="Vejledere" class="event-gallery-img">
        </div>
    </section>

    <?php if (!empty($participants)): ?>
    <section class="event-deltagere">
            <h2 class="deltagere-heading">DELTAGERE</h2>
            <div class="deltagere-carousel-wrapper">
                <button class="deltagere-arrow carousel-prev" aria-label="Forrige">←</button>
                <div class="deltagere-carousel" id="memberCarousel">
                    <?php foreach (array_chunk($participants, 2) as $slide): ?>
                        <div class="deltager-slide member-slide">
                            <?php foreach ($slide as $p): ?>
                                <?php $img = !empty($p['user_profile_image']) ? '/assets/img/uploads/' . $p['user_profile_image'] : '/assets/img/uploads/default_profile_image.webp'; ?>
                                <img src="<?= htmlspecialchars($img) ?>" alt="Profilbillede" class="profile-img profile-medium">
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="deltagere-arrow carousel-next" aria-label="Næste">→</button>
            </div>
    </section>
    <?php endif; ?>

</main>
