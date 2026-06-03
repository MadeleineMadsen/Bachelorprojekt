<?php
$currentPage = $currentPage ?? '';

// Navigation links i footer
$FooterNavItems = [
    [
        'key' => 'events',
        'label' => 'EVENTS',
        'url' => '/events'
    ],
    [
        'key' => 'about',
        'label' => 'OM OS',
        'url' => '/about'
    ],
    [
        'key' => 'members',
        'label' => 'MEDLEMMER',
        'url' => '/members'
    ],
    [
        'key' => 'about',
        'label' => 'KONTAKT',
        'url' => '/about#contact'
    ],
];

// Adresseoplysninger
$address = [
    'GULDBERGSGADE 29N, 2200 KØBENHAVN N'
];

// Sociale medier med ikon og link
$socials = [
    [
        'name' => 'Instagram',
        'icon' => '/assets/img/icons/insta.svg',
        'url' => 'https://www.instagram.com/_ekdigital/'
    ],
    [
        'name' => 'Facebook',
        'icon' => '/assets/img/icons/face.svg',
        'url' => 'https://www.facebook.com'
    ],
];
?>

<!-- Footer -->
<footer class="site-footer">

    <!-- Logo -->
    <div class="img-container">
        <a href="/" class="footer-logo">
            <img src="/assets/img/icons/logo_footer_black.svg" alt="GBG Social Logo">
        </a>
    </div>

    <!-- Midtersektion med navigation og adresse -->
    <div class="footer-container">

        <!-- Footer navigation -->
        <div class="footer-inner">

            <!-- Loop gennem alle footer links -->
            <?php foreach ($FooterNavItems as $item): ?>
                <a href="<?= htmlspecialchars($item['url']) ?>"
                    class="bottom-nav-link <?= ($currentPage === $item['key']) ? 'active' : '' ?>">
                    <?= htmlspecialchars($item['label']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Adresse -->
        <div class="footer-address">

            <!-- Loop gennem adresselinjer -->
            <?php foreach ($address as $line): ?>
                <p class="bottom-address"><?= htmlspecialchars($line) ?></p>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sociale medier -->
    <div class="some-container">
        <p>FØLG OS</p>

        <div>
            <!-- Loop gennem sociale medier -->
            <?php foreach ($socials as $social): ?>
                <a href="<?= $social['url'] ?>">
                    <!-- Ikon for socialt medie -->
                    <img src="<?= $social['icon'] ?>" alt="<?= $social['name'] ?>">
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</footer>

<!-- JavaScript filer -->
<script src="/assets/js/app.js"></script>
<script src="/assets/js/utilities.js"></script>
<script src="/assets/js/calendar.js"></script>
<script src="/assets/js/profile.js"></script>
<script src="/assets/js/events.js"></script>
</body>

</html>