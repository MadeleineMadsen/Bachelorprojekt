<?php
session_start();

// TIL TEST AF NAV - SKAL FJERNES NÅR LOGIN/SIGNUP VIRKER
$_SESSION['user'] = [
    'id' => 1,
    'name' => 'Test User',
    'role' => 'admin'
];



// Henter kun path fra URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Auth status (i header)
$isLoggedIn = isset($_SESSION['user']);
$isAdmin = $isLoggedIn && ($_SESSION['user']['role'] ?? '') === 'admin';

// Standard værdier
$currentPage = '';
$view = null;

// Router
switch ($uri) {
    
    // LANDING / FORSIDE
    case '/':
        $currentPage    = '';
        $view           = '/landing.php';
        break;

        // LOGIN / SIGNUP
    case '/login':
        $currentPage    = 'login';
        $view           = '/login.php';
        break;

    case '/signup':
        $currentPage    = 'signup';
        $view           = '/signup.php';
        break;

    case '/logout':
        session_destroy();
        header('Location: /');
        exit;

    // PROFILER
    case '/profil':
        if (!$isLoggedIn) {
            header('Location: /login');
            exit;
        }

        $currentPage    = 'profil';
        $view           = $isAdmin ? '/admin.php' : '/user.php';
        break;

    // ALLE EVENTS
    case '/events':
        if (!$isLoggedIn) {
            header('Location: /login');
            exit;
        }

        $currentPage    = 'events';
        $view           = '/events.php';
        break;

    // SINGLE EVENT
    case '/eventside':
        if (!$isLoggedIn) {
            header('Location: /login');
            exit;
        }

        $currentPage    = 'events';
        $view           = '/eventside.php';
        break;

    // OPRET EVENT (admin)
    case '/event_opret':
        if (!$isAdmin) {
            header('Location: /');
            exit;
        }

        $currentPage    = 'events';
        $view           = '/event_opret.php';
        break;

    // KALENDER
    case '/kalender':
        if (!$isLoggedIn) {
            header('Location: /login');
            exit;
        }

        $currentPage    = 'kalender';
        $view           = $isAdmin ? '/kalender_admin.php' : '/kalender_user.php';
        break;

    // MEDLEMMER (admin)
    case '/medlemsskab':
        if (!$isAdmin) {
            header('Location: /');
            exit;
        }

        $currentPage    = 'medlemsskab';
        $view           = '/medlemsskab.php';
        break;

    // KONTAKT
    case '/kontakt':
        $currentPage    = 'kontakt';
        $view           = '/kontakt.php';
        break;

    // OM
    case '/om':
        $currentPage    = 'om';
        $view           = '/om.php';
        break;


    default:
        http_response_code(404);
        echo '404 - Not Found';
        exit;
}

// --------------------------------------
// Load layout (header + view + footer)
// --------------------------------------

require __DIR__ . '/../App/Views/components/_header.php';
require __DIR__ . '/../App/Views' . $view;
require __DIR__ . '/../App/Views/components/_footer.php';