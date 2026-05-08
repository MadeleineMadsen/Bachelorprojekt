<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

// Send bekræftelsesmail efter ansøgning
function sendMembershipConfirmationMail(string $toEmail, string $firstName): bool
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'kamiweb1031@gmail.com';
        $mail->Password = 'sjiw chwn saxm qous';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('kamiweb1031@gmail.com', 'GBG Social');
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