<?php

// Importerer model og helper-funktioner
require_once __DIR__ . '/../Models/UserModel.php';
require_once __DIR__ . '/../../private/helpers.php';
require_once __DIR__ . '/../../private/mailhelpers.php';

class AuthController
{
    // Model til databasekald relateret til brugere
    private UserModel $userModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new UserModel($db);
    }

    /* ==================================================
    LOGIN
    ================================================== */

    // Viser login-siden eller håndterer login-formularen
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

    // Håndterer selve login-processen
    public function login(): void
    {
        // Henter input fra login-formularen
        $email = trim($_POST['user_email'] ?? '');
        $password = $_POST['user_password'] ?? '';

        // Finder brugeren ud fra email
        $user = $this->userModel->findByEmail($email);

        // Stopper login hvis brugeren ikke findes
        if (!$user) {
            $_SESSION['error'] = 'Forkert email eller adgangskode';
            redirect('/login');
        }

        // Stopper login hvis kontoen er låst
        if ($user['locked_at'] !== null) {
            $_SESSION['error'] = 'Din konto er låst. Tjek din mail for et oplåsningslink.';
            redirect('/login');
        }

        // Kontrollerer om adgangskoden matcher
        if (!password_verify($password, $user['user_password'])) {

            // Registrerer et fejlet loginforsøg
            $attempts = $this->userModel->recordFailedAttempt($user['user_pk']);

            // Låser kontoen efter 5 fejlede forsøg
            if ($attempts >= 5) {
                $unlockKey = bin2hex(random_bytes(16));
                $this->userModel->lockAccount($user['user_pk'], $unlockKey);
                $mailSent = sendAccountUnlockMail($user['user_email'], $user['user_name'], $unlockKey);

                if ($mailSent) {
                    $_SESSION['error'] = 'Din konto er låst pga. for mange fejlede forsøg. Vi har sendt et oplåsningslink til din mail.';
                } else {
                    $_SESSION['error'] = 'Din konto er låst, men oplåsningsmailen kunne ikke sendes.';
                }
            } else {
                $remaining = 5 - $attempts;
                $_SESSION['error'] = 'Forkert email eller adgangskode. ' . $remaining . ' forsøg tilbage.';
            }

            redirect('/login');
        }

        // Kræver at brugeren har bekræftet sin mail
        if ($user['user_verified_at'] === null) {
            $_SESSION['error'] = 'Du skal bekræfte din mail, før du kan logge ind';
            redirect('/login');
        }

        // Nulstiller fejlede loginforsøg efter korrekt login
        $this->userModel->resetLoginAttempts($user['user_pk']);

        // Forhindrer session fixation ved login
        session_regenerate_id(true);

        // Gemmer nødvendige brugerdata i sessionen
        $_SESSION['user'] = [
            'user_pk' => $user['user_pk'],
            'user_name' => $user['user_name'],
            'user_last_name' => $user['user_last_name'],
            'user_email' => $user['user_email'],
            'role_fk' => $user['role_fk'],
            'user_profile_image' => $user['user_profile_image']
        ];

        redirect('/profile');
    }

    // Låser en konto op via link sendt på mail
    public function unlockAccount(): void
    {
        $key = $_GET['key'] ?? '';

        // Tjekker om der findes en oplåsningsnøgle i URL'en
        if (!$key) {
            $_SESSION['error'] = 'Ugyldigt oplåsningslink';
            redirect('/login');
        }

        // Forsøger at låse kontoen op
        $unlocked = $this->userModel->unlockAccount($key);

        if ($unlocked) {
            $_SESSION['success'] = 'Din konto er nu låst op. Du kan logge ind igen.';
            redirect('/login');
        }

        $_SESSION['error'] = 'Linket er ugyldigt eller allerede brugt';
        redirect('/login');
    }

    /* ==================================================
    SIGNUP
    ================================================== */

    // Viser signup-siden eller håndterer oprettelse af bruger
    public function showSignup(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                require_csrf();

                $this->signup();
                exit;

            } catch (Exception $e) {
                // Viser fejlbesked og gemmer tidligere input
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

    // Håndterer oprettelse af ny bruger
    public function signup(): void
    {
        // Henter og validerer input fra signup-formularen
        $userName = trim($_POST['user_name'] ?? '');
        $lastName = trim($_POST['user_last_name'] ?? '');
        $email = validate_email('user_email');

        $password = validate_password('user_password');
        $confirmPassword = validate_password('confirm_password');

        // Sikrer at adgangskoderne matcher
        if ($password !== $confirmPassword) {
            throw new Exception('Adgangskoderne matcher ikke.');
        }

        // Tjekker om email allerede findes
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = 'Email eksisterer allerede';
            redirect('/signup');
        }

        // Opretter unik nøgle til mailbekræftelse
        $verificationKey = bin2hex(random_bytes(16));

        // Opretter brugeren i databasen
        $created = $this->userModel->create(
            $userName,
            $lastName,
            $email,
            $password,
            $verificationKey
        );

        // Sender bekræftelsesmail hvis brugeren blev oprettet
        if ($created) {
            $mailSent = sendUserVerificationMail($email, $userName, $verificationKey);

            if (!$mailSent) {
                $_SESSION['error'] = 'Din bruger blev oprettet, men bekræftelsesmailen kunne ikke sendes.';
                redirect('/login');
            }

            $_SESSION['success'] = 'Din bruger er oprettet. Tjek din mail for at bekræfte din konto.';
            redirect('/login');
        }

        $_SESSION['error'] = 'Der skete en fejl. Prøv igen.';
        redirect('/signup');
    }

    // Bekræfter brugerens email via link sendt på mail
    public function verifyUser(): void
    {
        $key = $_GET['key'] ?? '';

        // Tjekker om der findes en bekræftelsesnøgle i URL'en
        if (!$key) {
            $_SESSION['error'] = 'Ugyldigt bekræftelseslink';
            redirect('/login');
        }

        // Forsøger at bekræfte brugeren
        $verified = $this->userModel->verifyUser($key);

        if ($verified) {
            $_SESSION['success'] = 'Din mail er bekræftet. Du kan nu logge ind.';
            redirect('/login');
        }

        $_SESSION['error'] = 'Linket er ugyldigt eller allerede brugt';
        redirect('/login');
    }

    /* ==================================================
    GLEMT KODEORD
    ================================================== */

    // Viser formularen til at anmode om nulstilling af adgangskode
    public function showForgotPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();
            $this->requestPasswordReset();
            exit;
        }

        $currentPage = 'forgot_password';
        $view = '/forgot_password.php';

        load_view($view, [
            'currentPage' => $currentPage,
        ]);
    }

    // Håndterer anmodning om nulstilling sender mail med reset-link
    private function requestPasswordReset(): void
    {
        $email = trim($_POST['user_email'] ?? '');

        $user = $this->userModel->findByEmail($email);

        // Giver samme besked uanset om mailen findes undgår at afsløre kontoer
        if (!$user) {
            $_SESSION['success'] = 'Hvis din mail er registreret, har vi sendt et link til dig.';
            redirect('/login');
        }

        $resetKey = bin2hex(random_bytes(16));
        $this->userModel->savePasswordResetKey($user['user_pk'], $resetKey);

        $mailSent = sendPasswordResetMail($user['user_email'], $user['user_name'], $resetKey);

        if (!$mailSent) {
            $_SESSION['error'] = 'Der skete en fejl. Prøv igen.';
            redirect('/login');
        }

        $_SESSION['success'] = 'Hvis din mail er registreret, har vi sendt et link til dig.';
        redirect('/login');
    }

    // Viser formularen til at indtaste ny adgangskode via reset-link
    public function showResetPassword(): void
    {
        $key = $_GET['key'] ?? '';

        if (!$key) {
            $_SESSION['error'] = 'Ugyldigt link';
            redirect('/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();
            $this->resetPassword($key);
            exit;
        }

        // Validerer at nøglen stadig er gyldig inden siden vises
        $user = $this->userModel->findByResetKey($key);

        if (!$user) {
            $_SESSION['error'] = 'Linket er ugyldigt eller udløbet';
            redirect('/login');
        }

        $currentPage = 'reset_password';
        $view = '/reset_password.php';

        load_view($view, [
            'currentPage' => $currentPage,
            'key' => $key,
        ]);
    }

    // Sætter ny adgangskode og sletter reset-nøglen
    private function resetPassword(string $key): void
    {
        $user = $this->userModel->findByResetKey($key);

        if (!$user) {
            $_SESSION['error'] = 'Linket er ugyldigt eller udløbet';
            redirect('/login');
        }

        $password = validate_password('user_password');
        $confirmPassword = validate_password('confirm_password');

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Adgangskoderne matcher ikke';
            redirect('/reset_password?key=' . urlencode($key));
        }

        $updated = $this->userModel->resetPassword($user['user_pk'], $password);

        if ($updated) {
            $_SESSION['success'] = 'Din adgangskode er nulstillet. Du kan nu logge ind.';
            redirect('/login');
        }

        $_SESSION['error'] = 'Der skete en fejl. Prøv igen.';
        redirect('/reset_password?key=' . urlencode($key));
    }

    /* ==================================================
    LOGOUT
    ================================================== */

    // Logger brugeren ud og sletter sessionen
    public function logout(): void
    {
        // Tømmer alle session-data
        $_SESSION = [];

        // Sletter session-cookie hvis sessions bruger cookies
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

        // Afslutter sessionen helt
        session_destroy();

        redirect('/');
    }
}