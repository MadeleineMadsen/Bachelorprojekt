<?php

?>

<main class="profile-page container">
    <section class="profile-hero">
        <div class="hello-text">
            <h1>HEJ IGEN</h1>
            <h2>NAVN</h2>
        </div>

        <div class="profile-intro">
            <div>
                <h3>MIN PROFIL</h3>
                <p>Her kan du se og redigere dine personlige oplysninger</p>
            </div>

            <div class="profile-image-wrapper">
                <img src="/assets/img/uploads/test_profile.png" alt="Profilbillede" class="profile-img profile-profileimg">
                <button class="camera-btn" type="button"><img src="/assets/img/icons/camera_icon.png"
                        alt="Kamera ikon"></button>
            </div>
        </div>
    </section>

    <section class="form-container profile-admin-container">
        <h4>REDIGER PROFIL</h4>

        <form method="POST">
            <input type="text" name="name" placeholder="Fornavn" required>

            <input type="text" name="last_name" placeholder="Efternavn" required>

            <input type="text" name="education" placeholder="Studieretning" required>

            <input type="text" name="semester" placeholder="Semester" required>

            <input type="email" name="email" placeholder="Studiemail" required>

            <input type="password" name="password" placeholder="Adgangskode" required>

            <button class="btn btn-primary" type="submit">GEM ÆNDRINGER</button>
        </form>
    </section>
</main>