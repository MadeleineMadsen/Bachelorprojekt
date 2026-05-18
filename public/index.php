<?php
session_start();

require_once __DIR__ . '/../private/db.php';
require_once __DIR__ . '/../private/helpers.php';
require_once __DIR__ . '/../private/mailhelpers.php';
require_once __DIR__ . '/../App/Controllers/AuthController.php';
require_once __DIR__ . '/../App/Controllers/MedlemController.php';

$db = getDB();

$authController = new AuthController($db);
$userModel = new UserModel($db);

// Henter kun path fra URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Auth status (i header)
$isLoggedIn = is_logged_in();
$userRole = user_role();

$isAdmin = is_admin();
$isMember = is_member();
$isUser = is_user();

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
            require_csrf();

            $authController->login();
            exit;
        }

        $currentPage = 'log_ind';
        $view = '/log_ind.php';
        break;

    // OPRET DIG
    case '/opret_dig':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();

            $authController->signup();
            exit;
        }

        $currentPage = 'opret_dig';
        $view = '/opret_dig.php';
        break;

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
        session_destroy();
        redirect('/');

    // PROFIL
    case '/profil':
        require_login();

        $user = $userModel->findById($_SESSION['user']['user_pk']);
        $member = $userModel->findMemberByUserId($_SESSION['user']['user_pk']);

        $educations = MedlemModel::getEducations();
        $semesters = MedlemModel::getSemesters();

        $currentPage = 'profil';
        $view = '/profil.php';
        $isProfileSection = true;
        break;

    // PROFIL - OPDATERING AF OPLYSNINGER
    case '/profil/update':
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profil');
        }

        require_csrf();

        $userId = $_SESSION['user']['user_pk'];

        $userName = validate_text('user_name', 'Fornavn', 2, 50);
        $lastName = validate_text('user_last_name', 'Efternavn', 2, 50);
        $email = validate_email('user_email');
        $password = validate_password('user_password', false);

        $education = (int) ($_POST['education_fk'] ?? 0);
        $semester = (int) ($_POST['semester_fk'] ?? 0);

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
        if ($education > 0 && $semester > 0) {
            $userModel->updateMemberProfile(
                $userId,
                $education,
                $semester
            );
        }

        // OPDATER SESSION
        $_SESSION['user']['user_name'] = $userName;
        $_SESSION['user']['user_last_name'] = $lastName;
        $_SESSION['user']['user_email'] = $email;

        redirect('/profil');

    // PROFIL - OPDATER KUN PROFILBILLEDE
    case '/profil/update-image':
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profil');
        }

        require_csrf();

        $userId = $_SESSION['user']['user_pk'];

        if (
            isset($_FILES['profile_image']) &&
            $_FILES['profile_image']['error'] === UPLOAD_ERR_OK
        ) {
            $extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($extension, $allowedExtensions, true)) {
                $fileName = 'profile_' . $userId . '_' . time() . '.' . $extension;
                $uploadDir = __DIR__ . '/assets/img/uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                    $userModel->updateProfileImage($userId, $fileName);
                    $_SESSION['user']['user_profile_image'] = $fileName;
                }
            }
        }

        redirect('/profil');

    // PROFIL - SLET PROFIL
    case '/profil/delete':
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profil');
        }

        require_csrf();

        $userModel->softDelete($_SESSION['user']['user_pk']);

        session_destroy();
        redirect('/');

    // EVENTS
    case '/events':
        require_once __DIR__ . '/../App/Controllers/EventController.php';

        $events = EventController::getAll();
        $categories = EventController::getCategories();

        $currentPage = 'events';
        $view = '/events.php';
        break;

    // SINGLE EVENT
    case '/eventside':
        require_once __DIR__ . '/../App/Controllers/EventController.php';

        $event = EventController::getById($_GET['id'] ?? '');

        if (!$event) {
            redirect('/events');
        }

        $dato = $event['dato'];
        $participants = EventController::getParticipants($_GET['id'] ?? '');
        $isRegistered = $isLoggedIn && EventController::isRegistered($_GET['id'] ?? '', $_SESSION['user']['user_pk']);

        $currentPage = 'events';
        $view = '/eventside.php';
        break;

    // TILMELD EVENT
    case '/event_tilmeld':
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/events');
        }

        require_csrf();

        require_once __DIR__ . '/../App/Controllers/EventController.php';

        $eventId = $_POST['event_id'] ?? '';
        $action = $_POST['action'] ?? 'tilmeld';

        if ($action === 'frameld') {
            EventController::unregister($eventId, $_SESSION['user']['user_pk']);
        } else {
            EventController::register($eventId, $_SESSION['user']['user_pk']);
        }

        redirect('/eventside?id=' . urlencode($eventId));

    // TILMELDTE EVENTS
    case '/event_user':
        require_login();

        require_once __DIR__ . '/../App/Controllers/EventController.php';

        $events = EventController::getByUser($_SESSION['user']['user_pk']);

        $currentPage = 'event_user';
        $view = '/event_user.php';
        $isProfileSection = true;
        break;

    // REDIGER EVENT (ADMIN)
    case '/event_rediger':
        require_role(1, '/events');

        require_once __DIR__ . '/../App/Controllers/EventController.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();

            EventController::update();
            exit;
        }

        $event = EventController::getById($_GET['id'] ?? '');

        if (!$event) {
            redirect('/events');
        }

        $categories = EventController::getCategories();

        $currentPage = 'events';
        $view = '/event_opret.php';
        $isProfileSection = true;
        break;

    // SLET EVENT (ADMIN)
    case '/event_slet':
        require_role(1, '/events');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/events');
        }

        require_csrf();

        require_once __DIR__ . '/../App/Controllers/EventController.php';

        EventController::delete($_POST['event_id'] ?? '');

        redirect('/events');

    // OPRET EVENT (ADMIN)
    case '/event_opret':
        require_admin();

        require_once __DIR__ . '/../App/Controllers/EventController.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();

            EventController::create();
            exit;
        }

        $categories = EventController::getCategories();

        $currentPage = 'event_opret';
        $view = '/event_opret.php';
        $isProfileSection = true;
        break;

    // KALENDER
    case '/kalender':
        require_login();

        require_once __DIR__ . '/../App/Controllers/EventController.php';

        $calendarEvents = EventController::getAllForCalendar();
        $registeredEventIds = $isAdmin ? [] : EventController::getRegisteredEventIds($_SESSION['user']['user_pk']);

        $currentPage = 'kalender';
        $view = '/kalender.php';
        $isProfileSection = true;
        break;

    // SØG OM MEDLEMSSKAB
    case '/medlem_sog':
        require_user();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();

            MedlemController::createApplication();
            exit;
        }

        $educations = MedlemModel::getEducations();
        $semesters = MedlemModel::getSemesters();
        $memberStats = MedlemController::getStats();

        $currentPage = 'medlem_sog';
        $view = '/medlem_sog.php';
        break;

    // GODKEND MEDLEMSSKAB
    case '/medlem_godkend':
        require_admin();

        $applications = MedlemController::getPending();
        $educations = MedlemModel::getEducations();
        $members = MedlemController::getVisibleMembers();

        $currentPage = 'medlem_godkend';
        $view = '/medlem_godkend.php';
        $isProfileSection = true;
        break;

    // ALLE MEDLEMMER
    case '/medlemmer':
        $members = MedlemController::getVisibleMembers();
        $educations = MedlemModel::getEducations();
        $memberStats = MedlemController::getStats();

        $currentPage = 'medlemmer';
        $view = '/medlemmer.php';
        break;

    // OM
    case '/om':
        $members = MedlemController::getVisibleMembers();

        $currentPage = 'om';
        $view = '/om.php';
        break;

    // GODKEND, AFVIS OG SLET MEDLEMMER
    case '/godkend_medlem':
        require_admin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/');
        }

        require_csrf();

        MedlemController::approve();
        exit;

    case '/afvis_medlem':
        require_admin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/');
        }

        require_csrf();

        MedlemController::reject();
        exit;

    case '/slet_medlem':
        require_admin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/');
        }

        require_csrf();

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