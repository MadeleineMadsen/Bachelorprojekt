<?php
$events = $events ?? [];
?>

<!-- Forside -->
<main class="front-page">

    <!-- Hero sektion -->
    <section class="front-page-hero">
        <h1>GBG Social</h1>
        <img src="/assets/img/forside-hero.webp" alt="Studerende til socialt arrangement">
    </section>

    <!-- Banner -->
    <?php
    $bannerText = 'BLIV MEDLEM AF FORENINGEN';
    include __DIR__ . '/micro/___banner.php';
    ?>

    <!-- Intro sektion -->
    <section class="intro">
        <h2>Den sociale studenterforening</h2>

        <p>
            Den sociale studenterforening på EK Guldbergsgade er et nyt initiativ
            skabt af studerende, for studerende, med fokus på at styrke
            fællesskabet på tværs af uddannelser og årgange.
        </p>

        <p>
            Foreningen har til formål at skabe sociale aktiviteter, arrangementer og nye relationer, så alle studerende
            får mulighed for at lære hinanden at kende og føle sig som en del af studiemiljøet.
        </p>

        <a href="/membership_apply" class="btn btn-primary">BLIV MEDLEM</a>
    </section>

    <!-- Events sektion -->
    <section class="events-section">
        <h2 class="section-title">Events</h2>

        <div class="grid">

            <!-- Loop gennem events -->
            <?php foreach ($events as $event): ?>

                <!-- Eventkort -->
                <?php include __DIR__ . '/components/_card_event_sm.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Banner -->
    <?php
    $bannerText = 'SE EVENTS';
    include __DIR__ . '/micro/___banner.php';
    ?>

    <!-- Om os sektion -->
    <section class="about-section">
        <div class="about-heading">
            <h2>Om os</h2>
        </div>

        <div class="about-content">
            <img src="/assets/img/guldbar-hygge.webp" alt="Studerende samlet til fest">

            <div class="about-text">
                <p>
                    Vi tror på, at et godt studieliv også handler om fællesskab. Derfor arrangerer vi sociale events og
                    aktiviteter, hvor studerende kan mødes på tværs af hold og uddannelser.
                </p>

                <p>
                    Vores mål er at skabe et inkluderende miljø med plads til alle, uanset hvem du er, eller hvor du er
                    i dit studie.
                </p>

                <a href="/about" class="btn btn-secondary">LÆS MERE</a>
            </div>
        </div>
    </section>

    <!-- Feed sektion -->
    <section class="feed-section">
        <h2 class="section-title">Feed</h2>

        <!-- Link til flere billeder -->
        <div class="feed-link-wrapper">
            <a href="https://www.instagram.com/_ekdigital/" class="feed-link btn-nav">SE FLERE BILLEDER</a>
        </div>

        <!-- Feed billeder -->
        <div class="feed-grid">
            <img src="/assets/img/guldbar-billede.webp" alt="Studerende i Guldbar">

            <img src="/assets/img/om.webp" alt="Studerende til fest">

            <img src="/assets/img/kontakt.webp" alt="Grillaften med studerende">

            <img src="/assets/img/udklædningsfest.webp" alt="Studerende til udklædningsfest">
        </div>
    </section>
</main>