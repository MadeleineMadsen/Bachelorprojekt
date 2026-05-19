<?php

?>

<main class="container signup-page">

    <div class="signup-image">
        <img src="/assets/img/left-layout-picture.webp" alt="Studerende til socialt event">
    </div>

    <div class="signup-content">
        <h1 class="form-title">OPRET DIG</h1>

        <section class="form-container signup-container">
            <form method="POST" action="">
                <?php csrf_input(); ?>
                <input type="text" name="user_name" placeholder="Fornavn" required>

                <input type="text" name="user_last_name" placeholder="Efternavn" required>

                <input type="email" name="user_email" placeholder="Studiemail" required>

                <input type="password" name="user_password" placeholder="Adgangskode" required>

                <input type="password" name="confirm_password" placeholder="Bekræft adgangskode" required>

                <label class="checkbox-wrapper">
                    <a class="terms" href="/terms">Vilkår & betingelser</a>
                    <div class="term_check" >
                        <input type="checkbox" name="terms" required>
                        <span>Jeg accepterer privatlivspolitikken samt vilkår for brug af platformen.</span>
                    </div>
                </label>

                <button class="btn btn-primary" type="submit">OPRET DIG</button>
            </form>

            <div>
                <p class="form-link-text">Har du allerede en bruger? <a href="log_ind">Log ind her</a> </p>
            </div>
        </section>
    </div>


</main>