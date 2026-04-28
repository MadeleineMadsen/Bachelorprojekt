<?php

?>

<main class="container">
    <h1>OPRET DIG</h1>

    <section class="signup-container">
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
            <p>Har du allerede en bruger? <a href="log_ind">Log ind her</a> </p>
        </div>
    </section>
</main>

<style>
    h1 {
        font-family: var(--font-header);
        font-size: var(--font-mobil-h1);
        width: 80%;
    }

    .signup-container {
        width: 300px;
        margin: 30px auto;
    }

    input {
        font-family: var(--font-text);
        font-size: var(--font-mobil-text);
        color: var(--font-color-black);
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

    .checkbox-wrapper {
        display: flex;
        align-items: flex-start;
        gap: var(--gap-sm);
        margin-bottom: var(--gap-sm);
    }

    .checkbox-wrapper input[type="checkbox"] {
        width: 24px;
        height: 24px;
        margin-top: 3px;
        flex-shrink: 0;
        accent-color: var(--color-light-blue);
    }

    span {
        font-family: var(--font-text);
        font-size: var(--font-mobil-text);
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
            width: 80%;
        }

        .signup-container {
            width: 720px;
            margin: 70px auto;
        }

        input {
            font-family: var(--font-text);
            font-size: var(--font-desktop-text-small);
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

        .checkbox-wrapper {
            display: flex;
            align-items: flex-start;
            gap: var(--gap-md);
            margin-bottom: var(--gap-md);
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 34px;
            height: 34px;
            margin-top: 6px;
            flex-shrink: 0;
            accent-color: var(--color-light-blue);
        }

        span {
            font-size: var(--font-desktop-text-small);
            color: var(--font-color-black);
        }

        p {
            font-family: var(--font-text);
            font-size: var(--font-desktop-text-small);
            font-weight: var(--poppins-font-bold);
            color: var(--font-color-black);
            margin-top: var(--gap-md);

            a {
                color: var(--color-light-blue);
            }
        }
    }
</style>