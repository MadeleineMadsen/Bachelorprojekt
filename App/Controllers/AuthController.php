<?php

require_once __DIR__ . '/../Models/UserModel.php';
require_once __DIR__ . '/../../private/helpers.php';
require_once __DIR__ . '/../../private/mailhelpers.php';

class AuthController
{
    private UserModel $userModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new UserModel($db);
    }

    public function showLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();

            $this->login();
            exit;
        }

        $currentPage = 'login';
        $view = '/login.php';

        load_view($view, [
            'currentPage' => $currentPage,
        ]);
    }

    public function login(): void
    {
        $email = trim($_POST['user_email'] ?? '');
        $password = $_POST['user_password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $_SESSION['error'] = 'Forkert email eller adgangskode';
            header('Location: /login');
            exit;
        }

        if ($user['locked_at'] !== null) {
            $_SESSION['error'] = 'Din konto er låst. Tjek din mail for et oplåsningslink.';
            header('Location: /login');
            exit;
        }

        if (!password_verify($password, $user['user_password'])) {
            $attempts = $this->userModel->recordFailedAttempt($user['user_pk']);

            if ($attempts >= 5) {
                $unlockKey = bin2hex(random_bytes(16));
                $this->userModel->lockAccount($user['user_pk'], $unlockKey);
                sendAccountUnlockMail($user['user_email'], $user['user_name'], $unlockKey);
                $_SESSION['error'] = 'Din konto er låst pga. for mange fejlede forsøg. Vi har sendt et oplåsningslink til din mail.';
            } else {
                $remaining = 5 - $attempts;
                $_SESSION['error'] = 'Forkert email eller adgangskode. ' . $remaining . ' forsøg tilbage.';
            }

            header('Location: /login');
            exit;
        }

        if ($user['user_verified_at'] === null) {
            $_SESSION['error'] = 'Du skal bekræfte din mail, før du kan logge ind';
            header('Location: /login');
            exit;
        }

        $this->userModel->resetLoginAttempts($user['user_pk']);

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'user_pk' => $user['user_pk'],
            'user_name' => $user['user_name'],
            'user_last_name' => $user['user_last_name'],
            'user_email' => $user['user_email'],
            'role_fk' => $user['role_fk'],
            'user_profile_image' => $user['user_profile_image']
        ];

        header('Location: /profile');
        exit;
    }

    public function unlockAccount(): void
    {
        $key = $_GET['key'] ?? '';

        if (!$key) {
            $_SESSION['error'] = 'Ugyldigt oplåsningslink';
            header('Location: /login');
            exit;
        }

        $unlocked = $this->userModel->unlockAccount($key);

        if ($unlocked) {
            $_SESSION['success'] = 'Din konto er nu låst op. Du kan logge ind igen.';
            header('Location: /login');
            exit;
        }

        $_SESSION['error'] = 'Linket er ugyldigt eller allerede brugt';
        header('Location: /login');
        exit;
    }

    public function showSignup(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                require_csrf();

                $this->signup();
                exit;

            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();

                $_SESSION['old'] = [
                    'user_name' => $_POST['user_name'] ?? '',
                    'user_last_name' => $_POST['user_last_name'] ?? '',
                    'user_email' => $_POST['user_email'] ?? '',
                ];

                redirect('/signup');
            }
        }

        $currentPage = 'signup';
        $view = '/signup.php';

        load_view($view, [
            'currentPage' => $currentPage,
        ]);
    }

    public function signup(): void
    {
        $userName = trim($_POST['user_name'] ?? '');
        $lastName = trim($_POST['user_last_name'] ?? '');
        $email = validate_email('user_email');

        $password = validate_password('user_password');
        $confirmPassword = validate_password('confirm_password');

        if ($password !== $confirmPassword) {
            throw new Exception('Adgangskoderne matcher ikke.');
        }

        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = 'Email eksisterer allerede';
            header('Location: /signup');
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
            header('Location: /login');
            exit;
        }

        $_SESSION['error'] = 'Der skete en fejl. Prøv igen.';
        header('Location: /signup');
        exit;
    }

    public function verifyUser(): void
    {
        $key = $_GET['key'] ?? '';

        if (!$key) {
            $_SESSION['error'] = 'Ugyldigt bekræftelseslink';
            header('Location: /login');
            exit;
        }

        $verified = $this->userModel->verifyUser($key);

        if ($verified) {
            $_SESSION['success'] = 'Din mail er bekræftet. Du kan nu logge ind.';
            header('Location: /login');
            exit;
        }

        $_SESSION['error'] = 'Linket er ugyldigt eller allerede brugt';
        header('Location: /login');
        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header('Location: /');
        exit;
    }
}