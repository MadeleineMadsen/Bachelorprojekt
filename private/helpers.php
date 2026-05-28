<?php

// Sikrer output mod XSS
function e($value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Redirecter brugeren til en ny side
function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

// Henter og sletter flash beskeder
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

// Opretter eller henter CSRF token
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// Udskriver hidden input med CSRF token
function csrf_input(): void
{
    echo '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

// Validerer CSRF token fra POST request
function csrf_verify(): bool
{
    return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

// Kræver gyldigt CSRF token på POST routes
function require_csrf(): void
{
    if (!csrf_verify()) {
        $_SESSION['error'] = 'Ugyldig formular. Prøv igen.';
        redirect('/');
    }
}

// Tjekker om brugeren er logget ind
function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

// Henter brugerens rolle
function user_role(): ?int
{
    return $_SESSION['user']['role_fk'] ?? null;
}

// Rolle checks
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

// Kræver at brugeren er logget ind
function require_login(): void
{
    if (!is_logged_in()) {
        redirect('/login');
    }
}

// Kræver en bestemt rolle
function require_role(int $role, string $redirectTo = '/'): void
{
    require_login();

    if (user_role() !== $role) {
        redirect($redirectTo);
    }
}

// Rollebeskyttelse

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

// Validerer almindelige tekstfelter
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

// Validerer email
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

// Validerer adgangskode
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

// Valider ID fra POST data
function validate_post_id(string $field): int
{
    $id = (int) ($_POST[$field] ?? 0);

    if ($id <= 0) {
        throw new Exception('Ugyldigt ID.');
    }

    return $id;
}

// Loader view med header og footer
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

// Formaterer dato til dansk visning
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

// Validerer uploadet billede
function validate_image_upload(
    string $field,
    int $maxSize = 5 * 1024 * 1024
): ?array {
    if (
        !isset($_FILES[$field]) ||
        $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE
    ) {
        return null;
    }

    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Der opstod en fejl under upload af billedet.');
    }

    if ($_FILES[$field]['size'] > $maxSize) {
        throw new Exception('Billedet må maks være 5 MB.');
    }

    $tmpName = $_FILES[$field]['tmp_name'];
    $originalName = $_FILES[$field]['name'];

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions, true)) {
        throw new Exception('Billedet skal være JPG, PNG eller WEBP.');
    }

    $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    $mimeType = mime_content_type($tmpName);

    if (!in_array($mimeType, $allowedMimes, true)) {
        throw new Exception('Filen skal være et gyldigt billede.');
    }

    if (getimagesize($tmpName) === false) {
        throw new Exception('Filen kunne ikke genkendes som et billede.');
    }

    $newFileName = bin2hex(random_bytes(16)) . '.' . $extension;

    return [
        'tmp_name' => $tmpName,
        'file_name' => $newFileName,
        'extension' => $extension,
        'mime_type' => $mimeType
    ];
}
