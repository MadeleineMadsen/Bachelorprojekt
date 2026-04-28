<?php

?>

<main class="container">
    <h1 class="form-title">OPRET DIG</h1>

    <section class="form-container signup-container">
        <form method="POST">
            <input type="text" name="name" placeholder="Fornavn" required>

            <input type="text" name="last_name" placeholder="Efternavn" required>

            <input type="email" name="email" placeholder="Studiemail" required>

            <input type="password" name="password" placeholder="Adgangskode" required>

            <input type="password" name="password" placeholder="Bekræft adgangskode" required>

            <label class="checkbox-wrapper">
                <input type="checkbox" name="terms" required>
                <span>Jeg accepterer vilkår og betingelser samt privatlivspolitik</span>
            </label>

            <button class="btn btn-primary" type="submit">OPRET DIG</button>
        </form>

        <div>
            <p class="form-link-text">Har du allerede en bruger? <a href="log_ind">Log ind her</a> </p>
        </div>
    </section>
</main>