<?php

// Topnavigation
$leftNavItems = [
    [
        'key'   => 'events',
        'label' => 'EVENTS',
        'url'   => '/events'
    ],
    [
        'key'   => 'om',
        'label' => 'OM OS',
        'url'   => '/om'
    ],
    [
        'key'   => 'medlemmer',
        'label' => 'MEDLEMMER',
        'url'   => '/medlemmer'
    ],
];

// Kun almindelige users skal se "BLIV MEDLEM"
    if ($isLoggedIn && $isUser) {
    $leftNavItems[] = [
        'key' => 'medlem_sog',
        'label' => 'BLIV MEDLEM',
        'url' => '/medlem_sog'
    ];
}
    if ($isLoggedIn) {
        $rightNavItems = [
            [
                'key'   => 'profil',
                'label' => 'PROFIL',
                'url'   => '/profil'
            ],
            [
                'key'   => 'log_ud',
                'label' => 'LOG UD',
                'url'   => '/log_ud'
            ],
        ];
} else {
    $leftNavItems[] = [
        'key'   => 'medlem_sog',
        'label' => 'BLIV MEDLEM',
        'url'   => '/medlem_sog'
    ];

    $rightNavItems = [
        [
            'key'   => 'log_ind',
            'label' => 'LOG IND',
            'url'   => '/log_ind'
        ],
        [
            'key'   => 'opret_dig',
            'label' => 'OPRET DIG',
            'url'   => '/opret_dig'
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
                'label'         => 'PROFIL',
                'url'           => '/profil',
                'icon'          => '/assets/img/icons/profile.png',
                'icon_active'   => '/assets/img/icons/hover_profile.png'
            ],
            [
                'key'           => 'event_opret',
                'label'         => 'OPRET EVENT',
                'url'           => '/event_opret',
                'icon'          => '/assets/img/icons/add_event.png',
                'icon_active'   => '/assets/img/icons/hover_add_event.png'
            ],
            [
                'key'           => 'medlem_godkend',
                'label'         => 'ANSØGNINGER',
                'url'           => '/medlem_godkend',
                'icon'          => '/assets/img/icons/add_member.png',
                'icon_active'   => '/assets/img/icons/hover_add_member.png'
            ],
            [
                'key'           => 'kalender',
                'label'         => 'KALENDER',
                'url'           => '/kalender',
                'icon'          => '/assets/img/icons/calender.png',
                'icon_active'   => '/assets/img/icons/hover_calender.png'
            ],
        ];
    } else {
        $subNavItems = [
            [
                'key'           => 'profil',
                'label'         => 'PROFIL',
                'url'           => '/profil',
                'icon'          => '/assets/img/icons/profile.png',
                'icon_active'   => '/assets/img/icons/hover_profile.png'
            ],
            [
                'key'           => 'event_user',
                'label'         => 'MINE EVENTS',
                'url'           => '/event_user',
                'icon'          => '/assets/img/icons/events.png',
                'icon_active'   => '/assets/img/icons/hover_events.png'
            ],
            [
                'key'           => 'kalender',
                'label'         => 'KALENDER',
                'url'           => '/kalender',
                'icon'          => '/assets/img/icons/calender.png',
                'icon_active'   => '/assets/img/icons/hover_calender.png'
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
        
        <!-- Topnavigation -->
        <div class="header-left">
            <a href="/" class="logo">
                <img src="/assets/img/icons/logo_header.png" alt="Logo">
            </a>

            <nav class="top-nav top-nav-left" aria-label="Hovednavigation venstre">
                <?php foreach ($leftNavItems as $item): ?>
                    <a 
                        href="<?= htmlspecialchars($item['url']) ?>"
                        class="top-nav-link top-nav-link-left <?= ($currentPage === $item['key']) ? 'active' : '' ?>"
                    >
                        <?= htmlspecialchars($item['label']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>

        <nav class="top-nav top-nav-right" aria-label="Hovednavigation højre">
            <?php foreach ($rightNavItems as $item): ?>
                <a 
                    href="<?= htmlspecialchars($item['url']) ?>"
                    class="top-nav-link top-nav-link-right <?= ($currentPage === $item['key']) ? 'active' : '' ?>"
                >
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

                <div class="mobile-nav-main">
                    <?php foreach ($leftNavItems as $item): ?>
                        <a
                            href="<?= htmlspecialchars($item['url']) ?>"
                            class="mobile-nav-link"
                        >
                            <?= htmlspecialchars($item['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="mobile-nav-account">
                    <?php foreach ($rightNavItems as $item): ?>
                        <a
                            href="<?= htmlspecialchars($item['url']) ?>"
                            class="mobile-nav-link"
                        >
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