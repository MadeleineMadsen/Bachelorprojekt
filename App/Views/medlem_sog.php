<?php

?>

<main class="container">

    <section class="form-container membership-container">
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