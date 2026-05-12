<?php

?>

<!-- Hero section -->
<section class="membership-hero">

    <div class="membership-left">
        <h1>BLIV<br>MEDLEM</h1>

        <div class="hero-line"></div>

        <p>
            Bliv en del af GBG Social og vær med til at skabe
            uforglemmelige events, stærke fællesskaber og
            oplevelser, du ikke finder andre steder.
        </p>
    </div>

    <div class="membership-right">
        <img src="assets/img/solnedgang.webp" alt="GBG Social medlemmer">
    </div>

</section>

<!-- Banner -->
<?php
$bannerText = 'BLIV EN DEL AF FÆLLESSKABET';
include __DIR__ . '/micro/___banner.php';
?>

<main class="membership-page">

    <!-- Hvorfor blive medlem -->
    <section class="membership-benefits">

        <h2>HVORFOR BLIVE MEDLEM?</h2>

        <div class="benefits-grid">

            <article class="benefit-card">
                <img src="assets/img/icons/fællesskab.png" alt="Fællesskab logo">
                <h3>FÆLLESSKAB</h3>
                <p>
                    FG GDFDGJHDFJFFG<br>
                    GDFDGJHDFJFFG<br>
                    GDFDGJHDFJFFG
                </p>
            </article>

            <article class="benefit-card">
                <img src="assets/img/icons/events2.png" alt="Events logo">
                <h3>EVENTS</h3>
                <p>
                    FG GDFDGJHDFJFFG<br>
                    GDFDGJHDFJFFG<br>
                    GDFDGJHDFJFFG
                </p>
            </article>

            <article class="benefit-card">
                <img src="assets/img/icons/netværk.png" alt="Netværk logo">
                <h3>NETVÆRK</h3>
                <p>
                    FG GDFDGJHDFJFFG<br>
                    GDFDGJHDFJFFG<br>
                    GDFDGJHDFJFFG
                </p>
            </article>

        </div>

    </section>

    <!-- Stats section -->
    <section class="membership-stats">

        <div class="stat">
            <img src="assets/img/icons/user_account.svg" alt="">
            <div class="stat-text">
                <h3>+30</h3>
                <p>AKTIVE MEDLEMMER</p>
            </div>
        </div>

        <div class="stat">
            <img src="assets/img/icons/calender.png" alt="">
            <div class="stat-text">
                <h3>+10</h3>
                <p>EVENTS OM ÅRET</p>
            </div>
        </div>

        <div class="stat">
            <img src="assets/img/icons/favorite.svg" alt="">
            <div class="stat-text">
                <h3>100%</h3>
                <p>FÆLLESSKAB</p>
            </div>
        </div>

    </section>

    <!-- Bliv en del af holdet -->
    <section class="get-membership">

        <!-- Venstre side -->
        <div class="membership-info">
            <h2>BLIV EN DEL AF HOLDET</h2>

            <p>
                Udfyld formularen, så lærer vi dig bedre at kende.
                Vi ser frem til at byde dig velkommen i fællesskabet!
            </p>

            <div class="membership-image">
                <img src="assets/img/fredagsbar-udenfor2.webp" alt="GBG Social medlemmer">
            </div>
        </div>

        <!-- Højre side -->
        <div class="form-container membership-container">
            <form method="POST" action="/medlem_sog">
                <div class="form-row">
                    <input type="text" value="<?= htmlspecialchars($_SESSION['user']['user_name'] ?? '') ?>" disabled>
                    <input type="text" value="<?= htmlspecialchars($_SESSION['user']['user_last_name'] ?? '') ?>"
                        disabled>
                </div>
                <select name="education_fk" required>
                    <option value="">Vælg studieretning</option>

                    <?php foreach ($educations as $education): ?>
                        <option value="<?= $education['education_pk'] ?>">
                            <?= htmlspecialchars($education['education_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="semester_fk" required>
                    <option value="">Vælg semester</option>

                    <?php foreach ($semesters as $semester): ?>
                        <option value="<?= $semester['semester_pk'] ?>">
                            <?= htmlspecialchars($semester['semester_number']) ?>. semester
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="email" value="<?= htmlspecialchars($_SESSION['user']['user_email'] ?? '') ?>" disabled>

                <label class="form-label" for="description">Hvorfor vil du være medlem? Og hvilke af de kommende
                    aktiviteter
                    vil du have ansvaret for?</label>
                <textarea id="description" name="description" required></textarea>

                <button class="btn btn-primary" type="submit">SEND ANSØGNING</button>
            </form>
        </div>
    </section>


</main>