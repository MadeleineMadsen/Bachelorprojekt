<?php

// Brugerens rolle
$role = $user['role_fk'] ?? '3';

// Bestemmer container class ud fra rolle
$containerClass = match ($role) {
    '1' => 'profile-admin-container',
    '2' => 'profile-member-container',
    default => 'profile-user-container',
};

// Viser ekstra medlemsfelter for admins og medlemmer
$showMemberFields = in_array($role, ['1', '2']);
?>

<!-- Profil side -->
<main class="profile-page container">

    <!-- Hero sektion -->
    <section class="profile-hero">

        <!-- Velkomsttekst -->
        <div class="hello-text">
            <h1>HEJ IGEN</h1>

            <h2>
                <?= htmlspecialchars($user['user_name']) ?>
            </h2>
        </div>

        <!-- Profilintro -->
        <section class="profile-intro">
            <div>
                <h3>MIN PROFIL</h3>

                <p>Her kan du se og redigere dine personlige oplysninger</p>
            </div>

            <!-- Upload af profilbillede -->
            <form id="profileImageForm" method="POST" action="/profile/update-image" enctype="multipart/form-data">
                <?php csrf_input(); ?>

                <div class="profile-image-wrapper">
                    <!-- Profilbillede -->
                    <img id="profilePreview" src="<?= !empty($user['user_profile_image'])
                        ? '/assets/img/uploads/' . htmlspecialchars($user['user_profile_image'])
                        : '/assets/img/uploads/default_profile_image.webp' ?>" alt="Profilbillede"
                        class="profile-img profile-profileimg">

                    <!-- Kamera ikon -->
                    <label class="camera-btn">
                        <img src="/assets/img/icons/camera_icon.png" alt="Kamera ikon">

                        <input id="profileImageInput" type="file" name="profile_image" accept="image/*" hidden>
                    </label>
                </div>
            </form>
        </section>
    </section>

    <!-- Rediger profil -->
    <section class="form-container <?= $containerClass ?>">
        <h4>REDIGER PROFIL</h4>

        <!-- Formular -->
        <form method="POST" action="/profile/update">
            <?php csrf_input(); ?>

            <!-- Navn -->
            <input type="text" name="user_name" value="<?= htmlspecialchars($user['user_name']) ?>"
                placeholder="Fornavn" required>

            <input type="text" name="user_last_name" value="<?= htmlspecialchars($user['user_last_name'] ?? '') ?>"
                placeholder="Efternavn" required>

            <!-- Medlemsfelter -->
            <?php if ($showMemberFields): ?>

                <!-- Studieretning -->
                <select name="education_fk" required>
                    <option value="">Vælg studieretning</option>

                    <?php foreach ($educations as $education): ?>
                        <option value="<?= $education['education_pk'] ?>" <?= (($member['education_fk'] ?? '') == $education['education_pk']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($education['education_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Semester -->
                <select name="semester_fk" required>
                    <option value="">Vælg semester</option>

                    <?php foreach ($semesters as $semester): ?>
                        <option value="<?= $semester['semester_pk'] ?>" <?= (($member['semester_fk'] ?? '') == $semester['semester_pk']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($semester['semester_number']) ?>. semester
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <!-- Email -->
            <input type="email" name="user_email" value="<?= htmlspecialchars($user['user_email']) ?>"
                placeholder="Studiemail" required>

            <!-- Adgangskode -->
            <input type="password" name="user_password" placeholder="Ret adgangskode">

            <!-- Knapper -->
            <div class="profile-button-row">
                <button class="btn btn-primary" type="submit">GEM ÆNDRINGER</button>

                <button class="btn btn-delete" type="button" data-modal-open="deleteProfileModal">
                    SLET MIN PROFIL
                </button>
            </div>
        </form>

        <!-- Formular til sletning af profil -->
        <form id="deleteProfileForm" method="POST" action="/profile/delete">
            <?php csrf_input(); ?>
        </form>
    </section>
</main>

<?php
// Data til bekræftelsesmodal
$modalId = 'deleteProfileModal';
$formId = 'deleteProfileForm';
$title = 'Slet profil?';
$text = 'Er du sikker på, at du vil slette din profil? Denne handling kan ikke fortrydes.';
$confirmText = 'Ja, slet min profil';

// Genbrugelig modal til bekræftelse
include __DIR__ . '/micro/___confirm_modal.php';
?>