<?php
// db.php
// Opretter PDO databaseforbindelse
// Bruges i models til at kommunikere med databasen

function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=mariadb;dbname=bachelor;charset=utf8mb4',
            'root',
            'password',
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    return $pdo;
}
