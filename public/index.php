<?php
session_start();

require_once __DIR__ . '/../private/db.php';
require_once __DIR__ . '/../private/helpers.php';
require_once __DIR__ . '/../private/mailhelpers.php';
require_once __DIR__ . '/../App/Controllers/AuthController.php';
require_once __DIR__ . '/../App/Controllers/EventController.php';
require_once __DIR__ . '/../App/Controllers/UserController.php';
require_once __DIR__ . '/../App/Controllers/MedlemController.php';

$db = getDB();

$authController = new AuthController($db);
$userController = new UserController($db);

// Henter kun path fra URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Router
switch ($uri) {

    // LANDING / FORSIDE
    case '/':
        EventController::showFrontpage();
        exit;

    // LOG IND
    case '/log_ind':
        $authController->showLogin();
        exit;

    // OPRET DIG
    case '/opret_dig':
        $authController->showSignup();
        exit;

    // VERIFICER BRUGER
    case '/verificer_bruger':
        $authController->verifyUser();
        exit;

    // LÅS OP
    case '/laas_op':
        $authController->unlockAccount();
        exit;

    // LOG UD
    case '/log_ud':
        $authController->logout();
        exit;

    // PROFIL
    case '/profil':
        $userController->profile();
        exit;

    // PROFIL - OPDATERING AF OPLYSNINGER
    case '/profil/update':
        $userController->updateProfile();
        exit;

    // PROFIL - OPDATER KUN PROFILBILLEDE
    case '/profil/update-image':
        $userController->updateProfileImage();
        exit;

    // PROFIL - SLET PROFIL
    case '/profil/delete':
        $userController->deleteProfile();
        exit;

    // EVENTS
    case '/events':
        EventController::showAll();
        exit;

    // SINGLE EVENT
    case '/eventside':
        EventController::showSingle();
        exit;

    // TILMELD EVENT
    case '/event_tilmeld':
        EventController::toggleRegistration();
        exit;

    // TILMELDTE EVENTS
    case '/event_user':
        EventController::showUserEvents();
        exit;

    // REDIGER EVENT (ADMIN)
    case '/event_rediger':
        EventController::showEdit();
        exit;

    // SLET EVENT (ADMIN)
    case '/event_slet':
        EventController::deleteEvent();
        exit;

    // OPRET EVENT (ADMIN)
    case '/event_opret':
        EventController::showCreate();
        exit;

    // KALENDER
    case '/kalender':
        EventController::showCalendar();
        exit;

    // SØG OM MEDLEMSSKAB
    case '/medlem_sog':
        MedlemController::applicationPage();
        exit;

    // GODKEND MEDLEMSSKAB
    case '/medlem_godkend':
        MedlemController::showApprovalPage();
        exit;

    // ALLE MEDLEMMER
    case '/medlemmer':
        MedlemController::showMembers();
        exit;

    // GODKEND, AFVIS OG SLET MEDLEMMER
    case '/godkend_medlem':
        MedlemController::approveMember();
        exit;

    case '/afvis_medlem':
        MedlemController::rejectMember();
        exit;

    case '/slet_medlem':
        MedlemController::deleteMember();
        exit;


    // OM
    case '/om':
        MedlemController::showAbout();
        exit;

    // VILKÅR OG BETINGELSER
    case '/terms':
        load_view('/terms.php', [
            'currentPage' => 'terms',
        ]);
        exit;
    
    // DEFAULT
    default:
        http_response_code(404);
        echo '404 - Not Found';
        exit;
}
