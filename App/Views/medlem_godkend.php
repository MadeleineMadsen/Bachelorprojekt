<?php
$applications = [
    [
        'name' => 'Marie Andersen',
        'age' => 25,
        'title' => 'Datamatiker',
        'email' => 'marie.andersen@email.dk',
        'date' => '22. april',
        'motivation' => 'blabla. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sunt quod accusantium rem, beatae maiores numquam. Nisi, officiis et, ratione nobis suscipit vero quo iste, ex corporis voluptas nam error eius?Jeg vil gerne være en del af GBG SOCIAL, fordi jeg elsker at møde nye mennesker og deltage i sociale arrangementer.'
    ],
    [
        'name' => 'Jonas Mikkelsen',
        'age' => 28,
        'title' => 'Frontend udvikler',
        'email' => 'jonas.mikkelsen@email.dk',
        'date' => '20. april',
        'motivation' => 'Jeg søger, fordi jeg gerne vil være med.'
    ],
    [
        'name' => 'Sofie Larsen',
        'age' => 23,
        'title' => 'Studerende',
        'email' => 'sofie.larsen@email.dk',
        'date' => '18. april',
        'motivation' => 'Jeg er ny i byen og vil rigtig gerne møde nye mennesker gennem events og fællesskab.'
    ],
];

$members = [
    [
        'name' => 'Isabella',
        'education' => 'Multimediedesign',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Maja',
        'education' => 'Datamatiker',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Noah',
        'education' => 'Webudvikling',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
        [
        'name' => 'Isabella',
        'education' => 'Multimediedesign',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Maja',
        'education' => 'Datamatiker',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Noah',
        'education' => 'Webudvikling',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
        [
        'name' => 'Isabella',
        'education' => 'Multimediedesign',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Maja',
        'education' => 'Datamatiker',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
    [
        'name' => 'Noah',
        'education' => 'Webudvikling',
        'image' => '/assets/img/uploads/test_profile.png'
    ],
];

?>

<main>
    <section class="container">
    <div class="header-container">
        <h1 class="godkend-header">ANSØGNINGER</h1>
        <p class="godkend-p">Ansøgninger om medlemsskab til godkendelse</p>
    </div>

    <h3 class="godkend-sub">Afventer godkendelse</h3>

    <div class="godkend-container">
        <?php foreach ($applications as $app): ?>
            <div class="godkend-card">

                <!-- Venstre (profil) -->
                <div class="godkend-left inner">
                    <img src="/assets/img/uploads/test_profile.png" alt="" class="profile-img profile-medium">

                    <div class="godkend-info">
                        <h2 class="godkend-name"><strong><?= $app['name']; ?></strong>, <?= $app['age']; ?> år</h2>
                        <p><?= $app['title']; ?></p>
                        <p><?= $app['email']; ?></p>
                    </div>
                </div>

                <!-- Midte -->
                <div class="godkend-middle inner">
                    <p class="godkend-date">Ansøgt d. <?= $app['date']; ?></p>
                    <p class="motivation">
                        <?= $app['motivation']; ?>
                    </p>
                </div>

                <!-- Højre (knapper) -->
                <div class="godkend-right">
                    <button class="btn btn-primary">GODKEND</button>
                    <button class="btn btn-secondary">AFVIS</button>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <h3 class="allerede-medlem">Eksisterende medlemmer</h3>
        <div class="filter-container">
            <form class="search-form" action="" onsubmit="return false;">
                <div class="search-field">
                    <input type="text" id="memberSearch" placeholder="SØG">
                    <button type="button" aria-label="Søg">
                        <img src="/assets/img/search.svg" alt="">
                    </button>
                </div>

                <select name="education" id="educationFilter" class="filter-select">
                    <option value="">ALLE MEDLEMMER</option>
                    <!-- TODO: Ret til at hente fra database i stedet for hardcodet -->
                    <option value="datamatiker">Datamatiker</option>
                    <option value="multimediedesign">Multimediedesign</option>
                    <option value="webudvikling">Webudvikling</option>
                </select>
            </form>
        </div>


    <section class="member-carousel-section">
        <button class="carousel-arrow carousel-prev" type="button" aria-label="Forrige">
            &#8592;
        </button>

        <div class="member-carousel" id="memberCarousel">
            <?php foreach ($members as $member): ?>
                <article    class="member-slide"
                            data-name="<?= strtolower($member['name']); ?>"
                            data-education="<?= strtolower($member['education']); ?>"
                            >
                    <img src="<?= $member['image']; ?>" alt="" class="profile-img profile-medium">

                    <h3>
                        <?= $member['name']; ?>
                    </h3>

                    <p><?= $member['education']; ?></p>
                </article>
            <?php endforeach; ?>
        </div>

        <button class="carousel-arrow carousel-next" type="button" aria-label="Næste">
            &#8594;
        </button>
    </section>
    </section>
</main>