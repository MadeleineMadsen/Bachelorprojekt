<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
$env = parse_ini_file(__DIR__ . '/.env');

if ($env === false) {
    throw new RuntimeException('.env kunne ikke indlæses');
}

$appUrl = rtrim($env['APP_URL'], '/');

// Global mail-helper
function sendMail(string $toEmail, string $firstName, string $subject, string $body): bool
{
    global $env;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $env['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $env['SMTP_EMAIL'];
        $mail->Password = $env['SMTP_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port = (int)$env['SMTP_PORT'];

        $mail->setFrom($env['SMTP_EMAIL'], 'GBG Social');
        $mail->addAddress($toEmail, $firstName);

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(false);

        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();

    } catch (Exception $e) {
        error_log('Mailfejl: ' . $mail->ErrorInfo);
        return false;
    }
}

// Send verifikationsmail når bruger opretter sig
function sendUserVerificationMail(string $toEmail, string $firstName, string $verificationKey): bool
{
    global $appUrl;
	$verificationLink = $appUrl . '/verify_user?key=' . urlencode($verificationKey);
    
    return sendMail(
        $toEmail,
        $firstName,
        'Velkommen til GBG Social – bekræft din mailadresse',
        "Hej {$firstName}

Tak fordi du har oprettet en profil hos GBG Social. Vi er glade for at have dig med.

For at aktivere din konto bedes du bekræfte din mailadresse ved at klikke på linket herunder:

{$verificationLink}

Når du har bekræftet din mail, er du klar til at logge ind og udforske vores events.

Har du ikke oprettet en profil hos os, kan du se bort fra denne mail.

Vi glæder os til at se dig til et event.

Venlig hilsen
GBG Social"
    );
}

// Send bekræftelsesmail efter ansøgning
function sendMembershipConfirmationMail(string $toEmail, string $firstName): bool
{
    return sendMail(
        $toEmail,
        $firstName,
        'Vi har modtaget din ansøgning – GBG Social',
        "Hej {$firstName}

Tak for din ansøgning om at blive vejleder hos GBG Social.

Vi har modtaget din ansøgning og gennemgår den hurtigst muligt. Du vil høre fra os, så snart vi har truffet en beslutning.

I mellemtiden er du selvfølgelig velkommen til at kigge forbi vores events.

Venlig hilsen
GBG Social"
    );
}

// Send mail når ansøgning er godkendt
function sendMembershipApprovedMail(string $toEmail, string $firstName): bool
{
    global $appUrl;

    return sendMail(
        $toEmail,
        $firstName,
        'Din ansøgning er godkendt – velkommen som medlem',
        "Hej {$firstName}

Vi er glade for at kunne fortælle dig, at din ansøgning er blevet godkendt. Du er nu medlem af GBG Social.

Som medlem er du med til at skabe gode oplevelser og fællesskab for studerende på tværs af uddannelserne. Det sætter vi stor pris på.

Vi glæder os til at se dig.

Venlig hilsen
GBG Social"
    );
}

// Send mail når ansøgning er afvist
function sendMembershipRejectedMail(string $toEmail, string $firstName): bool
{
    return sendMail(
        $toEmail,
        $firstName,
        'Svar på din ansøgning – GBG Social',
        "Hej {$firstName}

Vi har desværre ikke mulighed for at optage dig som medlem på nuværende tidspunkt. 

Du kan stadig deltage i vores events som studerende og være en del af fællesskabet.

Tak fordi du viste interesse for GBG Social.

Venlig hilsen
GBG Social"
    );
}

//  Send mail når man slettes som medlem af admin
function sendMembershipRemovedMail(string $toEmail, string $firstName): bool
{
    return sendMail(
        $toEmail,
        $firstName,
        'Du er blevet fjernet som medlem hos GBG Social',
        "Hej {$firstName}

Du er desværre blevet fjernet som medlem hos GBG Social.

Det betyder, at du ikke længere har adgang som medlem på siden.

Hvis du mener, at dette er en fejl, er du meget velkommen til at kontakte os.

Venlig hilsen
GBG Social"
    );
}

//  Send mail når man deltager i event
function sendEventConfirmMail(string $toEmail, string $firstName, string $eventTitle): bool
{
    return sendMail(
        $toEmail,
        $firstName,
        'Du er nu tilmeldt event hos GBG Social',
        "Hej {$firstName}

Tak for din tilmelding til eventet {$eventTitle}.

Vi glæder os til at se dig til en hyggelig og social oplevelse sammen med resten af GBG Social.

Du kan altid finde information om eventet på eventsiden.

Hvis du bliver forhindret i at deltage, beder vi dig melde afbud hurtigst muligt.

Vi ses!

Venlig hilsen
GBG Social"
    );
}

//  Send mail når man afmelder sig et event
function sendEventRemoveMail(string $toEmail, string $firstName, string $eventTitle): bool
{
    return sendMail(
        $toEmail,
        $firstName,
        'Du er nu afmeldt event hos GBG Social',
        "Hej {$firstName}

Du er nu blevet afmeldt eventet {$eventTitle}.

Vi er kede af, at du ikke længere kan deltage, men håber at se dig til et andet event hos GBG Social snart.

Du kan altid finde kommende events på eventsiden.

Venlig hilsen
GBG Social"
    );
}

// Send oplåsningsmail når konto er låst
function sendAccountUnlockMail(string $toEmail, string $firstName, string $unlockKey): bool
{
    global $appUrl;
    $unlockLink = $appUrl . '/unlock_account?key=' . urlencode($unlockKey);

    return sendMail(
        $toEmail,
        $firstName,
        'Lås din konto op hos GBG Social',
        "Hej {$firstName}

Din konto hos GBG Social er blevet låst, da der er foretaget for mange fejlede loginforsøg.

Klik på linket herunder for at låse din konto op igen:

{$unlockLink}

Hvis det ikke var dig, der forsøgte at logge ind, kan du ignorere denne mail.

Venlig hilsen
GBG Social"
    );
}

//  Send mail når event opdateres af admin
function sendEventUpdatedMail(string $toEmail, string $firstName, string $eventTitle): bool
{
    global $appUrl;

    return sendMail(
        $toEmail,
        $firstName,
        'Et event du er tilmeldt er blevet opdateret',
        "Hej {$firstName}

Eventet {$eventTitle}, som du er tilmeldt, er blevet opdateret.

Der kan være ændringer i fx tidspunkt, lokation eller beskrivelse.

Du kan se de opdaterede informationer under Mine Events på hjemmesiden.

Link:
{$appUrl}/my_events

Venlig hilsen
GBG Social"
    );
}

//  Send mail når event slettes af admin
function sendEventDeletedMail(string $toEmail, string $firstName, string $eventTitle): bool
{
    return sendMail(
        $toEmail,
        $firstName,
        'Eventet er blevet aflyst hos GBG Social',
        "Hej {$firstName}

Vi er desværre nødt til at aflyse eventet {$eventTitle}.

Vi beklager ulejligheden og håber at se dig til et andet event hos GBG Social snart.

Du kan altid holde øje med kommende events på eventsiden.

Venlig hilsen
GBG Social"
    );
}

// Send bekræftelsesmail når bruger sletter sin konto
function sendAccountDeletedMail(string $toEmail, string $firstName): bool
{
    return sendMail(
        $toEmail,
        $firstName,
        'Din konto hos GBG Social er blevet slettet',
        "Hej {$firstName}

Vi bekræfter hermed, at din konto hos GBG Social er blevet slettet.

Dine data er fjernet fra vores system, og du kan ikke længere logge ind.

Hvis du fortryder, eller mener dette er sket ved en fejl, er du velkommen til at kontakte os.

Venlig hilsen
GBG Social"
    );
}

// Send mail med link til at nulstille adgangskode
function sendPasswordResetMail(string $toEmail, string $firstName, string $resetKey): bool
{
    global $appUrl;
    $resetLink = $appUrl . '/reset_password?key=' . urlencode($resetKey);
    
    return sendMail(
        $toEmail,
        $firstName,
        'Nulstil din adgangskode hos GBG Social',
        "Hej {$firstName}

Vi har modtaget en anmodning om at nulstille adgangskoden til din konto hos GBG Social.

Klik på linket herunder for at vælge en ny adgangskode:

{$resetLink}

Linket udløber om 1 time.

Hvis det ikke var dig, der anmodede om at nulstille din adgangskode, kan du ignorere denne mail.

Venlig hilsen
GBG Social"
    );
}

// Send mail 24 timer før eventet finder sted
function sendEventReminderMail(string $toEmail, string $firstName, string $eventTitle): bool
{
    return sendMail(
        $toEmail,
        $firstName,
        'Reminder: Dit event starter i morgen',
        "Hej {$firstName}

Dette er en venlig reminder om, at eventet {$eventTitle} starter i morgen.

Vi glæder os til at se dig!

Du kan finde mere information under Mine events på hjemmesiden.

Venlig hilsen
GBG Social"
    );
}