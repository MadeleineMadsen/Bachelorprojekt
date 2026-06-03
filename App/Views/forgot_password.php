<!-- Glemt kodeord -->
<main class="container login-page">

    <!-- Venstre billede -->
    <div class="login-image">
        <img src="/assets/img/left-layout-picture.webp" alt="Studerende til socialt event">
    </div>

    <!-- Formular -->
    <section class="login-content">
        <h1 class="form-title">GLEMT KODEORD</h1>

        <section class="form-container login-container">

            <form method="POST" action="">
                <?php csrf_input(); ?>

                <!-- Email -->
                <label for="user_email" class="hide_label">Studiemail</label>
                <input id="user_email" type="email" name="user_email" placeholder="Studiemail" required>

                <!-- Send knap -->
                <button class="btn btn-primary" type="submit">SEND LINK</button>
            </form>

            <!-- Link tilbage til login -->
            <p class="form-link-text">
                Husker du din kode?
                <a href="/login">Log ind her</a>
            </p>
        </section>
    </section>
</main>
