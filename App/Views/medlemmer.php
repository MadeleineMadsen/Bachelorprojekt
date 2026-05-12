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

        <h2>ALLE AKTIVE MEDLEMMER I GBG SOCIAL</h2>

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
                <h3>+<?= $memberStats['events_this_year']; ?></h3>
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

                    <?php foreach ($educations as $education): ?>
                        <option value="<?= $education['education_pk']; ?>">
                            <?= htmlspecialchars($education['education_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>

        <section class="members-grid" aria-label="Medlemmer">
            <?php foreach ($members as $member): ?>
                <article 
                    class="member-card"
                    data-name="<?= strtolower(htmlspecialchars($member['user_name'])); ?>"
                    data-education="<?= $member['education_fk']; ?>"
                >
                    <img src="/public/assets/img/uploads/test_profile.png" alt="Portræt af <?= htmlspecialchars($member['user_name']); ?>" class="member-img">

                    <h3><?= htmlspecialchars($member['user_name']); ?></h3>
                    <p><?= htmlspecialchars($member['education_name']); ?></p>
                    <p><?= htmlspecialchars($member['semester_number']); ?>. semester</p>
                </article>
            <?php endforeach; ?>
        </section>

        <div class="members-link-wrapper">
            <a href="#" class="members-link btn-nav">SE ALLE MEDLEMMER</a>
        </div>

    </section>

</main>