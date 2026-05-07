<main class="godkend-main">
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
                        <h2 class="godkend-name"><strong><?= $app['user_name']; ?></strong>, <?= $app['user_last_name']; ?> år</h2>
                        <p><?= $app['education']; ?></p>
                        <p><?= $app['user_email']; ?></p>
                    </div>
                </div>

                <!-- Midte -->
                <div class="godkend-middle inner">
                    <p class="godkend-date">Ansøgt d. <?= date('d M', strtotime($app['applied_at'])); ?></p>
                    <p class="motivation">
                        <?= $app['application_text']; ?>
                    </p>
                </div>

                <!-- Højre (knapper) -->
                <div class="godkend-right">
                    <form method="POST" action="/godkend_medlem">
                        <input type="hidden" name="member_pk" value="<?= $app['member_pk']; ?>">
                        <button class="btn btn-primary">GODKEND</button>
                    </form>

                    <form method="POST" action="/afvis_medlem">
                        <input type="hidden" name="member_pk" value="<?= $app['member_pk']; ?>">
                        <button class="btn btn-secondary">AFVIS</button>
                    </form>
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
                        <img src="/assets/img/icons/search.svg" alt="">
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