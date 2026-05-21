<?php

require_once __DIR__ . '/../Models/UserModel.php';
require_once __DIR__ . '/../Models/MedlemModel.php';
require_once __DIR__ . '/../../private/helpers.php';

class UserController
{
    private UserModel $userModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new UserModel($db);
    }

    public function profile(): void
    {
        require_login();

        $user = $this->userModel->findById($_SESSION['user']['user_pk']);
        $member = $this->userModel->findMemberByUserId($_SESSION['user']['user_pk']) ?? [];

        $educations = MedlemModel::getEducations();
        $semesters = MedlemModel::getSemesters();

        $currentPage = 'profil';

        load_view('/profil.php', [
            'user' => $user,
            'member' => $member,
            'educations' => $educations,
            'semesters' => $semesters,
            'currentPage' => $currentPage,
            'isProfileSection' => true,
        ]);
    }


    public function updateProfile(): void
    {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profil');
        }

        try {
            require_csrf();

            $userId = $_SESSION['user']['user_pk'];

            $userName = validate_text('user_name', 'Fornavn', 2, 50);
            $lastName = validate_text('user_last_name', 'Efternavn', 2, 50);
            $email = validate_email('user_email');
            $password = validate_password('user_password', false);

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/profil');
        }

        $education = (int) ($_POST['education_fk'] ?? 0);
        $semester = (int) ($_POST['semester_fk'] ?? 0);

        $this->userModel->updateProfile(
            $userId,
            $userName,
            $lastName,
            $email
        );

        if (!empty($password)) {
            $this->userModel->updatePassword($userId, $password);
        }

        if ($education > 0 && $semester > 0) {
            $this->userModel->updateMemberProfile(
                $userId,
                $education,
                $semester
            );
        }

        $_SESSION['user']['user_name'] = $userName;
        $_SESSION['user']['user_last_name'] = $lastName;
        $_SESSION['user']['user_email'] = $email;

        $_SESSION['success'] = 'Din profil er opdateret.';

        redirect('/profil');
    }

    public function updateProfileImage(): void
    {
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
                $uploadDir = __DIR__ . '/../../public/assets/img/uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                    $this->userModel->updateProfileImage($userId, $fileName);
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

        redirect('/profil');
    }

    public function deleteProfile(): void
    {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profil');
        }

        require_csrf();

        $this->userModel->softDelete($_SESSION['user']['user_pk']);

        session_destroy();
        session_start();

        $_SESSION['success'] = 'Din profil er blevet slettet.';

        redirect('/');
    }
}