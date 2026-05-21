<?php

// Topnavigation
$leftNavItems = [
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
        'key' => 'medlemmer',
        'label' => 'MEDLEMMER',
        'url' => '/members'
    ],
];

// Kun almindelige users skal se "BLIV MEDLEM"
if ($isLoggedIn && $isUser) {
    $leftNavItems[] = [
        'key' => 'membership_apply',
        'label' => 'BLIV MEDLEM',
        'url' => '/membership_apply'
    ];
}

if ($isLoggedIn) {
    $rightNavItems = [
        [
            'key' => 'profile',
            'label' => 'PROFIL',
            'url' => '/profile'
        ],
        [
            'key' => 'logout',
            'label' => 'LOG UD',
            'url' => '/logout'
        ],
    ];

} else {
    $leftNavItems[] = [
        'key' => 'membership_apply',
        'label' => 'BLIV MEDLEM',
        'url' => '/membership_apply'
    ];

    $rightNavItems = [
        [
            'key' => 'login',
            'label' => 'LOG IND',
            'url' => '/login'
        ],
        [
            'key' => 'signup',
            'label' => 'OPRET DIG',
            'url' => '/signup'
        ],
    ];
}

$pendingApplicationsCount = 0;

if ($isLoggedIn && $isAdmin) {
    $pendingApplicationsCount = count(MemberController::getPending());
}

// Subnavigation
$subNavItems = [];

if ($isLoggedIn) {
    if ($isAdmin) {

        $subNavItems = [
            [
                'key' => 'profile',
                'label' => 'PROFIL',
                'url' => '/profile',
                'icon' => '/assets/img/icons/profile.svg',
                'icon_active' => '/assets/img/icons/hover_profile.svg'
            ],
            [
                'key' => 'event_create',
                'label' => 'OPRET EVENT',
                'url' => '/event_create',
                'icon' => '/assets/img/icons/add_event.png',
                'icon_active' => '/assets/img/icons/hover_add_event.png'
            ],
            [
                'key' => 'membership_approve',
                'label' => 'ANSØGNINGER',
                'url' => '/membership_approve',
                'icon' => '/assets/img/icons/add_member.png',
                'icon_active' => '/assets/img/icons/hover_add_member.png',
                'badge' => $pendingApplicationsCount
            ],
            [
                'key' => 'calendar',
                'label' => 'KALENDER',
                'url' => '/calendar',
                'icon' => '/assets/img/icons/calender.png',
                'icon_active' => '/assets/img/icons/hover_calender.png'
            ],
        ];

    } else {

        $subNavItems = [
            [
                'key' => 'profile',
                'label' => 'PROFIL',
                'url' => '/profile',
                'icon' => '/assets/img/icons/profile.png',
                'icon_active' => '/assets/img/icons/hover_profile.png'
            ],
            [
                'key' => 'my_events',
                'label' => 'MINE EVENTS',
                'url' => '/my_events',
                'icon' => '/assets/img/icons/events.png',
                'icon_active' => '/assets/img/icons/hover_events.png'
            ],
            [
                'key' => 'calendar',
                'label' => 'KALENDER',
                'url' => '/calendar',
                'icon' => '/assets/img/icons/calender.png',
                'icon_active' => '/assets/img/icons/hover_calender.png'
            ],
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="da">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="image/png" href="/assets/img/icons/logo_header.png">
    <title>GBG Social</title>
</head>

<body>

    <?php
    $successMessage = flash('success');
    $errorMessage = flash('error');
    ?>

    <div class="toast-container" aria-live="polite" aria-atomic="true">

        <?php if ($successMessage): ?>
            <div class="toast toast-success">
                <?= e($successMessage) ?>
                <button type="button" class="toast-close" aria-label="Luk besked">×</button>
            </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="toast toast-error" role="alert">
                <?= e($errorMessage) ?>
                <button type="button" class="toast-close" aria-label="Luk besked">×</button>
            </div>
        <?php endif; ?>

    </div>

    <header class="site-header">
        <div class="header-inner">

            <!-- Burgermenu lukket -->
            <button class="burger" id="burgerBtn" aria-label="Åbn menu" aria-expanded="false" aria-controls="mobileMenu"
                type="button">

                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Topnavigation -->
            <div class="header-left">
                <a href="/" class="logo">
                    <img src="/assets/img/icons/logo_header.png" alt="GBG Social Logo">
                </a>

                <nav class="top-nav top-nav-left" aria-label="Hovednavigation venstre">
                    <?php foreach ($leftNavItems as $item): ?>
                        <a href="<?= htmlspecialchars($item['url']) ?>"
                            class="top-nav-link top-nav-link-left <?= ($currentPage === $item['key']) ? 'active' : '' ?>">
                            <?= htmlspecialchars($item['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>

            <nav class="top-nav top-nav-right" aria-label="Hovednavigation højre">
                <?php foreach ($rightNavItems as $item): ?>
                    <a href="<?= htmlspecialchars($item['url']) ?>"
                        class="top-nav-link top-nav-link-right <?= ($currentPage === $item['key']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($item['label']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>

        <!-- Subnavigation -->
        <?php if ($isLoggedIn && !empty($subNavItems) && $isProfileSection): ?>
            <nav class="sub-nav" aria-label="Sekundær navigation">
                <div class="sub-nav-inner">
                    <?php foreach ($subNavItems as $item): ?>
                        <a href="<?= htmlspecialchars($item['url']) ?>"
                            class="sub-nav-link <?= ($currentPage === $item['key']) ? 'active' : '' ?>">
                            <?= htmlspecialchars($item['label']) ?>

                            <?php if (!empty($item['badge'])): ?>
                                <span class="sub-nav-badge"><?= htmlspecialchars($item['badge']) ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </nav>
        <?php endif; ?>

        <!-- Topnavigation - Mobil -->
        <div class="mobile-nav" id="mobileMenu">
            <nav class="mobile-nav-group" aria-label="Mobil hovednavigation">

                <div class="mobile-nav-main">
                    <?php foreach ($leftNavItems as $item): ?>
                        <a href="<?= htmlspecialchars($item['url']) ?>" class="mobile-nav-link">
                            <?= htmlspecialchars($item['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="mobile-nav-account">
                    <?php foreach ($rightNavItems as $item): ?>
                        <a href="<?= htmlspecialchars($item['url']) ?>" class="mobile-nav-link">
                            <?= htmlspecialchars($item['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>

            </nav>
        </div>
    </header>


    <!-- Subnavigation - Mobil -->
    <?php if ($isLoggedIn && !empty($subNavItems) && $isProfileSection): ?>
        <nav class="mobile-bottom-nav" aria-label="Mobil subnavigation">
            <?php foreach ($subNavItems as $item): ?>
                <a href="<?= htmlspecialchars($item['url']) ?>"
                    class="mobile-bottom-link <?= ($currentPage === $item['key']) ? 'active' : '' ?>">
                    <img src="<?= ($currentPage === $item['key']) ? $item['icon_active'] : $item['icon']; ?>"
                        alt="<?= htmlspecialchars($item['label']) ?>" class="mobile-bottom-icon">
                    <?php if (!empty($item['badge'])): ?>
                        <span class="mobile-nav-badge"><?= htmlspecialchars($item['badge']) ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
    <?php endif; ?>