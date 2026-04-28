<?php

?>

<main class="container">
    <h1>LOG IND</h1>

    <section class="login-container">
        <form method="POST">
            <input type="email" name="email" placeholder="Studiemail" required>

            <input type="password" name="password" placeholder="Adgangskode" required>

            <button class="btn btn-primary" type="submit">LOG IND</button>
        </form>

        <div>
            <p>Ikke medlem endnu? <a href="opret_dig">Opret dig her</a> </p>
        </div>
    </section>
</main>

<style>
    h1 {
        font-family: var(--font-header);
        font-size: var(--font-mobil-h1);
    }

    .login-container {
        width: 300px;
        margin: 30px auto;
    }

    input {
        font-family: var(--font-text);
        font-size: var(--font-mobil-text);
        width: 100%;
        border: none;
        border-bottom: 1px solid var(--font-color-black);
        padding: var(--gap-sm) 0 var(--gap-sm);
        margin-bottom: var(--gap-sm);
        outline: none;
    }

    input::placeholder {
        color: var(--font-color-black);
    }

    p {
        font-family: var(--font-text);
        font-size: var(--font-mobil-text);
        font-weight: var(--poppins-font-bold);
        margin-top: var(--gap-sm);
        color: var(--font-color-black);

        a {
            color: var(--color-light-blue);
        }
    }

    @media (min-width: 768px) {
        h1 {
            font-family: var(--font-header);
            font-size: var(--font-desktop-h1);
        }

        .login-container {
            width: 720px;
            margin: 70px auto;
        }

        input {
            font-family: var(--font-text);
            font-size: var(--font-desktop-text-big);
            color: var(--font-color-black);
            width: 100%;
            border: none;
            border-bottom: 2px solid var(--font-color-black);
            padding: var(--gap-sm) 0 var(--gap-sm);
            margin-bottom: var(--gap-md);
            outline: none;
        }

        input::placeholder {
            color: var(--font-color-black);
        }

        p {
            font-family: var(--font-text);
            font-size: var(--font-desktop-text-big);
            font-weight: var(--poppins-font-bold);
            margin-top: var(--gap-md);

            a {
                color: var(--color-light-blue);
            }
        }
    }
</style>