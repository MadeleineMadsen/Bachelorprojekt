<?php

?>

<main class="container login-page">

    <div class="login-image">
        <img src="/assets/img/left-layout-picture.webp" alt="Studerende til socialt event">
    </div>

    <div class="login-content">
        <h1 class="form-title">LOG IND</h1>

        <section class="form-container login-container">
            <form method="POST">
                <input type="email" name="email" placeholder="Studiemail" required>

                <input type="password" name="password" placeholder="Adgangskode" required>

                <button class="btn btn-primary" type="submit">LOG IND</button>
            </form>

            <div>
                <p class="form-link-text">Ikke medlem endnu? <a href="opret_dig">Opret dig her</a> </p>
            </div>
        </section>
    </div>

</main>