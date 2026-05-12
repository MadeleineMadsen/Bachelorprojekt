<?php
session_start();

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

        $educations = MedlemModel::getEducations();
        $semesters = MedlemModel::getSemesters();

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

    // PROFIL - OPDATERING AF OPLYSNINGER
    case '/profil/update':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $userId = $_SESSION['user']['user_pk'];

            $userName = trim($_POST['user_name'] ?? '');
            $lastName = trim($_POST['user_last_name'] ?? '');
            $email = trim($_POST['user_email'] ?? '');
            $password = $_POST['user_password'] ?? '';

            $education = trim($_POST['education'] ?? '');
            $semester = trim($_POST['semester'] ?? '');

            // OPDATER BRUGERDATA
            $userModel->updateProfile(
                $userId,
                $userName,
                $lastName,
                $email
            );

            // OPDATER PASSWORD
            if (!empty($password)) {
                $userModel->updatePassword($userId, $password);
            }

            // OPDATER MEMBER DATA
            if (!empty($education) || !empty($semester)) {
                $userModel->updateMemberProfile(
                    $userId,
                    $education,
                    $semester
                );
            }

            // UPLOAD PROFILBILLEDE
            if (
                isset($_FILES['profile_image']) &&
                $_FILES['profile_image']['error'] === UPLOAD_ERR_OK
            ) {

                $extension = strtolower(
                    pathinfo(
                        $_FILES['profile_image']['name'],
                        PATHINFO_EXTENSION
                    )
                );

                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

                if (in_array($extension, $allowedExtensions)) {

                    $fileName =
                        'profile_' .
                        $userId .
                        '_' .
                        time() .
                        '.' .
                        $extension;

                    $uploadDir =
                        __DIR__ . '/assets/img/uploads/';

                    // Opret mappe hvis den ikke findes
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $uploadPath = $uploadDir . $fileName;

                    // Flyt fil til uploads mappe
                    if (
                        move_uploaded_file(
                            $_FILES['profile_image']['tmp_name'],
                            $uploadPath
                        )
                    ) {

                        // Gem filnavn i database
                        $userModel->updateProfileImage(
                            $userId,
                            $fileName
                        );
                    }
                }
            }

            // OPDATER SESSION
            $_SESSION['user']['user_name'] = $userName;
            $_SESSION['user']['user_last_name'] = $lastName;
            $_SESSION['user']['user_email'] = $email;

            header('Location: /profil');
            exit;
        }

        header('Location: /profil');
        exit;

    // PROFIL - SLET PROFIL
    case '/profil/delete':
        if (!$isLoggedIn) {
            header('Location: /log_ind');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel->softDelete($_SESSION['user']['user_pk']);

            session_destroy();

            header('Location: /');
            exit;
        }

        header('Location: /profil');
        exit;

    // EVENTS
    case '/events':

        require_once __DIR__ . '/../App/Controllers/EventController.php';
        $events = EventController::getAll();
        $currentPage = 'events';
        $view = '/events.php';
        break;

    // SINGLE EVENT
    case '/eventside':

        require_once __DIR__ . '/../App/Controllers/EventController.php';
        $event = EventController::getById($_GET['id'] ?? '');

        if (!$event) {
            header('Location: /events');
            exit;
        }

        $dato = $event['dato'];
        $participants = EventController::getParticipants($_GET['id'] ?? '');
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

        $educations = MedlemModel::getEducations();
        $semesters = MedlemModel::getSemesters();

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
        $educations = MedlemModel::getEducations();
        $members = MedlemController::getApproved();

        $currentPage = 'medlem_godkend';
        $view = '/medlem_godkend.php';
        $isProfileSection = true;
        break;

        // SLET MEDLEMSSKAB
    case '/slet_medlem':
        MedlemController::delete();
        break;

        // ALLE MEDLEMMER
    case '/medlemmer':
        $members = MedlemController::getApproved();
        $educations = MedlemModel::getEducations();
        $memberStats = MedlemController::getStats();
        
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