<main class="container login-page">

    <div class="login-image">
        <img src="/assets/img/left-layout-picture.webp" alt="Studerende til socialt event">
    </div>

    <div class="login-content">
        <h1 class="form-title">LOG IND</h1>

        <section class="form-container login-container">

            <form method="POST" action="">
                <?php csrf_input(); ?>
                <label for="user_email" class="hide_label">Studiemail</label>
                <input id="user_email" type="email" name="user_email" placeholder="Studiemail" required>
                
                <label for="user_password" class="hide_label">Adgangskode</label>
                <input id="user_password" type="password" name="user_password" placeholder="Adgangskode" required>
                
                <button class="btn btn-primary" type="submit">LOG IND</button>
            </form>

            <div>
                <p class="form-link-text">
                    Ikke medlem endnu?
                    <a href="/signup">Opret dig her</a>
                </p>
            </div>

        </section>
    </div>

</main>