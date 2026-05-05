<?php
$members = [
    [
        'name' => 'Gigi',
        'age' => 27,
        'education' => 'Multimedie',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Maja',
        'age' => 24,
        'education' => 'Datamatiker',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Noah',
        'age' => 29,
        'education' => 'Webudvikling',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Gigi',
        'age' => 27,
        'education' => 'Multimedie',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Maja',
        'age' => 24,
        'education' => 'Datamatiker',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Noah',
        'age' => 29,
        'education' => 'Webudvikling',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Gigi',
        'age' => 27,
        'education' => 'Multimedie',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Maja',
        'age' => 24,
        'education' => 'Datamatiker',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Noah',
        'age' => 29,
        'education' => 'Webudvikling',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
];
?>

<main class="main-om">
    <section class="om-hero">
        <div class="img-hero">
                <h1 class="om-header">OM OS</h1>
                <img class="om-img" src="/assets/img/om.webp" alt="Billede af unge kvinder fra GBG Social">
            <div class=" container sub-header">
            <h2 class="sub">DEN SOCIALE STUDENTERFORENING</h2>
            <p class="sub-text">GBG Social er for alle studerende på Guldbergsgade.</p>
            <p class="sub-text">Vi skaber fællesskab gennem sjove events, gode vibes og uforglemmelige oplevelser</p>
            </div>
        </div>
    </section>


    <?php
    $bannerText = 'VI SKABER FÆLLESSKAB';
    include __DIR__ . '/micro/___banner.php';
    ?>

    <section id="vil-du">
        <section class="vil-du-container">
        <h2 class="vil-du-sub sub">VIL DU VÆRE MED?</h2>
        <img class="vil-du-img" src="/assets/img/vil-du.webp" alt="Billede af studerende der smiler til kameraet">
        <p class="sub-text vil-du-text">Har du lyst til at være en del af et socialt fællesskab, hvor du kan være med til at planlægge og stå for de fedeste events?</p>
        <button class="btn btn-primary">BLIV MEDLEM</button>
        </section>
    </section>


    <section class="container" id="teamet">
        <h2 class="sub mød-sub">MØD MEDLEMMERNE I GBG SOCIAL</h2>
        <div class="team-members">
            <h1 class="om-header teamet">TEAMET</h1>

            <section class="member-carousel-section om-carousel-section">
                <button class="carousel-arrow carousel-prev" type="button" aria-label="Forrige">
                    &#8592;
                </button>

                <div class="member-carousel om-carousel" id="memberCarousel" data-visible-slides="1">
                    <?php foreach ($members as $index => $member): ?>
                        <article class="member-slide om-slide <?= $index >= 8 ? 'desktop-hidden' : ''; ?>">
                            <img src="<?= $member['image']; ?>" alt="" class="profile-img profile-medium">

                            <h3>
                                <?= $member['name']; ?>
                                <?= $member['age']; ?> ÅR
                            </h3>

                            <p><?= $member['education']; ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>

                <button class="carousel-arrow carousel-next" type="button" aria-label="Næste">
                    &#8594;
                </button>
            </section>
        </div>
        <div class="team-btn-wrapper">
            <a href="/../App/Views/medlemmer.php" class="btn btn-nav btn-om">SE ALLE MEDLEMMER</a>
        </div>
    </section>

    <section id="værdi">
        <h2 class="sub container">VORES VÆRDIER</h2>
        <div class="værdi-container">
            <img src="/assets/img/icons/fælles.svg" alt="Fællesskab icon">
            <div>
                <h3>FÆLLESSKAB</h3>
                <p class="sub-text">Et trygt fællesskab, hvor studerende mødes og lærer hinanden at kende på tværs.</p>
            </div>
        </div>
        <div class="værdi-container">
            <img src="/assets/img/icons/oplev.svg" alt="Menneske med armene over hovedet icon">
            <div>
                <h3>OPLEVELSER</h3>
                <p class="sub-text">Vi skaber sociale events, der samler studerende og giver gode minder.</p>
            </div>
        </div>
        <div class="værdi-container">
            <img src="/assets/img/icons/inklusion.svg" alt="Hjerte icon">
            <div>
                <h3>INKLUSION</h3>
                <p class="sub-text">Et inkluderende miljø, der har plads til alle, og hvor du kan være dig selv.</p>
            </div>
        </div>

        <img class="værdi-img" src="/assets/img/værdi.webp" alt="Studerende der kigger på solnedgangen sammen">
    </section>
    
    <!-- id´et bruges til at der scroller hertil når man klikker kontakt i footer -->
    <section class="kontakt container" id="kontakt">
        <h2 class="sub">KONTAKT OS</h2>
        <p class="sub-text">Har du spørgsmål, ideer til events eller lyst til at samarbejde? Så ræk endelig ud!</p>

        <div class="kontakt-container">
            <img src="/assets/img/icons/svg" alt="Mail icon">
            <div>
                <p class="sub-text">GBG@SOCIAL.COM</p>
            </div>
        </div>
        <div class="kontakt-container">
            <img src="/assets/img/icons/insta-sort.svg" alt="Instagram icon">
            <div>
                <p class="sub-text">@GBG.SOCIAL</p>
            </div>
        </div>
        <div class="kontakt-container">
            <img src="/assets/img/icons/face-sort.svg" alt="Facebook icon">
            <div>
                <p class="sub-text">GBG SOCIAL - MEMBERGROUP</p>
            </div>
        </div>
        <div class="kontakt-container">
            <img src="/assets/img/icons/pin.svg" alt="Pin icon">
            <div>
                <p class="sub-text">GULDBERGSGADE 29N, 2200 KØBENHAVN N</p>
            </div>
        </div>

        <img class="kontakt-img" src="/assets/img/kontakt.webp" alt="Billede af studerende der smiler til kameraet">
    </section>
</main>
