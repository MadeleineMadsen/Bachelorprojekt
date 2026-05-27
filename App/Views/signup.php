<!-- Opret bruger side -->
<main class="container signup-page">

    <!-- Venstre billede -->
    <div class="signup-image">
        <img src="/assets/img/left-layout-picture.webp" alt="Studerende til socialt event">
    </div>

    <!-- Opret bruger formular -->
    <section class="signup-content">
        <h1 class="form-title">OPRET DIG</h1>

        <section class="form-container signup-container">

            <form method="POST" action="">
                <?php csrf_input(); ?>

                <!-- Navn -->
                <label for="user_name" class="hide_label">Fornavn</label>
                <input id="user_name" type="text" name="user_name" value="<?= e(old('user_name')) ?>"
                    placeholder="Fornavn" required>

                <label for="user_last_name" class="hide_label">Efternavn</label>
                <input id="user_last_name" type="text" name="user_last_name" value="<?= e(old('user_last_name')) ?>"
                    placeholder="Efternavn" required>

                <!-- Email -->
                <label for="user_email" class="hide_label">Studiemail</label>
                <input id="user_email" type="email" name="user_email" value="<?= e(old('user_email')) ?>"
                    placeholder="Studiemail" required>

                <!-- Adgangskode -->
                <label for="user_password" class="hide_label">Adgangskode</label>
                <input id="user_password" type="password" name="user_password" placeholder="Adgangskode" required>

                <label for="confirm_password" class="hide_label">Bekræft adgangskode</label>
                <input id="confirm_password" type="password" name="confirm_password" placeholder="Bekræft adgangskode"
                    required>

                <!-- Vilkår og betingelser -->
                <div class="checkbox-wrapper">
                    <div class="term_check">
                        <input id="terms" type="checkbox" name="terms" required>

                        <label for="terms">
                            Jeg accepterer privatlivspolitikken samt <a class="terms" href="/terms">Vilkår &
                                betingelser</a> for brug af platformen.
                        </label>
                    </div>
                </div>

                <!-- Submit knap -->
                <button class="btn btn-primary" type="submit">OPRET DIG</button>
            </form>

            <!-- Link til login -->
            <p class="form-link-text">Har du allerede en bruger? <a href="/login">Log ind her</a> </p>
        </section>
    </section>
</main>