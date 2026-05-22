<?php
session_start();

require_once __DIR__ . '/../private/db.php';
require_once __DIR__ . '/../private/helpers.php';
require_once __DIR__ . '/../private/mailhelpers.php';
require_once __DIR__ . '/../App/Controllers/AuthController.php';
require_once __DIR__ . '/../App/Controllers/EventController.php';
require_once __DIR__ . '/../App/Controllers/UserController.php';
require_once __DIR__ . '/../App/Controllers/MemberController.php';

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
    case '/login':
        $authController->showLogin();
        exit;

    // OPRET DIG
    case '/signup':
        $authController->showSignup();
        exit;

    // VERIFICER BRUGER
    case '/verify_user':
        $authController->verifyUser();
        exit;

    // LÅS OP
    case '/unlock_account':
        $authController->unlockAccount();
        exit;

    // LOG UD
    case '/logout':
        $authController->logout();
        exit;

    // PROFIL
    case '/profile':
        $userController->profile();
        exit;

    // PROFIL - OPDATERING AF OPLYSNINGER
    case '/profile/update':
        $userController->updateProfile();
        exit;

    // PROFIL - OPDATER KUN PROFILBILLEDE
    case '/profile/update-image':
        $userController->updateProfileImage();
        exit;

    // PROFIL - SLET PROFIL
    case '/profile/delete':
        $userController->deleteProfile();
        exit;

    // EVENTS
    case '/events':
        EventController::showAll();
        exit;

    // SINGLE EVENT
    case '/event_page':
        EventController::showSingle();
        exit;

    // TILMELD EVENT
    case '/event_register':
        EventController::toggleRegistration();
        exit;

    // TILMELDTE EVENTS
    case '/my_events':
        EventController::showUserEvents();
        exit;

    // REDIGER EVENT (ADMIN)
    case '/event_edit':
        EventController::showEdit();
        exit;

    // SLET EVENT (ADMIN)
    case '/event_delete':
        EventController::deleteEvent();
        exit;

    // OPRET EVENT (ADMIN)
    case '/event_create':
        EventController::showCreate();
        exit;

    // CALENDAR
    case '/calendar':
        EventController::showCalendar();
        exit;

    // SØG OM MEDLEMSSKAB
    case '/membership_apply':
        MemberController::applicationPage();
        exit;

    // GODKEND MEDLEMSSKAB
    case '/membership_approve':
        MemberController::showApprovalPage();
        exit;

    // ALLE MEDLEMMER
    case '/members':
        MemberController::showMembers();
        exit;

    // GODKEND, AFVIS OG SLET MEDLEMMER
    case '/approve_member':
        MemberController::approveMember();
        exit;

    case '/reject_member':
        MemberController::rejectMember();
        exit;

    case '/delete_member':
        MemberController::deleteMember();
        exit;

    // OM
    case '/about':
        MemberController::showAbout();
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
