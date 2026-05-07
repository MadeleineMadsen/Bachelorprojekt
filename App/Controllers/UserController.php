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

    public function members(): void
    {
        $users = $this->userModel->getAll();

        require __DIR__ . '/../views/users/members.php';
    }
}