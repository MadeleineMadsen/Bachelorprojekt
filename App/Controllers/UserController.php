<?php

// Importerer modeller samt helper-funktioner til login, redirects, validering osv.
require_once __DIR__ . '/../Models/UserModel.php';
require_once __DIR__ . '/../Models/MemberModel.php';
require_once __DIR__ . '/../../private/helpers.php';

class UserController
{
    // Model til databasekald relateret til brugere
    private UserModel $userModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new UserModel($db);
    }

    /* ==================================================
    PROFILVISNING
    ================================================== */

    // Viser brugerens profilside
    public function profile(): void
    {
        require_login();

        // Henter brugerens data fra databasen
        $user = $this->userModel->findById($_SESSION['user']['user_pk']);

        // Henter medlemsdata hvis brugeren er medlem
        $member = $this->userModel->findMemberByUserId($_SESSION['user']['user_pk']) ?? [];

        // Henter uddannelser og semestre til dropdowns
        $educations = MemberModel::getEducations();
        $semesters = MemberModel::getSemesters();

        $currentPage = 'profile';

        load_view('/profile.php', [
            'user' => $user,
            'member' => $member,
            'educations' => $educations,
            'semesters' => $semesters,
            'currentPage' => $currentPage,
            'isProfileSection' => true,
        ]);
    }

    /* ==================================================
    OPDATER PROFIL
    ================================================== */

    // Opdaterer brugerens profiloplysninger
    public function updateProfile(): void
    {
        require_login();

        // Profilopdatering må kun ske via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profile');
        }

        try {
            require_csrf();

            $userId = $_SESSION['user']['user_pk'];

            // Validerer inputfelter
            $userName = validate_text('user_name', 'Fornavn', 2, 50);
            $lastName = validate_text('user_last_name', 'Efternavn', 2, 50);
            $email = validate_email('user_email');

            // Password er valgfrit ved profilopdatering
            $password = validate_password('user_password', false);

        } catch (Exception $e) {
            // Viser fejlbesked hvis validering fejler
            $_SESSION['error'] = $e->getMessage();
            redirect('/profile');
        }

        // Henter medlemsoplysninger fra formularen
        $education = (int) ($_POST['education_fk'] ?? 0);
        $semester = (int) ($_POST['semester_fk'] ?? 0);

        // Opdaterer brugerens grundlæggende profiloplysninger
        $this->userModel->updateProfile(
            $userId,
            $userName,
            $lastName,
            $email
        );

        // Opdaterer kun password hvis brugeren har indtastet et nyt
        if (!empty($password)) {
            $this->userModel->updatePassword($userId, $password);
        }

        // Opdaterer medlemsprofil hvis uddannelse og semester er valgt
        if ($education > 0 && $semester > 0) {
            $this->userModel->updateMemberProfile(
                $userId,
                $education,
                $semester
            );
        }

        // Opdaterer sessionen så ændringer vises med det samme
        $_SESSION['user']['user_name'] = $userName;
        $_SESSION['user']['user_last_name'] = $lastName;
        $_SESSION['user']['user_email'] = $email;

        $_SESSION['success'] = 'Din profil er opdateret.';

        redirect('/profile');
    }

    /* ==================================================
    PROFILBILLEDE
    ================================================== */

    // Opdaterer brugerens profilbillede
    public function updateProfileImage(): void
    {
        require_login();

        // Upload må kun ske via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profile');
        }

        require_csrf();

        $userId = $_SESSION['user']['user_pk'];

        try {

            // Validerer uploadet billede via helper
            $image = validate_image_upload('profile_image');

            // Stopper hvis brugeren ikke har valgt et billede
            if ($image === null) {
                throw new Exception('Vælg venligst et profilbillede.');
            }

            // Upload-mappe til profilbilleder
            $uploadDir = __DIR__ . '/../../public/assets/img/uploads/';

            // Fuld sti til det nye billede
            $uploadPath = $uploadDir . $image['file_name'];

            // Gemmer billedet i uploads-mappen
            if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
                throw new Exception('Profilbilledet kunne ikke uploades.');
            }

            // Opdaterer profilbilledet i databasen
            $this->userModel->updateProfileImage(
                $userId,
                $image['file_name']
            );

            // Opdaterer sessionen så billedet ændres med det samme
            $_SESSION['user']['user_profile_image'] = $image['file_name'];

            $_SESSION['success'] = 'Dit profilbillede er opdateret.';

        } catch (Exception $e) {

            // Viser fejlbesked hvis upload eller validering fejler
            $_SESSION['error'] = $e->getMessage();
        }

        redirect('/profile');
    }

    /* ==================================================
    SLET PROFIL
    ================================================== */

    // Soft-deleter brugerens profil
    public function deleteProfile(): void
    {
        require_login();

        // Sletning må kun ske via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profile');
        }

        require_csrf();

        // Gem brugerdata før session ødelægges
        $email = $_SESSION['user']['user_email'];
        $name  = $_SESSION['user']['user_name'];

        // Soft-deleter brugeren i databasen
        $this->userModel->softDelete($_SESSION['user']['user_pk']);

        sendAccountDeletedMail($email, $name);

        // Logger brugeren ud efter sletning
        session_destroy();
        session_start();

        $_SESSION['success'] = 'Din profil er blevet slettet.';

        redirect('/');
    }
}