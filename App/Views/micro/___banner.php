<?php

// Bannertekst fallback hvis ingen tekst er sendt med
$bannerText = trim($bannerText ?? 'GBG Social');

// Finder længden på teksten
$textLength = mb_strlen($bannerText);

// Beregner hvor mange gange teksten skal gentages
// så banneret altid fylder hele bredden
$repeat = max(12, ceil(120 / max($textLength, 1)));
?>

<!-- Rullende tekstbanner -->
<section class="text-banner">

    <!-- Track bruges til animationen -->
    <div class="text-banner-track">

        <!-- To grupper bruges for at skabe et uendeligt scroll-loop -->
        <?php for ($group = 0; $group < 2; $group++): ?>
            <div class="text-banner-group">

                <!-- Bannerteksten gentages flere gange -->
                <?php for ($i = 0; $i < $repeat; $i++): ?>
                    <span><?= htmlspecialchars($bannerText); ?></span>
                <?php endfor; ?>
            </div>
        <?php endfor; ?>
    </div>
</section>