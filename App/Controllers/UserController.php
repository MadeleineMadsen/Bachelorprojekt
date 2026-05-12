<?php

require_once __DIR__ . '/../Models/UserModel.php';
require_once __DIR__ . '/../Models/MedlemModel.php';

class UserController
{
    private UserModel $userModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new UserModel($db);
    }

    public function profile(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /log_ind');
            exit;
        }
        
        $user = $this->userModel->findById($_SESSION['user']['user_pk']);
        $member = $this->userModel->findMemberByUserId($_SESSION['user']['user_pk']);
        
        $educations = MedlemModel::getEducations();
        $semesters = MedlemModel::getSemesters();

        require __DIR__ . '/../views/users/profil.php';
    }

    public function updateProfile(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /log_ind');
            exit;
        }

        $id = $_SESSION['user']['user_pk'];

        $userName = trim($_POST['user_name'] ?? '');
        $lastName = trim($_POST['user_last_name'] ?? '');
        $email = trim($_POST['user_email'] ?? '');
        $password = $_POST['user_password'] ?? '';

        $educationFk = (int) ($_POST['education_fk'] ?? 0);
        $semesterFk = (int) ($_POST['semester_fk'] ?? 0);

        $this->userModel->updateProfile($id, $userName, $lastName, $email);

        if (!empty($password)) {
            $this->userModel->updatePassword($id, $password);
        }

        if ($educationFk > 0 && $semesterFk > 0) {
            $this->userModel->updateMemberProfile($id, $educationFk, $semesterFk);
        }

        $_SESSION['user']['user_name'] = $userName;
        $_SESSION['user']['user_last_name'] = $lastName;
        $_SESSION['user']['user_email'] = $email;

        header('Location: /profil');
        exit;
    }

    public function updateProfileImage(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /log_ind');
            exit;
        }

        $userId = $_SESSION['user']['user_pk'];

        if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
            header('Location: /profil');
            exit;
        }

        $uploadDir = __DIR__ . '/../../public/assets/img/uploads/';

        $fileTmp = $_FILES['profile_image']['tmp_name'];
        $fileName = $_FILES['profile_image']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($fileExt, $allowedExtensions)) {
            header('Location: /profil');
            exit;
        }

        $profileImageName = uniqid('profile_', true) . '.' . $fileExt;

        if (!move_uploaded_file($fileTmp, $uploadDir . $profileImageName)) {
            header('Location: /profil');
            exit;
        }

        $this->userModel->updateProfileImage($userId, $profileImageName);

        $_SESSION['user']['user_profile_image'] = $profileImageName;

        header('Location: /profil');
        exit;
    }

    public function members(): void
    {
        $users = $this->userModel->getAll();

        require __DIR__ . '/../views/users/members.php';
    }
}