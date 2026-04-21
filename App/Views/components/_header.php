<?php

// Topnavigation
if ($isLoggedIn) {
    $topNavItems = [
        [
            'key'   => 'logout',
            'label' => 'Log ud',
            'url'   => '/logout'
        ],
    ];
} else {
    $topNavItems = [
        [
            'key'   => 'login',
            'label' => 'Log ind',
            'url'   => '/login'
        ],
        [
            'key'   => 'signup',
            'label' => 'Opret dig',
            'url'   => '/signup'
        ],
    ];
}

// Subnavigation
$subNavItems = [];

if ($isLoggedIn) {
    if ($isAdmin) {
        $subNavItems = [
            [
                'key'           => 'profil',
                'label'         => 'Profil',
                'url'           => '/profil',
                'icon'          => '/assets/img/profile.png',
                'icon_active'   => '/assets/img/hover_profile.png'
            ],
            [
                'key'           => 'events',
                'label'         => 'Opret event',
                'url'           => '/event_opret',
                'icon'          => '/assets/img/add_event.png',
                'icon_active'   => '/assets/img/hover_add_event.png'
            ],
            [
                'key'           => 'medlemsskab',
                'label'         => 'Medlemmer',
                'url'           => '/medlemsskab',
                'icon'          => '/assets/img/add_member.png',
                'icon_active'   => '/assets/img/hover_add_member.png'
            ],
            [
                'key'           => 'kalender',
                'label'         => 'Kalender',
                'url'           => '/kalender',
                'icon'          => '/assets/img/calender.png',
                'icon_active'   => '/assets/img/hover_calender.png'
            ],
        ];
    } else {
        $subNavItems = [
            [
                'key'           => 'profil',
                'label'         => 'Profil',
                'url'           => '/profil',
                'icon'          => '/assets/img/profile.png',
                'icon_active'   => '/assets/img/hover_profile.png'
            ],
            [
                'key'           => 'events',
                'label'         => 'Se events',
                'url'           => '/events',
                'icon'          => '/assets/img/events.png',
                'icon_active'   => '/assets/img/hover_events.png'
            ],
            [
                'key'           => 'kalender',
                'label'         => 'Kalender',
                'url'           => '/kalender',
                'icon'          => '/assets/img/calender.png',
                'icon_active'   => '/assets/img/hover_calender.png'
            ],
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>GBG Social</title>
</head>
<body>

<header class="site-header">
    <div class="header-inner">

        <!-- Burgermenu lukket -->
        <button
            class="burger"
            id="burgerBtn"
            aria-label="Åbn menu"
            aria-expanded="false"
            aria-controls="mobileMenu"
            type="button"
            >
            
            <span></span>
            <span></span>
            <span></span>
        </button>

        <a href="/" class="logo">
            <img src="/assets/img/test.png" alt="Logo">

        </a>

        <!-- Topnavigation -->
        <nav class="top-nav" aria-label="Hovednavigation">
            <?php foreach ($topNavItems as $item): ?>
                <a 
                    href="<?= htmlspecialchars($item['url']) ?>"
                    class="top-nav-link <?=  ($currentPage === $item['key']) ? 'active' : '' ?>"
                    >
                    <?= htmlspecialchars($item['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>


        <!-- Subnavigation -->

        <?php if ($isLoggedIn && !empty($subNavItems)): ?>
            <nav class="sub-nav" aria-label="Sekundær navigation">
                <div class="sub-nav-inner">
                    <?php foreach ($subNavItems as $item): ?>
                        <a
                            href="<?= htmlspecialchars($item['url']) ?>"
                            class="sub-nav-link <?= ($currentPage === $item['key']) ? 'active' : '' ?>"
                            >
                            <?= htmlspecialchars($item['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </nav>
        <?php endif; ?>

        <!-- Topnavigation - Mobil -->
        <div class="mobile-nav" id="mobileMenu">
            <nav class="mobile-nav-group" aria-label="Mobil hovednavigation">
                <?php foreach ($topNavItems as $item): ?>
                    <a
                        href="<?= htmlspecialchars($item['url']) ?>"
                        class="mobile-nav-link"
                        >
                        <?= htmlspecialchars($item['label']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
</header>

<!-- Subnavigation - Mobil -->
<?php if ($isLoggedIn && !empty($subNavItems)): ?>
    <nav class="mobile-bottom-nav" aria-label="Mobil subnavigation">
        <?php foreach ($subNavItems as $item): ?>
            <a
                href="<?= htmlspecialchars($item['url']) ?>"
                class="mobile-bottom-link <?= ($currentPage === $item['key']) ? 'active' : '' ?>"
                >
                <img
                    src="<?= ($currentPage === $item['key']) ? $item['icon_active'] : $item['icon']; ?>"
                    alt="<?= htmlspecialchars($item['label']) ?>"
                    class="mobile-bottom-icon"
                >            
            </a>
        <?php endforeach; ?>
    </nav>
<?php endif; ?>