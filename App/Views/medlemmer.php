<?php
$members = [
    [
        'name' => 'Gigi Hadid',
        'education' => 'Designteknolog',
        'semester' => '3. semester',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Gigi Hadid',
        'education' => 'Designteknolog',
        'semester' => '3. semester',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Gigi Hadid',
        'education' => 'Designteknolog',
        'semester' => '3. semester',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Gigi Hadid',
        'education' => 'Designteknolog',
        'semester' => '3. semester',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Gigi Hadid',
        'education' => 'Designteknolog',
        'semester' => '3. semester',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Gigi Hadid',
        'education' => 'Designteknolog',
        'semester' => '3. semester',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Gigi Hadid',
        'education' => 'Designteknolog',
        'semester' => '3. semester',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Gigi Hadid',
        'education' => 'Designteknolog',
        'semester' => '3. semester',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
];
?>

<!-- Hero section -->
<section class="members-hero">
    <h1>MEDLEMMER</h1>
</section>

<!-- Banner -->
<?php
$bannerText = 'SE ALLE MEDLEMMER';
include __DIR__ . '/micro/___banner.php';
?>

<main class="members-page">

    <!-- Medlemmer overview -->
    <section class="members-overview">

        <h2>ALLE AKTIVE MEDLEM-MER I GBG SOCIAL</h2>

        <div class="members-stats">
            <div class="members-stat">
                <h3>44</h3>
                <p>AKTIVE MEDLEMMER</p>
            </div>

            <div class="members-stat">
                <h3>12</h3>
                <p>BESTYRELSES-MEDLEMMER</p>
            </div>

            <div class="members-stat">
                <h3>+10</h3>
                <p>EVENTS OM ÅRET</p>
            </div>
        </div>

        <div class="members-filters">
            <label class="search-field" for="memberSearch">
                <span aria-hidden="true">&#128269;</span>
                <input id="memberSearch" type="search" name="search" placeholder="SØG">
            </label>

            <label class="education-filter" for="educationFilter">
                <span class="sr-only">Filtrer efter uddannelse</span>
                <select id="educationFilter" name="education">
                    <option value="">UDDANNELSE</option>
                    <option value="designteknolog">Designteknolog</option>
                    <option value="multimediedesigner">Multimediedesigner</option>
                    <option value="webudvikling">Webudvikling</option>
                    <option value="datamatiker">Datamatiker</option>
                </select>
            </label>
        </div>

        <section class="members-grid" aria-label="Medlemmer">
            <?php foreach ($members as $member): ?>
                <article class="member-card">
                    <img src="<?= $member['image']; ?>" alt="Portræt af <?= $member['name']; ?>" class="member-img">

                    <h3><?= $member['name']; ?></h3>
                    <p><?= $member['education']; ?></p>
                    <p><?= $member['semester']; ?></p>
                </article>
            <?php endforeach; ?>
        </section>

        <div class="members-link-wrapper">
            <a href="#" class="members-link">
                SE ALLE MEDLEMMER
                <span aria-hidden="true">&#8594;</span>
            </a>
        </div>

    </section>

</main>