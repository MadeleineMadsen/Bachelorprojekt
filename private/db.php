<?php

// Opretter forbindelse til databasen
function getDB(): PDO
{
    // Gemmer forbindelsen så den kun oprettes én gang
    static $pdo = null;

    // Opretter forbindelse hvis den ikke allerede findes
    if ($pdo === null) {

        // Henter databaseoplysninger fra .env fil
        $env = parse_ini_file(__DIR__ . '/.env');

        if ($env === false) {
            throw new RuntimeException('.env kunne ikke indlæses');
        }

        // Opretter PDO forbindelse
        $pdo = new PDO(
            'mysql:host=' . $env['DB_HOST'] . ';dbname=' . $env['DB_NAME'] . ';charset=utf8mb4',
            $env['DB_USER'],
            $env['DB_PASSWORD'],
            [
                // Aktiverer fejl som exceptions
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

                // Returnerer data som associative arrays
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    // Returnerer databaseforbindelsen
    return $pdo;
}