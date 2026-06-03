<?php
$modalId = $modalId ?? '';
$formId = $formId ?? '';
$text = $text ?? '';
?>

<!-- Bekræftelsesmodal -->
<dialog class="modal-overlay" id="<?= htmlspecialchars($modalId) ?>">

    <!-- Modal indhold -->
    <div class="modal-box">

        <!-- Titel -->
        <h3><?= htmlspecialchars($title) ?></h3>

        <!-- Beskrivende tekst -->
        <p><?= htmlspecialchars($text) ?></p>

        <!-- Lukker modal uden handling -->
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" data-modal-close>
                Annuller
            </button>

            <!-- Bekræfter handling og sender formular -->
            <button type="submit" form="<?= htmlspecialchars($formId) ?>" class="btn btn-delete">
                <?= htmlspecialchars($confirmText) ?>
            </button>
        </div>
    </div>
</dialog>