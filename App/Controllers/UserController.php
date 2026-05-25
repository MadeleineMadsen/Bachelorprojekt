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

        // Tjekker om der er uploadet et billede uden fejl
        if (
            isset($_FILES['profile_image']) &&
            $_FILES['profile_image']['error'] === UPLOAD_ERR_OK
        ) {
            // Finder filens extension
            $extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));

            // Tilladte billedformater
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            // Tjekker om filtypen er tilladt
            if (in_array($extension, $allowedExtensions, true)) {

                // Opretter unikt filnavn
                $fileName = 'profile_' . $userId . '_' . time() . '.' . $extension;

                $uploadDir = __DIR__ . '/../../public/assets/img/uploads/';

                // Opretter upload-mappen hvis den ikke findes
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $uploadPath = $uploadDir . $fileName;

                // Gemmer billedet på serveren
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {

                    // Opdaterer profilbilledet i databasen
                    $this->userModel->updateProfileImage($userId, $fileName);

                    // Opdaterer sessionen så billedet ændres med det samme
                    $_SESSION['user']['user_profile_image'] = $fileName;

                    $_SESSION['success'] = 'Dit profilbillede er opdateret.';
                } else {
                    $_SESSION['error'] = 'Profilbilledet kunne ikke uploades.';
                }
            } else {
                $_SESSION['error'] = 'Profilbilledet skal være jpg, jpeg, png eller webp.';
            }
        } else {
            $_SESSION['error'] = 'Vælg venligst et profilbillede.';
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

        // Soft-deleter brugeren i databasen
        $this->userModel->softDelete($_SESSION['user']['user_pk']);

        // Logger brugeren ud efter sletning
        session_destroy();
        session_start();

        $_SESSION['success'] = 'Din profil er blevet slettet.';

        redirect('/');
    }
}