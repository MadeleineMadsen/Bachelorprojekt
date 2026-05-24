<dialog class="modal-overlay" id="<?= htmlspecialchars($modalId) ?>">
    <div class="modal-box">
        <h3><?= htmlspecialchars($title) ?></h3>

        <p><?= htmlspecialchars($text) ?></p>

        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" data-modal-close>
                Annuller
            </button>

            <button type="submit" form="<?= htmlspecialchars($formId) ?>" class="btn btn-delete">
                <?= htmlspecialchars($confirmText) ?>
            </button>
        </div>
    </div>
</dialog>