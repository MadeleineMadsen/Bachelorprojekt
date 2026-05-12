<?php

require_once __DIR__ . '/../Models/UserModel.php';
require_once __DIR__ . '/../../private/helpers.php';

class AuthController
{
    private UserModel $userModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new UserModel($db);
    }

    public function showLogin(): void
    {
        require __DIR__ . '/../views/log_ind.php';
    }

    public function login(): void
    {
        $email = trim($_POST['user_email'] ?? '');
        $password = $_POST['user_password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['user_password'])) {
            $_SESSION['error'] = 'Forkert email eller adgangskode';
            header('Location: /log_ind');
            exit;
        }

        if ($user['user_verified_at'] === null) {
            $_SESSION['error'] = 'Du skal bekræfte din mail, før du kan logge ind';
            header('Location: /log_ind');
            exit;
        }

        $_SESSION['user'] = [
            'user_pk' => $user['user_pk'],
            'user_name' => $user['user_name'],
            'user_last_name' => $user['user_last_name'],
            'user_email' => $user['user_email'],
            'role_fk' => $user['role_fk'],
            'user_profile_image' => $user['user_profile_image']
        ];

        header('Location: /profil');
        exit;
    }

    public function showSignup(): void
    {
        require __DIR__ . '/../views/auth/signup.php';
    }

    public function signup(): void
    {
        $userName = trim($_POST['user_name'] ?? '');
        $lastName = trim($_POST['user_last_name'] ?? '');
        $email = trim($_POST['user_email'] ?? '');
        $password = $_POST['user_password'] ?? '';

        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = 'Email eksisterer allerede';
            header('Location: /opret_dig');
            exit;
        }

        $verificationKey = bin2hex(random_bytes(16));

        $created = $this->userModel->create(
            $userName,
            $lastName,
            $email,
            $password,
            $verificationKey
        );

        if ($created) {
            sendUserVerificationMail($email, $userName, $verificationKey);

            $_SESSION['success'] = 'Din bruger er oprettet. Tjek din mail for at bekræfte din konto.';
            header('Location: /log_ind');
            exit;
        }

        $_SESSION['error'] = 'Der skete en fejl. Prøv igen.';
        header('Location: /opret_dig');
        exit;
    }

    public function verifyUser(): void
    {
        $key = $_GET['key'] ?? '';

        if (!$key) {
            $_SESSION['error'] = 'Ugyldigt bekræftelseslink';
            header('Location: /log_ind');
            exit;
        }

        $verified = $this->userModel->verifyUser($key);

        if ($verified) {
            header('Location: /log_ind');
            exit;
        }

        $_SESSION['error'] = 'Linket er ugyldigt eller allerede brugt';
        header('Location: /log_ind');
        exit;
    }

    public function logout(): void
    {
        session_destroy();

        header('Location: /log_ind');
        exit;
    }
}