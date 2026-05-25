<!-- Login side -->
<main class="container login-page">

    <!-- Venstre billede -->
    <div class="login-image">
        <img src="/assets/img/left-layout-picture.webp" alt="Studerende til socialt event">
    </div>

    <!-- Login formular -->
    <section class="login-content">
        <h1 class="form-title">LOG IND</h1>

        <section class="form-container login-container">

            <form method="POST" action="">
                <?php csrf_input(); ?>

                <!-- Email -->
                <label for="user_email" class="hide_label">Studiemail</label>
                <input id="user_email" type="email" name="user_email" placeholder="Studiemail" required>

                <!-- Adgangskode -->
                <label for="user_password" class="hide_label">Adgangskode</label>
                <input id="user_password" type="password" name="user_password" placeholder="Adgangskode" required>

                <!-- Login knap -->
                <button class="btn btn-primary" type="submit">LOG IND</button>
            </form>

            <!-- Link til oprettelse -->
            <p class="form-link-text">
                Ikke medlem endnu?
                <a href="/signup">Opret dig her</a>
            </p>
        </section>
    </section>
</main>