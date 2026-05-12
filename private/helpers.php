<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
$env = parse_ini_file(__DIR__ . '/.env');

// Send bekræftelsesmail efter ansøgning
function sendMembershipConfirmationMail(string $toEmail, string $firstName): bool
{
    global $env;
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = $env['SMTP_EMAIL'];
        $mail->Password = $env['SMTP_PASSWORD'];

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($env['SMTP_EMAIL'], 'GBG Social');
        $mail->addAddress($toEmail, $firstName);

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(false);

        $mail->Subject = 'Vi har modtaget din ansøgning';
        $mail->Body = "Hej {$firstName}

    Tak for din ansøgning om medlemskab hos GBG Social.

    Vi har modtaget din ansøgning og vender tilbage hurtigst muligt.

    Venlig hilsen
    GBG Social";

        return $mail->send();

    } catch (Exception $e) {
        error_log('Mailfejl: ' . $mail->ErrorInfo);
        return false;
    }
}

// Send mail når ansøgning er godkendt
function sendMembershipApprovedMail(string $toEmail, string $firstName): bool
{
    global $env;
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = $env['SMTP_EMAIL'];
        $mail->Password = $env['SMTP_PASSWORD'];

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($env['SMTP_EMAIL'], 'GBG Social');
        $mail->addAddress($toEmail, $firstName);

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(false);

        $mail->Subject = 'Tillykke - du er nu medlem af GBG Social';
        $mail->Body = "Hej {$firstName}

    Tillykke! Din ansøgning er blevet godkendt.

    Du er nu medlem og vejleder hos GBG Social og kan være med til at skabe fællesskab og gode oplevelser for andre studerende.

    Husk at gå ind på eventsiden og tilmelde dig de events, du gerne vil være vejleder på:
    http://localhost/events

    Venlig hilsen
    GBG Social";

        return $mail->send();

    } catch (Exception $e) {
        error_log('Mailfejl: ' . $mail->ErrorInfo);
        return false;
    }
}

// Send mail når ansøgning er afvist
function sendMembershipRejectedMail(string $toEmail, string $firstName): bool
{
    global $env;
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = $env['SMTP_EMAIL'];
        $mail->Password = $env['SMTP_PASSWORD'];

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($env['SMTP_EMAIL'], 'GBG Social');
        $mail->addAddress($toEmail, $firstName);

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(false);

        $mail->Subject = 'Svar på din ansøgning hos GBG Social';

        $mail->Body = "Hej {$firstName}

    Tak for din ansøgning om at blive vejleder hos GBG Social.

    Vi har desværre valgt ikke at godkende din ansøgning som vejleder denne gang.

    Du er altid velkommen til at ansøge igen senere!

    Venlig hilsen
    GBG Social";

        return $mail->send();

    } catch (Exception $e) {
        error_log('Mailfejl: ' . $mail->ErrorInfo);
        return false;
    }
}

//  Send mail når man slettes som medlem af admin
function sendMembershipRemovedMail(string $toEmail, string $firstName): bool
{
    global $env;
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = $env['SMTP_EMAIL'];
        $mail->Password = $env['SMTP_PASSWORD'];

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($env['SMTP_EMAIL'], 'GBG Social');
        $mail->addAddress($toEmail, $firstName);

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(false);

        $mail->Subject = 'Du er blevet fjernet som medlem hos GBG Social';

        $mail->Body = "Hej {$firstName}

Du er desværre blevet fjernet som medlem hos GBG Social.

Det betyder, at du ikke længere har adgang som medlem på siden.

Hvis du mener, at dette er en fejl, er du meget velkommen til at kontakte os.

Venlig hilsen
GBG Social";

        return $mail->send();

    } catch (Exception $e) {
        error_log('Mailfejl: ' . $mail->ErrorInfo);
        return false;
    }
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

// Send verifikationsmail når bruger opretter sig
function sendUserVerificationMail(string $toEmail, string $firstName, string $verificationKey): bool
{
    global $env;
    $mail = new PHPMailer(true);

    $verificationLink = "http://localhost/verificer_bruger?key=" . urlencode($verificationKey);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = $env['SMTP_EMAIL'];
        $mail->Password = $env['SMTP_PASSWORD'];

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($env['SMTP_EMAIL'], 'GBG Social');
        $mail->addAddress($toEmail, $firstName);

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(false);

        $mail->Subject = 'Bekræft din bruger hos GBG Social';

        $mail->Body = "Hej {$firstName}

Tak fordi du har oprettet dig hos GBG Social.

Klik på linket herunder for at bekræfte din bruger:

{$verificationLink}

Når du har bekræftet din mail, kan du logge ind.

Venlig hilsen
GBG Social";

        return $mail->send();

    } catch (Exception $e) {
        error_log('Mailfejl: ' . $mail->ErrorInfo);
        return false;
    }
}