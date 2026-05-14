<?php

require_once __DIR__ . '/../vendor/autoload.php';
$env = parse_ini_file(__DIR__ . '/.env');


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
