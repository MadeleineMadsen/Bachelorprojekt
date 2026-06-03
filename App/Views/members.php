<!-- Medlemmer side -->
<main class="members-page">

    <!-- Hero sektion -->
    <section class="members-hero">
        <h1>MEDLEMMER</h1>
    </section>

    <!-- Banner -->
    <?php
    $bannerText = 'SE ALLE MEDLEMMER';
    include __DIR__ . '/micro/___banner.php';
    ?>

    <!-- Medlemmer overview -->
    <section class="members-overview">

        <h2>ALLE AKTIVE MEDLEMMER I GBG SOCIAL</h2>

        <!-- Statistik -->
        <div class="members-stats">
            <div class="members-stat">
                <h3><?= $memberStats['active_members']; ?></h3>
                <p>AKTIVE MEDLEMMER</p>
            </div>

            <div class="members-stat">
                <h3><?= $memberStats['board_members']; ?></h3>
                <p>BESTYRELSES-MEDLEMMER</p>
            </div>

            <div class="members-stat">
                <h3><?= $memberStats['events_this_year']; ?></h3>
                <p>EVENTS OM ÅRET</p>
            </div>
        </div>

        <!-- Søgning og filter -->
        <div class="members-filter-container">
            <form class="search-form" action="" onsubmit="return false;">

                <!-- Søgefelt -->
                <div class="search-field">
                    <input id="memberSearch" type="search" name="search" placeholder="SØG">

                    <button type="button" aria-label="Søg">
                        <img src="/assets/img/icons/search.svg" alt="Søg ikon">
                    </button>
                </div>

                <!-- Uddannelsesfilter -->
                <div class="members-filter-select-wrap">
                    <select name="education" id="educationFilter" class="filter-select">
                        <option value="">ALLE MEDLEMMER</option>

                        <!-- Loop gennem uddannelser -->
                        <?php foreach ($educations as $education): ?>
                            <option value="<?= $education['education_pk']; ?>">
                                <?= htmlspecialchars($education['education_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <img src="/assets/img/icons/arrow-down.svg" alt="" class="education-filter-arrow">
                </div>
            </form>
        </div>

        <!-- Medlemsgrid -->
        <section class="members-grid" aria-label="Medlemmer">

            <!-- Loop gennem medlemmer -->
            <?php foreach ($members as $member): ?>

                <article class="member-card" data-name="<?= strtolower(htmlspecialchars($member['user_name'])); ?>"
                    data-education="<?= $member['education_fk'] ?? ''; ?>">

                    <!-- Profilbillede -->
                    <img src="<?= !empty($member['user_profile_image'])
                        ? '/assets/img/uploads/' . htmlspecialchars($member['user_profile_image'])
                        : '/assets/img/uploads/default_profile_image.webp' ?>"
                        alt="Portræt af <?= htmlspecialchars($member['user_name']); ?>" class="member-img">

                    <!-- Navn -->
                    <h3><?= htmlspecialchars($member['user_name']); ?></h3>

                    <!-- Uddannelse -->
                    <p>
                        <?= !empty($member['education_name'])
                            ? htmlspecialchars($member['education_name'])
                            : 'Bestyrelsesmedlem' ?>
                    </p>

                    <!-- Semester -->
                    <?php if (!empty($member['semester_number'])): ?>
                        <p><?= htmlspecialchars($member['semester_number']); ?>. semester</p>
                    <?php endif; ?>

                </article>
            <?php endforeach; ?>
        </section>
    </section>
</main>