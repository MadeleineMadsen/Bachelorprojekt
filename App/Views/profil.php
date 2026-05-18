<?php
$role = $user['role_fk'] ?? '3';

$containerClass = match ($role) {
    '1' => 'profile-admin-container',
    '2' => 'profile-member-container',
    default => 'profile-user-container',
};

$showMemberFields = in_array($role, ['1', '2']);
?>

<main class="profile-page container">
    <section class="profile-hero">
        <div class="hello-text">
            <h1>HEJ IGEN</h1>
            <h2>
                <?= htmlspecialchars($user['user_name']) ?>
            </h2>
        </div>

        <div class="profile-intro">
            <div>
                <h3>MIN PROFIL</h3>
                <p>Her kan du se og redigere dine personlige oplysninger</p>
            </div>

            <form id="profileImageForm" method="POST" action="/profil/update-image" enctype="multipart/form-data">
                <?php csrf_input(); ?>
                <div class="profile-image-wrapper">
                    <img id="profilePreview" src="<?= !empty($user['user_profile_image'])
                        ? '/assets/img/uploads/' . htmlspecialchars($user['user_profile_image'])
                        : '/assets/img/uploads/default_profile_image.webp' ?>" alt="Profilbillede"
                        class="profile-img profile-profileimg">

                    <label class="camera-btn">
                        <img src="/assets/img/icons/camera_icon.png" alt="Kamera ikon">
                        <input id="profileImageInput" type="file" name="profile_image" accept="image/*" hidden>
                    </label>
                </div>
            </form>
        </div>
    </section>

    <section class="form-container <?= $containerClass ?>">
        <h4>REDIGER PROFIL</h4>

        <form method="POST" action="/profil/update">
            <?php csrf_input(); ?>
            <input type="text" name="user_name" value="<?= htmlspecialchars($user['user_name']) ?>"
                placeholder="Fornavn" required>

            <input type="text" name="user_last_name" value="<?= htmlspecialchars($user['user_last_name'] ?? '') ?>"
                placeholder="Efternavn" required>

            <?php if ($showMemberFields): ?>
                <select name="education_fk" required>
                    <option value="">Vælg studieretning</option>

                    <?php foreach ($educations as $education): ?>
                        <option value="<?= $education['education_pk'] ?>" <?= (($member['education_fk'] ?? '') == $education['education_pk']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($education['education_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="semester_fk" required>
                    <option value="">Vælg semester</option>

                    <?php foreach ($semesters as $semester): ?>
                        <option value="<?= $semester['semester_pk'] ?>" <?= (($member['semester_fk'] ?? '') == $semester['semester_pk']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($semester['semester_number']) ?>. semester
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <input type="email" name="user_email" value="<?= htmlspecialchars($user['user_email']) ?>"
                placeholder="Studiemail" required>

            <input type="password" name="user_password" placeholder="Ret adgangskode">

            <div class="profile-button-row">
                <button class="btn btn-primary" type="submit">GEM ÆNDRINGER</button>

                <button class="btn btn-delete" type="submit" form="deleteProfileForm">
                    SLET MIN PROFIL
                </button>
            </div>
        </form>

        <form id="deleteProfileForm" method="POST" action="/profil/delete"
            onsubmit="return confirm('Er du sikker på, at du vil slette din profil?');">
            <?php csrf_input(); ?>
        </form>
    </section>
</main>