<main class="godkend-main">
    <section class="accept-container">
        <div class="header-container">
            <h1 class="godkend-header">ANSØGNINGER</h1>
            <p class="godkend-p">Ansøgninger om medlemsskab til godkendelse</p>
        </div>

        <h3 class="godkend-sub">Afventer godkendelse</h3>

        <div class="godkend-container">
            <?php if (empty($applications)): ?>
                <p class="godkend-empty">Ingen ansøgninger afventer godkendelse</p>
            <?php endif; ?>
            <?php foreach ($applications as $app): ?>
                <div class="godkend-card">

                    <!-- Venstre (profil) -->
                    <div class="godkend-left inner">
                        <img src="<?= !empty($app['user_profile_image'])
                            ? '/assets/img/uploads/' . htmlspecialchars($app['user_profile_image'])
                            : '/assets/img/uploads/default_profile_image.webp' ?>" alt="Profilbillede"
                            class="profile-img profile-medium">
                        <div class="godkend-info">
                            <h2 class="godkend-name"><strong><?= $app['user_name']; ?>
                                    <?= $app['user_last_name']; ?></strong></h2>
                            <p><?= htmlspecialchars($app['education_name']); ?></p>
                            <p><?= $app['user_email']; ?></p>
                        </div>
                    </div>

                    <!-- Midte -->
                    <div class="godkend-middle inner">
                        <p class="godkend-date">Ansøgt d. <?= formatDanishDate($app['applied_at']); ?></p>
                        <p class="motivation">
                            <?= $app['application_text']; ?>
                        </p>
                    </div>

                    <!-- Højre (knapper) -->
                    <div class="godkend-right">
                        <form method="POST" action="/godkend_medlem">
                            <?php csrf_input(); ?>
                            <input type="hidden" name="member_pk" value="<?= $app['member_pk']; ?>">
                            <button class="btn btn-primary">GODKEND</button>
                        </form>

                        <form method="POST" action="/afvis_medlem">
                            <?php csrf_input(); ?>
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
                        <img src="/assets/img/icons/search.svg" alt="Søg ikon">
                    </button>
                </div>

                <select name="education" id="educationFilter" class="filter-select">
                    <option value="">ALLE MEDLEMMER</option>

                    <?php foreach ($educations as $education): ?>
                        <option value="<?= $education['education_pk']; ?>">
                            <?= htmlspecialchars($education['education_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>


        <section class="member-carousel-section">
            <button class="carousel-arrow carousel-prev" type="button" aria-label="Forrige">
                &#8592;
            </button>

            <div class="member-carousel" id="memberCarousel">
                <?php foreach ($members as $member): ?>
                    <article class="member-slide" data-name="<?= strtolower($member['user_name']); ?>"
                        data-education="<?= $member['education_fk'] ?? ''; ?>">

                        <form id="deleteMemberForm-<?= htmlspecialchars($member['member_pk']) ?>" method="POST"
                            action="/slet_medlem">
                            <?php csrf_input(); ?>

                            <input type="hidden" name="member_pk" value="<?= $member['member_pk']; ?>">

                            <button type="button" class="member-delete-btn" aria-label="Slet medlem"
                                data-modal-open="deleteMemberModal-<?= htmlspecialchars($member['member_pk']) ?>">
                                X
                            </button>
                        </form>

                        <?php
                        $modalId = 'deleteMemberModal-' . $member['member_pk'];
                        $formId = 'deleteMemberForm-' . $member['member_pk'];
                        $title = 'Slet medlem?';
                        $text = 'Er du sikker på, at du vil slette ' . $member['user_name'] . ' som medlem?';
                        $confirmText = 'Ja, slet medlem';

                        include __DIR__ . '/micro/___confirm_modal.php';
                        ?>

                        <img src="<?= !empty($member['user_profile_image'])
                            ? '/assets/img/uploads/' . htmlspecialchars($member['user_profile_image'])
                            : '/assets/img/uploads/default_profile_image.webp' ?>" alt="Profilbillede"
                            class="profile-img profile-medium">
                        <h3>
                            <?= $member['user_name']; ?>
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
    </section>
</main>