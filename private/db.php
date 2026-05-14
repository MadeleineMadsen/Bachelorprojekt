<?php

function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $env = parse_ini_file(__DIR__ . '/.env');

        $pdo = new PDO(
            'mysql:host=' . $env['DB_HOST'] . ';dbname=' . $env['DB_NAME'] . ';charset=utf8mb4',
            $env['DB_USER'],
            $env['DB_PASSWORD'],
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    return $pdo;
}