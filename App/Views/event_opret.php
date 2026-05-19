<?php
$isEditing = isset($event);
$formAction = $isEditing ? '/event_rediger' : '';
?>

<main class="container create-event-page">

    <div class="create-event-image">
        <img src="/assets/img/left-layout-picture.webp" alt="Studerende til socialt event">
    </div>

    <div class="create-event-content">
        <h1 class="form-title"><?= $isEditing ? 'REDIGER EVENT' : 'OPRET EVENT' ?></h1>

        <section class="form-container create-event-container">
            <form method="POST" action="<?= $formAction ?>" enctype="multipart/form-data"><?php csrf_input(); ?>
                <?php if ($isEditing): ?>
                    <input type="hidden" name="event_pk" value="<?= htmlspecialchars($event['event_pk']) ?>">
                <?php endif; ?>

                <input type="text" name="titel" placeholder="Titel på event" required
                    value="<?= $isEditing ? htmlspecialchars($event['event_title']) : '' ?>">

                <input type="date" name="date" required
                    value="<?= $isEditing ? htmlspecialchars($event['event_date']) : '' ?>">

                <div class="form-row">
                    <input type="time" name="time" placeholder="Starttid" required
                        value="<?= $isEditing ? htmlspecialchars(substr($event['event_time'], 0, 5)) : '' ?>">

                    <input type="time" name="end_time" placeholder="Sluttid"
                        value="<?= $isEditing ? htmlspecialchars(substr($event['event_end_time'] ?? '', 0, 5)) : '' ?>">
                </div>

                <input type="text" name="location" placeholder="Lokation" required
                    value="<?= $isEditing ? htmlspecialchars($event['event_location']) : '' ?>">

                <input type="text" name="subtitel" placeholder="Undertitel"
                    value="<?= $isEditing ? htmlspecialchars($event['event_subtitle'] ?? '') : '' ?>">

                <select name="category" required>
                    <option value="" disabled <?= !$isEditing ? 'selected' : '' ?>>Vælg kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['category_pk']) ?>"
                            <?= $isEditing && $cat['category_pk'] === $event['category_fk'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="form-label" for="description">Beskrivelse</label>
                <textarea id="description" name="description" required><?= $isEditing ? htmlspecialchars($event['event_description']) : '' ?></textarea>

                <label class="form-label" for="description-bulletpoints">Beskrivende punkter</label>
                <small class="form-hint">Skriv ét punkt per linje, hver linje bliver et bullet point.</small>
                <textarea id="description-bulletpoints" name="description-bulletpoints"><?= $isEditing ? htmlspecialchars($event['event_expectations'] ?? '') : '' ?></textarea>

                <label class="form-label">Upload billede<?= $isEditing ? ' <small class="form-hint">(valgfrit – behold nuværende hvis tomt)</small>' : '' ?></label>
                <label class="upload-box" for="profile_image">
                    <input id="profile_image" type="file" name="image" accept="image/*" <?= !$isEditing ? 'required' : '' ?>>
                    <div class="upload-icon">
                        <img src="/assets/img/icons/upload_picture.png" alt="Upload billede ikon">
                    </div>
                    <p id="uploadText">
                        <?php if ($isEditing && !empty($event['event_image'])): ?>
                            Der er allerede et billede – upload kun hvis du vil skifte det
                        <?php else: ?>
                            Træk og slip et billede her<br>eller klik for at vælge fil
                        <?php endif; ?>
                    </p>
                    <img
                        id="uploadPreview"
                        src="<?= $isEditing && !empty($event['event_image']) ? '' . htmlspecialchars($event['event_image']) : '' ?>"
                        alt="Billede preview"
                        class="upload-preview"
                        <?= (!$isEditing || empty($event['event_image'])) ? 'style="display:none"' : '' ?>
                    >
                </label>

                <div class="button-row">
                    <button class="btn btn-primary" type="submit"><?= $isEditing ? 'GEM ÆNDRINGER' : 'OPRET EVENT' ?></button>
                    <?php if ($isEditing): ?>
                        <a href="/eventside?id=<?= htmlspecialchars($event['event_pk']) ?>" class="btn btn-secondary">ANNULLER</a>
                    <?php else: ?>
                        <a href="/events" class="btn btn-secondary">ANNULLER</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>
    </div>
</main>