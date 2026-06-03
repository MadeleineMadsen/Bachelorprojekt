<?php
$event = $event ?? [];
$categories = $categories ?? [];

// Tjekker om formularen bruges til at redigere eller oprette et event
$isEditing = isset($event['event_pk']);
$formAction = $isEditing ? '/event_edit' : '/event_create';
?>

<!-- Opret/rediger event side -->
<main class="container create-event-page">

    <!-- Venstre billede -->
    <div class="create-event-image">
        <img src="/assets/img/left-layout-picture.webp" alt="Studerende til socialt event">
    </div>

    <!-- Formular sektion -->
    <section class="create-event-content">
        <h1 class="form-title"><?= $isEditing ? 'REDIGER EVENT' : 'OPRET EVENT' ?></h1>

        <section class="form-container create-event-container">
            
            <!-- Event formular -->
            <form id="eventForm" method="POST" action="<?= $formAction ?>" enctype="multipart/form-data"><?php csrf_input(); ?>
                
                <!-- Event id sendes med ved redigering -->
                <?php if ($isEditing): ?>
                    <input type="hidden" name="event_pk" value="<?= htmlspecialchars($event['event_pk']) ?>">
                <?php endif; ?>

                <!-- Tidligere uploadet billede bevares ved valideringsfejl -->
                <?php if (!$isEditing && old('saved_image')): ?>
                    <input type="hidden" name="saved_image" value="<?= e(old('saved_image')) ?>">
                <?php endif; ?>

                <!-- Basisinformation -->
                <label for="event_titel" class="hide_label">Titel på event</label>
                <input id="event_titel" type="text" name="titel" placeholder="Titel på event" required
                    value="<?= $isEditing ? htmlspecialchars($event['event_title']) : e(old('titel')) ?>">

                <label for="event_dato" class="hide_label">Dato på event</label>
                <input id="event_dato" type="date" name="date" required
                    min="<?= $isEditing ? '' : date('Y-m-d') ?>"
                    value="<?= $isEditing ? htmlspecialchars($event['event_date']) : e(old('date')) ?>">

                <!-- Tidspunkt -->
                <div class="form-row">
                    <div>
                        <small class="form-hint">Starttidspunkt</small>
                        <label for="event_start" class="hide_label">Starttid</label>
                        <input id="event_start" type="time" name="time" placeholder="Starttid" required
                            value="<?= $isEditing ? htmlspecialchars(substr($event['event_time'], 0, 5)) : e(old('time')) ?>">
                    </div>

                    <div>
                        <small class="form-hint">Sluttidspunkt</small>
                        <label for="event_slut" class="hide_label">Sluttid</label>
                        <input id="event_slut" type="time" name="end_time" placeholder="Sluttid" required
                            value="<?= $isEditing ? htmlspecialchars(substr($event['event_end_time'] ?? '', 0, 5)) : e(old('end_time')) ?>">
                    </div>
                </div>

                <!-- Lokation og undertitel -->
                <label for="event_lokation" class="hide_label">Lokation</label>
                <input id="event_lokation" type="text" name="location" placeholder="Lokation" required
                    value="<?= $isEditing ? htmlspecialchars($event['event_location']) : e(old('location')) ?>">

                <label for="event_subtitel" class="hide_label">Undertitel på event</label>
                <input id="event_subtitel" type="text" name="subtitel" placeholder="Undertitel" required
                    value="<?= $isEditing ? htmlspecialchars($event['event_subtitle'] ?? '') : e(old('subtitel')) ?>">

                <!-- Kategori -->
                <label for="category" class="hide_label">Kategori</label>
                <select id="category" name="category" required>
                    <option value="" disabled <?= !$isEditing && old('category') === '' ? 'selected' : '' ?>>Vælg kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['category_pk']) ?>"
                            <?= ($isEditing && $cat['category_pk'] === $event['category_fk']) || (!$isEditing && old('category') === $cat['category_pk']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Beskrivelse -->
                <label class="form-label" for="description">Beskrivelse</label>
                <textarea id="description" name="description" required><?= $isEditing ? htmlspecialchars($event['event_description']) : e(old('description')) ?></textarea>

                <!-- Beskrivende punkter -->
                <label class="form-label" for="description-bulletpoints">Beskrivende punkter</label>
                <small class="form-hint">Skriv ét punkt per linje, hver linje bliver et bullet point.</small>
                <textarea id="description-bulletpoints" name="description-bulletpoints" required><?= $isEditing ? htmlspecialchars($event['event_expectations'] ?? '') : e(old('description-bulletpoints')) ?></textarea>

                <!-- Hvorfor skal du deltage -->
                <label class="form-label" for="why-join">Hvorfor skal du deltage</label>
                <textarea id="why-join" name="why_join" required><?= $isEditing ? htmlspecialchars($event['event_why_join'] ?? '') : e(old('why_join')) ?></textarea>

                <!-- Billede upload -->
                <label class="form-label" for="profile_image">Upload billede<?= $isEditing ? ' <small class="form-hint">(valgfrit – behold nuværende hvis tomt)</small>' : '' ?></label>
                <label class="upload-box" for="profile_image">
                    <input id="profile_image" type="file" name="image" accept="image/*">
                    <div class="upload-icon">
                        <img src="/assets/img/icons/upload_picture.svg" alt="Upload billede ikon">
                    </div>
                    <?php
                        $hasSavedImage = !$isEditing && old('saved_image') !== '';
                        $hasEventImage = $isEditing && !empty($event['event_image']);
                    ?>
                    <p id="uploadText">
                        <?php if ($hasEventImage || $hasSavedImage): ?>
                            Der er allerede et billede – upload kun hvis du vil skifte det
                        <?php else: ?>
                            Træk og slip et billede her<br>eller klik for at vælge fil
                        <?php endif; ?>
                    </p>
                    <img
                        id="uploadPreview"
                        src="<?php
                            if ($hasEventImage) echo '/assets/img/' . htmlspecialchars($event['event_image']);
                            elseif ($hasSavedImage) echo '/assets/img/' . e(old('saved_image'));
                            else echo '';
                        ?>"
                        alt="Billede preview"
                        class="upload-preview"
                        <?= (!$hasEventImage && !$hasSavedImage) ? 'style="display:none"' : '' ?>
                    >
                </label>
                
                <!-- Fejlbesked til billede -->
                <p class="field-error" id="imageError" style="display:none;">
                    Du skal uploade et billede
                </p>

                <!-- Knapper -->
                <div class="button-row">
                    <button class="btn btn-primary" type="submit"><?= $isEditing ? 'GEM ÆNDRINGER' : 'OPRET EVENT' ?></button>
                    <?php if ($isEditing): ?>
                        <a href="/event_page?id=<?= htmlspecialchars($event['event_pk']) ?>" class="btn btn-secondary">ANNULLER</a>
                    <?php else: ?>
                        <a href="/events" class="btn btn-secondary">ANNULLER</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>
    </section>
</main>