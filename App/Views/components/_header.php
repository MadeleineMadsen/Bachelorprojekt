<?php

// Links til venstre side af topnavigationen
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

// "Bliv medlem" vises kun i topnavigationen for almindelige brugere
if ($isLoggedIn && $isUser) {
    $leftNavItems[] = [
        'key' => 'membership_apply',
        'label' => 'BLIV MEDLEM',
        'url' => '/membership_apply'
    ];
}

// Links til højre side af topnavigationen afhænger af loginstatus
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
    // Besøgende brugere får også vist "Bliv medlem"
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


// Antal afventende medlemsansøgninger
$pendingApplicationsCount = 0;

// Admin får vist antal afventende ansøgninger i navigationen
if ($isLoggedIn && $isAdmin) {
    $pendingApplicationsCount = count(MemberController::getPending());
}

// Sekundær navigation bruges på profilsider
$subNavItems = [];

// Subnavigation oprettes kun for brugere der er logget ind
if ($isLoggedIn) {
    if ($isAdmin) {

        // Subnavigation for admin
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

        // Subnavigation for almindelige brugere
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
    // Henter flash-beskeder fra sessionen
    $successMessage = flash('success');
    $errorMessage = flash('error');
    ?>

    <!-- Container til succes- og fejlbeskeder -->
    <div class="toast-container" aria-live="polite" aria-atomic="true">

        <!-- Succesbesked -->
        <?php if ($successMessage): ?>
            <div class="toast toast-success">
                <?= e($successMessage) ?>

                <button type="button" class="toast-close" aria-label="Luk besked">×</button>
            </div>
        <?php endif; ?>

        <!-- Fejlbesked -->
        <?php if ($errorMessage): ?>
            <div class="toast toast-error" role="alert">
                <?= e($errorMessage) ?>

                <button type="button" class="toast-close" aria-label="Luk besked">×</button>
            </div>
        <?php endif; ?>

    </div>

    <!-- Header med navigation -->
    <header class="site-header">
        <div class="header-inner">

            <!-- Burgermenu til mobil -->
            <button class="burger" id="burgerBtn" aria-label="Åbn menu" aria-expanded="false" aria-controls="mobileMenu"
                type="button">

                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Venstre del af headeren med logo og navigation -->
            <div class="header-left">
                <a href="/" class="logo">
                    <img src="/assets/img/icons/logo_header.png" alt="GBG Social Logo">
                </a>

                <!-- Venstre topnavigation -->
                <nav class="top-nav top-nav-left" aria-label="Hovednavigation venstre">
                    <?php foreach ($leftNavItems as $item): ?>
                        <a href="<?= htmlspecialchars($item['url']) ?>"
                            class="top-nav-link top-nav-link-left <?= ($currentPage === $item['key']) ? 'active' : '' ?>">
                            <?= htmlspecialchars($item['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>

            <!-- Højre topnavigation -->
            <nav class="top-nav top-nav-right" aria-label="Hovednavigation højre">
                <?php foreach ($rightNavItems as $item): ?>
                    <a href="<?= htmlspecialchars($item['url']) ?>"
                        class="top-nav-link top-nav-link-right <?= ($currentPage === $item['key']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($item['label']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>

        <!-- Subnavigation på desktop -->
        <?php if ($isLoggedIn && !empty($subNavItems) && $isProfileSection): ?>
            <nav class="sub-nav" aria-label="Sekundær navigation">
                <div class="sub-nav-inner">

                    <!-- Loop gennem links i subnavigationen -->
                    <?php foreach ($subNavItems as $item): ?>
                        <a href="<?= htmlspecialchars($item['url']) ?>"
                            class="sub-nav-link <?= ($currentPage === $item['key']) ? 'active' : '' ?>">
                            <?= htmlspecialchars($item['label']) ?>

                            <!-- Badge vises fx ved afventende ansøgninger -->
                            <?php if (!empty($item['badge'])): ?>
                                <span class="sub-nav-badge"><?= htmlspecialchars($item['badge']) ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </nav>
        <?php endif; ?>

        <!-- Mobil navigation -->
        <div class="mobile-nav" id="mobileMenu">
            <nav class="mobile-nav-group" aria-label="Mobil hovednavigation">

                <!-- Primære links i mobilmenuen -->
                <div class="mobile-nav-main">
                    <?php foreach ($leftNavItems as $item): ?>
                        <a href="<?= htmlspecialchars($item['url']) ?>" class="mobile-nav-link">
                            <?= htmlspecialchars($item['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Konto-links i mobilmenuen -->
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

    <!-- Mobil subnavigation i bunden -->
    <?php if ($isLoggedIn && !empty($subNavItems) && $isProfileSection): ?>
        <nav class="mobile-bottom-nav" aria-label="Mobil subnavigation">

            <!-- Loop gennem links i mobil subnavigationen -->
            <?php foreach ($subNavItems as $item): ?>
                <a href="<?= htmlspecialchars($item['url']) ?>"
                    class="mobile-bottom-link <?= ($currentPage === $item['key']) ? 'active' : '' ?>">

                    <!-- Aktivt ikon vises på den aktuelle side -->
                    <img src="<?= ($currentPage === $item['key']) ? $item['icon_active'] : $item['icon']; ?>"
                        alt="<?= htmlspecialchars($item['label']) ?>" class="mobile-bottom-icon">

                    <!-- Badge vises fx ved afventende ansøgninger -->
                    <?php if (!empty($item['badge'])): ?>
                        <span class="mobile-nav-badge"><?= htmlspecialchars($item['badge']) ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
    <?php endif; ?>