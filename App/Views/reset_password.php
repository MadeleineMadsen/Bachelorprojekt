<!-- Nulstil kodeord -->
<main class="container login-page">

    <!-- Venstre billede -->
    <div class="login-image">
        <img src="/assets/img/left-layout-picture.webp" alt="Studerende til socialt event">
    </div>

    <!-- Formular -->
    <section class="login-content">
        <h1 class="form-title">NY ADGANGSKODE</h1>

        <section class="form-container login-container">

            <form method="POST" action="">
                <?php csrf_input(); ?>

                <!-- Ny adgangskode -->
                <label for="user_password" class="hide_label">Ny adgangskode</label>
                <input id="user_password" type="password" name="user_password" placeholder="Ny adgangskode" required>

                <!-- Bekræft adgangskode -->
                <label for="confirm_password" class="hide_label">Bekræft adgangskode</label>
                <input id="confirm_password" type="password" name="confirm_password" placeholder="Bekræft adgangskode" required>

                <!-- Gem knap -->
                <button class="btn btn-primary" type="submit">GEM ADGANGSKODE</button>
            </form>
        </section>
    </section>
</main>
