<?php

?>

<main class="container">

    <section class="membership-container">
        <form method="POST">
            <div class="form-row">
                <input type="text" name="name" placeholder="Fornavn" required>

                <input type="text" name="last_name" placeholder="Efternavn" required>
            </div>

            <input type="text" name="last_name" placeholder="Studieretning" required>

            <input type="text" name="last_name" placeholder="Semester" required>

            <input type="email" name="email" placeholder="Studiemail" required>

            <label class="form-label" for="description">Hvorfor vil du være medlem? Og hvilke af de kommende aktiviteter
                vil du have ansvaret for?</label>
            <textarea id="description" name="description" required></textarea>

            <button class="btn btn-primary" type="submit">SEND ANSØGNING</button>
        </form>
    </section>
</main>

<style>
    .membership-container {
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--gap-sm);
    }

    .form-label {
        display: block;
        font-family: var(--font-text);
        font-size: var(--font-mobil-text);
        margin-bottom: var(--gap-sm);
    }

    textarea {
        width: 100%;
        height: 170px;
        resize: none;
        border: 1px solid var(--font-color-black);
        margin-bottom: var(--gap-sm);
        font-family: var(--font-text);
        font-size: var(--font-mobil-text);
        padding: var(--gap-sm);
        outline: none;
    }

    @media (min-width: 768px) {

        .membership-container {
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--gap-md);
        }

        .form-label {
            display: block;
            font-family: var(--font-text);
            font-size: var(--font-desktop-text-small);
            margin-bottom: var(--gap-sm);
        }

        textarea {
            width: 100%;
            height: 200px;
            resize: none;
            border: 1px solid var(--font-color-black);
            margin-bottom: var(--gap-md);
            font-family: var(--font-text);
            font-size: var(--font-desktop-text-small);
            padding: var(--gap-sm);
            outline: none;
        }

    }
</style>