<?php

?>

<main class="container create-event-page">

    <div class="create-event-image">
        <img src="/assets/img/left-layout-picture.webp" alt="Studerende til socialt event">
    </div>

    <div class="create-event-content">
        <h1 class="form-title">OPRET EVENT</h1>

        <section class="form-container create-event-container">
            <form method="POST">
                <input type="text" name="titel" placeholder="Titel på event" required>

                <div class="form-row">
                    <input type="date" name="date" placeholder="Dato" required>

                    <input type="time" name="time" placeholder="Tid" required>
                </div>

                <input type="text" name="location" placeholder="Lokation" required>

                <label class="form-label" for="description">Beskrivelse</label>
                <textarea id="description" name="description" required></textarea>

                <label class="form-label">Upload billede</label>
                <div class="upload-box">
                    <input type="file" name="image" accept="image/*">
                    <div class="upload-icon"><img src="/assets/img/upload_picture.png" alt="Upload billede ikon"></div>
                    <p>Træk og slip et billede her<br>eller klik for at vælge fil</p>
                </div>

                <div class="button-row">
                    <button class="btn btn-primary" type="submit">OPRET EVENT</button>

                    <button class="btn btn-secondary" type="reset">ANNULLER</button>
                </div>
            </form>
        </section>
    </div>
</main>