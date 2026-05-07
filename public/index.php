<?php
session_start();

// TIL TEST AF NAV - SKAL FJERNES NÅR LOGIN/SIGNUP VIRKER
// ret til role=admin for at teste admin-sider, og udkommenter alt hvis der skal testes uden login
// $_SESSION['user'] = [
//     'id' => 1,
//     'name' => 'Test User',
//     'role' => 'admin'
// ];

require_once __DIR__ . '/../private/db.php';
require_once __DIR__ . '/../App/Controllers/AuthController.php';
require_once __DIR__ . '/../App/Controllers/MedlemController.php';

$db = getDB();

$authController = new AuthController($db);
$userModel = new UserModel($db);

// Henter kun path fra URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Auth status (i header)
$isLoggedIn = isset($_SESSION['user']);
$userRole = $_SESSION['user']['role_fk'] ?? null;

$isAdmin = $isLoggedIn && $userRole == 1;
$isMember = $isLoggedIn && $userRole == 2;
$isUser = $isLoggedIn && $userRole == 3;

$isProfileSection = false;

$currentPage = '';
$view = null;

// Router
switch ($uri) {

// LANDING / FORSIDE
    case '/':
        require_once __DIR__ . '/../App/Controllers/EventController.php';
        $events = EventController::getLatest(3);
        $currentPage = '';
        $view = '/forside.php';
        break;

        // LOG IND
    case '/log_ind':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
            exit;
        }

        $currentPage = 'log_ind';
        $view = '/log_ind.php';
        break;

        // OPRET DIG
    case '/opret_dig':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->signup();
            exit;
        }

        $currentPage = 'opret_dig';
        $view = '/opret_dig.php';
        break;

        // LOG UD
    case '/log_ud':
        session_destroy();
        header('Location: /');
        exit;

        // PROFIL
    case '/profil':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        $user = $userModel->findById($_SESSION['user']['user_pk']);
        $member = $userModel->findMemberByUserId($_SESSION['user']['user_pk']);

        $currentPage = 'profil';

        if ($isAdmin) {
            $view = '/profil_admin.php';
        } elseif ($isMember) {
            $view = '/profil_member.php';
        } else {
            $view = '/profil_user.php';
        }

        $isProfileSection = true;
        break;

        // EVENTS
    case '/events':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        require_once __DIR__ . '/../App/Controllers/EventController.php';
        $events = EventController::getAll();
        $currentPage = 'events';
        $view = '/events.php';
        break;

        // SINGLE EVENT
    case '/eventside':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        require_once __DIR__ . '/../App/Controllers/EventController.php';
        $event = EventController::getById($_GET['id'] ?? '');

        if (!$event) {
            header('Location: /events');
            exit;
        }

        $dato = $event['dato'];
        $currentPage = 'events';
        $view = '/eventside.php';
        break;

        // TILMELDTE EVENTS
    case '/event_user':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        $currentPage = 'event_user';
        $view = '/event_user.php';
        $isProfileSection = true;
        break;

        // OPRET EVENT (ADMIN)
    case '/event_opret':
        if (!$isAdmin) {
            header('Location: /');
            exit;
        }

        $currentPage = 'event_opret';
        $view = '/event_opret.php';
        $isProfileSection = true;
        break;

        // KALENDER
    case '/kalender':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        $currentPage = 'kalender';
        $view = $isAdmin ? '/kalender_admin.php' : '/kalender_user.php';
        $isProfileSection = true;
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            MedlemController::createApplication();
            exit;
        }

        $currentPage = 'medlem_sog';
        $view = '/medlem_sog.php';
        break;

        // GODKEND MEDLEMSSKAB
    case '/medlem_godkend':
        if (!$isAdmin) {
            header('Location: /');
            exit;
        }

        $applications = MedlemController::getPending();
        $members = MedlemController::getApproved();

        $currentPage = 'medlem_godkend';
        $view = '/medlem_godkend.php';
        $isProfileSection = true;
        break;

        // ALLE MEDLEMMER
    case '/medlemmer':
        $members = MedlemController::getApproved();
        
        $currentPage = 'medlemmer';
        $view = '/medlemmer.php';
        break;

        // OM
    case '/om':
        $currentPage = 'om';
        $view = '/om.php';
        break;

        // GODKEND, AFVIS OG SLET MEDLEMMER
    case '/godkend_medlem':
        if (!$isAdmin || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        MedlemController::approve();
        exit;

    case '/afvis_medlem':
        if (!$isAdmin || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        MedlemController::reject();
        exit;

    case '/slet_medlem':
        if (!$isAdmin || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        MedlemController::delete();
        exit;

        // DEFAULT
    default:
        http_response_code(404);
        echo '404 - Not Found';
        exit;
}

// LOAD LAYOUT
require __DIR__ . '/../App/Views/components/_header.php';
require __DIR__ . '/../App/Views' . $view;
require __DIR__ . '/../App/Views/components/_footer.php';