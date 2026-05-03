<?php
$bannerText = trim($bannerText ?? 'GBG Social');

$textLength = mb_strlen($bannerText);
$repeat = max(12, ceil(120 / max($textLength, 1)));
?>

<div class="text-banner">
    <div class="text-banner-track">
        <?php for ($group = 0; $group < 2; $group++): ?>
            <div class="text-banner-group">
                <?php for ($i = 0; $i < $repeat; $i++): ?>
                    <span><?= htmlspecialchars($bannerText); ?></span>
                <?php endfor; ?>
            </div>
        <?php endfor; ?>
    </div>
</div>