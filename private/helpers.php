<?php

// Sikkert output i HTML
function e($value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Redirect helper
function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

// Flash beskeder
function flash(string $key): ?string
{
    if (!isset($_SESSION[$key])) {
        return null;
    }

    $message = $_SESSION[$key];
    unset($_SESSION[$key]);

    return $message;
}

// Gem gamle input hvis adgangskode er forkert, så man ikke skal skrive alt på ny igen
function old(string $key, string $default = ''): string
{
    return $_SESSION['old'][$key] ?? $default;
}

// CSRF token
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// CSRF hidden input
function csrf_input(): void
{
    echo '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

// CSRF validering
function csrf_verify(): bool
{
    return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

// Skal bruges på POST routes
function require_csrf(): void
{
    if (!csrf_verify()) {
        $_SESSION['error'] = 'Ugyldig formular. Prøv igen.';
        redirect('/');
    }
}

// Login check
function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

// Rolle check
function user_role(): ?int
{
    return $_SESSION['user']['role_fk'] ?? null;
}

function is_admin(): bool
{
    return is_logged_in() && user_role() === 1;
}

function is_member(): bool
{
    return is_logged_in() && user_role() === 2;
}

function is_user(): bool
{
    return is_logged_in() && user_role() === 3;
}

// Kræv login
function require_login(): void
{
    if (!is_logged_in()) {
        redirect('/login');
    }
}

// Kræv specifik rolle
function require_role(int $role, string $redirectTo = '/'): void
{
    require_login();

    if (user_role() !== $role) {
        redirect($redirectTo);
    }
}

// Kræv admin
function require_admin(): void
{
    require_role(1, '/');
}

// Kræv almindelig bruger
function require_user(): void
{
    require_role(3, '/profile');
}

// Valider tekstfelt
function validate_text(
    string $field,
    string $label,
    int $min = 1,
    int $max = 255
): string {
    $value = trim($_POST[$field] ?? '');

    if ($value === '') {
        throw new Exception($label . ' skal udfyldes.');
    }

    $value = strip_tags($value);

    if (mb_strlen($value) < $min || mb_strlen($value) > $max) {
        throw new Exception($label . " skal være mellem $min og $max tegn.");
    }

    return $value;
}

// Valider email
function validate_email(string $field = 'user_email'): string
{
    $email = trim($_POST[$field] ?? '');

    if ($email === '') {
        throw new Exception('Email skal udfyldes.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email er ikke gyldig.');
    }

    return strtolower($email);
}

// Valider password
function validate_password(
    string $field = 'user_password',
    bool $required = true
): ?string {
    $password = $_POST[$field] ?? '';

    if (!$required && $password === '') {
        return null;
    }

    if ($password === '') {
        throw new Exception('Adgangskode skal udfyldes.');
    }

    if (strlen($password) < 6) {
        throw new Exception('Adgangskoden skal være mindst 6 tegn.');
    }

    return $password;
}

// Valider ID fra POST
function validate_post_id(string $field): int
{
    $id = (int) ($_POST[$field] ?? 0);

    if ($id <= 0) {
        throw new Exception('Ugyldigt ID.');
    }

    return $id;
}

// Header helper
function load_view(string $view, array $data = []): void
{
    $isLoggedIn = is_logged_in();
    $userRole = user_role();

    $isAdmin = is_admin();
    $isMember = is_member();
    $isUser = is_user();

    $isProfileSection = $data['isProfileSection'] ?? false;
    $currentPage = $data['currentPage'] ?? '';

    extract($data);

    require __DIR__ . '/../App/Views/components/_header.php';
    require __DIR__ . '/../App/Views' . $view;
    require __DIR__ . '/../App/Views/components/_footer.php';
}

// Ret dato i ansøngninger til dansk
function formatDanishDate(string $date): string
{
    $months = [
        'Jan' => 'jan',
        'Feb' => 'feb',
        'Mar' => 'mar',
        'Apr' => 'apr',
        'May' => 'maj',
        'Jun' => 'jun',
        'Jul' => 'jul',
        'Aug' => 'aug',
        'Sep' => 'sep',
        'Oct' => 'okt',
        'Nov' => 'nov',
        'Dec' => 'dec'
    ];

    return strtr(date('d M', strtotime($date)), $months);
}
