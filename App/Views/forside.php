<section class="front-page-hero">
    <h1>GBG Social</h1>
    <img src="/assets/img/forside-hero.webp" alt="Studerende til socialt arrangement">
</section>

<?php
$bannerText = 'BLIV MEDLEM AF FORENINGEN';
include __DIR__ . '/micro/___banner.php';
?>

<main class="front-page">
    <section class="intro">
        <h2>Den sociale studenterforening</h2>

        <p>
            Den sociale studenterforening på EK GulbergsGade er et nyt initiativ
            skabt af studerende, for studerende, med fokus på at styrke
            fællesskabet på tværs af uddannelser og årgange.
        </p>

        <p>
            Foreningen har til formål at skabe et levende og inkluderende
            studiemiljø, hvor alle har mulighed for at deltage i sociale
            aktiviteter, møde nye mennesker og opbygge relationer uden for
            undervisningen.
        </p>

        <a href="#" class="btn btn-primary">BLIV MEDLEM</a>
    </section>

    <section class="events-section">
        <h2 class="section-title">Events</h2>

        <div class="grid">
            <?php foreach ($events as $event): ?>
                <?php include __DIR__ . '/components/_card_event_sm.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <?php
    $bannerText = 'SE EVENTS';
    include __DIR__ . '/micro/___banner.php';
    ?>

    <section class="about-section">
        <div class="about-heading">
            <h2>Om os</h2>
        </div>

        <div class="about-content">
            <img src="/assets/img/tøser.webp" alt="Studerende samlet til fest">

            <div class="about-text">
                <p>
                    Den sociale studenterforening på EK GulbergsGade er et nyt
                    initiativ skabt af studerende, for studerende, med fokus på at
                    styrke fællesskabet på tværs af uddannelser og årgange.
                </p>

                <p>
                    Foreningen har til formål at skabe et levende og inkluderende
                    studiemiljø, hvor alle har mulighed for at deltage.
                </p>

                <a href="#" class="btn btn-secondary">LÆS MERE</a>
            </div>
        </div>
    </section>

    <section class="feed-section">
        <h2 class="section-title">Feed</h2>

        <div class="feed-link-wrapper">
            <a href="#" class="feed-link btn-nav">SE FLERE BILLEDER</a>
        </div>

        <div class="feed-grid">
            <img src="/assets/img/guldbar-billede.webp" alt="Studerende til arrangement">
            <img src="/assets/img/om.webp" alt="Studerende omkring bord">
            <img src="/assets/img/kontakt.webp" alt="Studerende til fest">
            <img src="/assets/img/tøser.webp" alt="Studerende med drikkevarer">
        </div>
    </section>
</main>