<?php

?>

<main class="profile-page container">
    <section class="profile-hero">
        <div class="hello-text">
            <h1>HEJ IGEN</h1>
            <h2><?= htmlspecialchars($user['user_name']) ?></h2>
        </div>

        <div class="profile-intro">
            <div>
                <h3>MIN PROFIL</h3>
                <p>Her kan du se og redigere dine personlige oplysninger</p>
            </div>

            <div class="profile-image-wrapper">
                <img src="/assets/img/uploads/test_profile.png" alt="Profilbillede"
                    class="profile-img profile-profileimg">
                <button class="camera-btn" type="button"><img src="/assets/img/icons/camera_icon.png"
                        alt="Kamera ikon"></button>
            </div>
        </div>
    </section>

    <section class="form-container profile-admin-container">
        <h4>REDIGER PROFIL</h4>

        <form method="POST" action="/profil/update">
            <input type="text" name="user_name" value="<?= htmlspecialchars($user['user_name']) ?>"
                placeholder="Fornavn" required>

            <input type="text" name="user_last_name" value="<?= htmlspecialchars($user['user_last_name'] ?? '') ?>"
                placeholder="Efternavn" required>

            <input type="text" name="education" value="<?= htmlspecialchars($member['education'] ?? '') ?>"
                placeholder="Studieretning" required>

            <input type="text" name="semester" value="<?= htmlspecialchars($member['semester'] ?? '') ?>"
                placeholder="Semester" required>

            <input type="email" name="user_email" value="<?= htmlspecialchars($user['user_email']) ?>"
                placeholder="Studiemail" required>

            <input type="password" name="user_password" placeholder="Adgangskode">

            <div class="profile-button-row">
                <button class="btn btn-primary" type="submit">GEM ÆNDRINGER</button>

                <button class="btn btn-delete" type="submit" form="deleteProfileForm">
                    SLET MIN PROFIL
                </button>
            </div>
        </form>

        <form id="deleteProfileForm" method="POST" action="/profil/delete"
            onsubmit="return confirm('Er du sikker på, at du vil slette din profil?');"></form>
    </section>
</main>