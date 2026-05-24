<?php

// Footer navigation
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

// Adresse
$address = [
    'GULDBERGSGADE 29N, 2200 KØBENHAVN N'
];

// Sociale medier
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

<footer class="site-footer">
    <div class="img-container">
        <a href="/" class="footer-logo">
            <img src="/assets/img/icons/logo_footer_black.png" alt="GBG Social Logo">
        </a>
    </div>

    <div class="footer-container">
        <div class="footer-inner">
            <?php foreach ($FooterNavItems as $item): ?>
                <a href="<?= htmlspecialchars($item['url']) ?>"
                    class="bottom-nav-link <?= ($currentPage === $item['key']) ? 'active' : '' ?>">
                    <?= htmlspecialchars($item['label']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="footer-address">
            <?php foreach ($address as $line): ?>
                <p class="bottom-address"><?= htmlspecialchars($line) ?></p>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="some-container">
        <p>FØLG OS</p>

        <div>
            <?php foreach ($socials as $social): ?>
                <a href="<?= $social['url'] ?>">
                    <img src="<?= $social['icon'] ?>" alt="<?= $social['name'] ?>">
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</footer>

<script src="/assets/js/app.js"></script>
<script src="/assets/js/utilities.js"></script>
<script src="/assets/js/calendar.js"></script>
<script src="/assets/js/profile.js"></script>
<script src="/assets/js/events.js"></script>
</body>

</html>