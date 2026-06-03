<?php
$applications = $applications ?? [];
$educations = $educations ?? [];
$members = $members ?? [];
?>

<!-- Godkend ansøgninger side -->
<main class="approve-main">

    <!-- Ansøgnings- og medlemscontainer -->
    <section class="accept-container">

        <!-- Header -->
        <section class="header-container">
            <h1 class="approve-header">ANSØGNINGER</h1>

            <p class="approve-p">Ansøgninger om medlemsskab til godkendelse</p>
        </section>

        <!-- Afventende ansøgninger -->
        <h3 class="approve-sub">
            Afventer godkendelse
            <?php if (!empty($applications)): ?>
                <span class="approve-count">
                    <?= count($applications); ?>
                </span>
            <?php endif; ?>
        </h3>

        <!-- Liste med ansøgninger -->
        <section class="approve-container">
            <?php if (empty($applications)): ?>
                <p class="approve-empty">Ingen ansøgninger afventer godkendelse</p>
            <?php endif; ?>

            <!-- Loop gennem ansøgninger -->
            <?php foreach ($applications as $app): ?>

                <article class="approve-card">

                    <!-- Profilinformation -->
                    <div class="approve-left inner">
                        <img src="<?= !empty($app['user_profile_image'])
                            ? '/assets/img/uploads/' . htmlspecialchars($app['user_profile_image'])
                            : '/assets/img/uploads/default_profile_image.webp' ?>" alt="Profilbillede"
                            class="profile-img profile-medium">

                        <div class="approve-info">
                            <h2 class="approve-name"><strong><?= $app['user_name']; ?>
                                    <?= $app['user_last_name']; ?></strong></h2>

                            <p><?= htmlspecialchars($app['education_name']); ?></p>

                            <p><?= $app['user_email']; ?></p>
                        </div>
                    </div>

                    <!-- Ansøgningsinformation -->
                    <div class="approve-middle inner">
                        <p class="approve-date">Ansøgt d. <?= formatDanishDate($app['applied_at']); ?></p>

                        <p class="motivation">
                            <?= $app['application_text']; ?>
                        </p>
                    </div>

                    <!-- Godkend / afvis -->
                    <div class="approve-right">

                        <!-- Godkend medlem -->
                        <form method="POST" action="/approve_member">
                            <?php csrf_input(); ?>

                            <input type="hidden" name="member_pk" value="<?= $app['member_pk']; ?>">

                            <button class="btn btn-primary">GODKEND</button>
                        </form>

                        <!-- Afvis medlem -->
                        <form method="POST" action="/reject_member">
                            <?php csrf_input(); ?>

                            <input type="hidden" name="member_pk" value="<?= $app['member_pk']; ?>">

                            <button class="btn btn-secondary">AFVIS</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <!-- Eksisterende medlemmer -->
        <h3 class="existing-members">Eksisterende medlemmer</h3>

        <!-- Søgning og filter -->
        <div class="filter-container">
            <form class="search-form" action="" onsubmit="return false;">

                <!-- Søgefelt -->
                <div class="search-field">
                    <input type="text" id="memberSearch" placeholder="SØG">

                    <button type="button" aria-label="Søg">
                        <img src="/assets/img/icons/search.svg" alt="Søg ikon">
                    </button>
                </div>

                <!-- Uddannelsesfilter -->
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

        <!-- Medlemscarousel -->
        <section class="member-carousel-section">
            <button class="carousel-arrow carousel-prev" type="button" aria-label="Forrige">
                &#8592;
            </button>

            <div class="member-carousel" id="memberCarousel">

                <!-- Loop gennem eksisterende medlemmer -->
                <?php foreach ($members as $member): ?>
                    <article class="member-slide" data-name="<?= strtolower($member['user_name']); ?>"
                        data-education="<?= $member['education_fk'] ?? ''; ?>">

                        <!-- Formular til sletning af medlem -->
                        <form id="deleteMemberForm-<?= htmlspecialchars($member['member_pk']) ?>" method="POST"
                            action="/delete_member">
                            <?php csrf_input(); ?>

                            <input type="hidden" name="member_pk" value="<?= $member['member_pk']; ?>">

                            <button type="button" class="member-delete-btn" aria-label="Slet medlem"
                                data-modal-open="deleteMemberModal-<?= htmlspecialchars($member['member_pk']) ?>">
                                X
                            </button>
                        </form>

                        <?php
                        // Data til bekræftelsesmodal
                        $modalId = 'deleteMemberModal-' . $member['member_pk'];
                        $formId = 'deleteMemberForm-' . $member['member_pk'];
                        $title = 'Slet medlem?';
                        $text = 'Er du sikker på, at du vil slette ' . $member['user_name'] . ' som medlem?';
                        $confirmText = 'Ja, slet medlem';

                        // Genbrugelig modal til bekræftelse af sletning
                        include __DIR__ . '/micro/___confirm_modal.php';
                        ?>

                        <!-- Medlemsinformation -->
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