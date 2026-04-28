<?php
session_start();

// TIL TEST AF NAV - SKAL FJERNES NÅR LOGIN/SIGNUP VIRKER
// ret til role=admin for at teste admin-sider, og udkommenter alt hvis der skal testes uden login
$_SESSION['user'] = [
    'id' => 1,
    'name' => 'Test User',
    'role' => 'user'
];



// Henter kun path fra URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Auth status (i header)
$isLoggedIn = isset($_SESSION['user']);
$userRole = $_SESSION['user']['role'] ?? null;

$isAdmin = $isLoggedIn && $userRole === 'admin';
$isMember = $isLoggedIn && $userRole === 'member';
$isUser = $isLoggedIn && $userRole === 'user';

$isProfileSection = false;

// Standard værdier
$currentPage = '';
$view = null;

// Router
switch ($uri) {
    
    // LANDING / FORSIDE
    case '/':
        $currentPage    = '';
        $view           = '/forside.php';
        break;

        // LOGIN / SIGNUP
    case '/log_ind':
        $currentPage    = 'log_ind';
        $view           = '/log_ind.php';
        break;

    case '/opret_dig':
        $currentPage    = 'opret_dig';
        $view           = '/opret_dig.php';
        break;

    case '/log_ud':
        session_destroy();
        header('Location: /');
        exit;

    // PROFILER
    case '/profil':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        $currentPage        = 'profil';

        if ($isAdmin) {
            $view = '/profil_admin.php';
        } elseif ($isMember) {
            $view = '/profil_member.php';
        } else {
            $view = '/profil_user.php';
        }

        $isProfileSection   = true;
        break;


    // ALLE EVENTS
    case '/events':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        $currentPage    = 'events';
        $view           = '/events.php';
        break;

    // SINGLE EVENT
    case '/eventside':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        $currentPage    = 'events';
        $view           = '/eventside.php';
        break;

    // TILMELDTE EVENTS
    case '/event_user':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        $currentPage    = 'event_user';
        $view           = '/event_user.php';
        $isProfileSection   = true;
        break;

    // OPRET EVENT (admin)
    case '/event_opret':
        if (!$isAdmin) {
            header('Location: /');
            exit;
        }

        $currentPage    = 'event_opret';
        $view           = '/event_opret.php';
        $isProfileSection   = true;
        break;

    // KALENDER - OBS: rettes hvis member eller alle skal have egen kalender
    case '/kalender':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        $currentPage    = 'kalender';
        $view           = $isAdmin ? '/kalender_admin.php' : '/kalender_user.php';
        $isProfileSection   = true;
        break;

    // SØG OM MEDLEMSSKAB
    case '/medlem_sog':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        if (!$isUser) {
            header('Location: /profil');
            exit;
        }

        $currentPage        = 'medlem_sog';
        $view               = '/medlem_sog.php';
        break;

    // GODKEND MEDLEMSSKAB
    case '/medlem_godkend':
        if (!$isAdmin) {
            header('Location: /');
            exit;
        }

        $currentPage        = 'medlem_godkend';
        $view               = '/medlem_godkend.php';
        $isProfileSection   = true;
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