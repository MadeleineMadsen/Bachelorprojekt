<?php

?>

<main class="container login-page">

    <div class="login-image">
        <img src="/assets/img/left-layout-picture.webp" alt="Studerende til socialt event">
    </div>

    <div class="login-content">
        <h1 class="form-title">LOG IND</h1>

        <section class="form-container login-container">

            <?php if (isset($_SESSION['success'])): ?>
                <div class="form-message success">
                    <?= $_SESSION['success']; ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="form-message error">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="">
                <?php csrf_input(); ?>
                <input type="email" name="user_email" placeholder="Studiemail" required>

                <input type="password" name="user_password" placeholder="Adgangskode" required>

                <button class="btn btn-primary" type="submit">LOG IND</button>
            </form>

            <div>
                <p class="form-link-text">
                    Ikke medlem endnu?
                    <a href="opret_dig">Opret dig her</a>
                </p>
            </div>

        </section>
    </div>

</main>