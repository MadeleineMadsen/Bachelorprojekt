<main class="main-about">
    <section class="about-hero">

        <div class="about-left">
            <h1 class="about-header">OM OS</h1>

            <div class="about-hero-line"></div>

            <h2 class="hero-sub sub">DEN SOCIALE STUDENTERFORENING</h2>

            <p class="sub-text">
                GBG Social er for alle studerende på Guldbergsgade.
                Vi skaber fællesskab gennem sjove events, gode vibes og uforglemmelige oplevelser.
            </p>
        </div>

        <div class="about-right">
            <img class="about-img" src="/assets/img/om.webp" alt="Billede af unge kvinder fra GBG Social">
        </div>

    </section>


    <?php
    $bannerText = 'VI SKABER FÆLLESSKAB';
    include __DIR__ . '/micro/___banner.php';
    ?>

    <section id="join-us">
        <section class="join-us-container">
            <h2 class="join-us-sub sub">VIL DU VÆRE MED?</h2>
            <img class="join-us-img" src="/assets/img/vil-du.webp" alt="Billede af studerende der smiler til kameraet">
            <p class="sub-text join-us-text">Har du lyst til at være en del af et socialt fællesskab, hvor du kan være
                med til at planlægge og stå for de fedeste events?</p>
            <a href="/membership_apply" class="btn btn-primary">BLIV MEDLEM</a>
        </section>
    </section>


    <section id="team">
        <h2 class="meet-sub sub">MØD MEDLEMMERNE I GBG SOCIAL</h2>
        <div class="team-members">
            <h1 class="about-header team">TEAMET</h1>

            <section class="member-carousel-section about-carousel-section">
                <button class="carousel-arrow carousel-prev" type="button" aria-label="Forrige">
                    &#8592;
                </button>

                <div class="member-carousel about-carousel" id="memberCarousel" data-visible-slides="1">
                    <?php foreach ($members as $index => $member): ?>
                        <article class="member-slide about-slide <?= $index >= 10 ? 'desktop-hidden' : ''; ?>">
                            <img src="<?= !empty($member['user_profile_image'])
                                ? '/assets/img/uploads/' . htmlspecialchars($member['user_profile_image'])
                                : '/assets/img/uploads/default_profile_image.webp' ?>"
                                alt="Portræt af <?= htmlspecialchars($member['user_name']); ?>"
                                class="profile-img profile-medium">

                            <h3>
                                <?= htmlspecialchars($member['user_name']); ?>
                            </h3>
                            <p>
                                <?= !empty($member['education_name'])
                                    ? htmlspecialchars($member['education_name'])
                                    : 'Bestyrelsesmedlem' ?>
                            </p>
                        </article>
                    <?php endforeach; ?>
                </div>

                <button class="carousel-arrow carousel-next" type="button" aria-label="Næste">
                    &#8594;
                </button>
            </section>
        </div>
        <div class="team-btn-wrapper">
            <a href="/members" class="btn btn-nav btn-about">SE ALLE MEDLEMMER</a>
        </div>
    </section>

    <section id="values">
        <h2 class="values-sub sub">VORES VÆRDIER</h2>
        <div class="values-container">
            <img src="/assets/img/icons/fælles.svg" alt="Fællesskab icon">
            <div>
                <h3>FÆLLESSKAB</h3>
                <p class="values-sub-text">Et trygt fællesskab, hvor studerende mødes og lærer hinanden at kende på
                    tværs.</p>
            </div>
        </div>
        <div class="values-container">
            <img src="/assets/img/icons/oplev.svg" alt="Menneske med armene over hovedet icon">
            <div>
                <h3>OPLEVELSER</h3>
                <p class="values-sub-text">Vi skaber sociale events, der samler studerende og giver gode minder.</p>
            </div>
        </div>
        <div class="values-container">
            <img src="/assets/img/icons/inklusion.svg" alt="Hjerte icon">
            <div>
                <h3>INKLUSION</h3>
                <p class="values-sub-text">Et inkluderende miljø, der har plads til alle, og hvor du kan være dig selv.
                </p>
            </div>
        </div>

        <img class="values-img" src="/assets/img/værdi.webp" alt="Studerende der kigger på solnedgangen sammen">
    </section>

    <!-- id´et bruges til at der scroller hertil når man klikker kontakt i footer -->
    <section class="contact" id="contact">
        <h2 class="contact-sub sub">KONTAKT OS</h2>
        <p class="sub-text">Har du spørgsmål, ideer til events eller lyst til at samarbejde? Så ræk endelig ud!</p>

        <div class="contact-container">
            <img src="/assets/img/icons/mail.svg" alt="Mail icon">
            <div>
                <p class="sub-text">GBG@SOCIAL.COM</p>
            </div>
        </div>
        <div class="contact-container">
            <img src="/assets/img/icons/insta-sort.svg" alt="Instagram icon">
            <div>
                <p class="sub-text">@GBG.SOCIAL</p>
            </div>
        </div>
        <div class="contact-container">
            <img src="/assets/img/icons/face-sort.svg" alt="Facebook icon">
            <div>
                <p class="sub-text">GBG SOCIAL - MEMBERGROUP</p>
            </div>
        </div>
        <div class="contact-container">
            <img src="/assets/img/icons/placering_icon.png" alt="Pin icon">
            <div>
                <p class="sub-text">GULDBERGSGADE 29N, 2200 KØBENHAVN N</p>
            </div>
        </div>

        <img class="contact-img" src="/assets/img/kontakt.webp" alt="Billede af studerende der smiler til kameraet">
    </section>
</main>