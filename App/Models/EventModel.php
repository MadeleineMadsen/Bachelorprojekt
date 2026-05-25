<?php

// Importerer databaseforbindelsen
require_once __DIR__ . '/../../private/db.php';

class EventModel
{

    /* ==================================================
    KATEGORIER
    ================================================== */

    // Henter alle event-kategorier alfabetisk
    public static function getAllCategories(): array
    {
        $db = getDB();
        return $db->query('SELECT * FROM event_categories ORDER BY category_name ASC')->fetchAll();
    }

    /* ==================================================
    HENT EVENTS
    ================================================== */

    // Henter alle aktive events inkl. kategori og antal deltagere
    public static function getAll(): array
    {
        $db = getDB();
        $stmt = $db->query('
            SELECT e.*, c.category_name, COUNT(r.registration_pk) AS participant_count
            FROM events e
            LEFT JOIN event_categories c ON c.category_pk = e.category_fk
            LEFT JOIN event_registrations r ON r.event_fk = e.event_pk
            WHERE e.deleted_at IS NULL
            GROUP BY e.event_pk
            ORDER BY e.event_date ASC
        ');
        return $stmt->fetchAll();
    }

    // Henter ét aktivt event ud fra event-id
    public static function getById(string $id): array|false
    {
        $db = getDB();
        $stmt = $db->prepare('
            SELECT e.*, c.category_name, COUNT(r.registration_pk) AS participant_count
            FROM events e
            LEFT JOIN event_categories c ON c.category_pk = e.category_fk
            LEFT JOIN event_registrations r ON r.event_fk = e.event_pk
            WHERE e.event_pk = ?
            AND e.deleted_at IS NULL
            GROUP BY e.event_pk
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Henter alle events som en bestemt bruger er tilmeldt
    public static function getByUserId(int $userId): array
    {
        $db = getDB();
        $stmt = $db->prepare('
            SELECT e.*, c.category_name, COUNT(r2.registration_pk) AS participant_count
            FROM event_registrations r
            JOIN events e ON e.event_pk = r.event_fk
            LEFT JOIN event_categories c ON c.category_pk = e.category_fk
            LEFT JOIN event_registrations r2 ON r2.event_fk = e.event_pk
            WHERE r.user_fk = ?
            AND e.deleted_at IS NULL
            GROUP BY e.event_pk
            ORDER BY e.event_date ASC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Henter de nyeste/kommende events til fx forsiden
    public static function getLatest(int $limit = 3): array
    {
        $db = getDB();
        $stmt = $db->prepare('
            SELECT e.*, COUNT(r.registration_pk) AS participant_count
            FROM events e
            LEFT JOIN event_registrations r ON r.event_fk = e.event_pk
            WHERE e.deleted_at IS NULL
            GROUP BY e.event_pk
            ORDER BY e.event_date ASC
            LIMIT ?
        ');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /* ==================================================
    TILMELDING / AFMELDING
    ================================================== */

    // Tjekker om en bruger allerede er tilmeldt et event
    public static function isRegistered(string $eventId, int $userId): bool
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT 1 FROM event_registrations WHERE event_fk = ? AND user_fk = ?');
        $stmt->execute([$eventId, $userId]);
        return (bool) $stmt->fetch();
    }

    // Tilmelder en bruger til et event
    public static function registerUser(string $eventId, int $userId): void
    {
        $uuid = bin2hex(random_bytes(16));
        $db = getDB();
        $stmt = $db->prepare('INSERT IGNORE INTO event_registrations (registration_pk, event_fk, user_fk) VALUES (?, ?, ?)');
        $stmt->execute([$uuid, $eventId, $userId]);
    }

    // Afmelder en bruger fra et event
    public static function unregisterUser(string $eventId, int $userId): void
    {
        $db = getDB();
        $stmt = $db->prepare('DELETE FROM event_registrations WHERE event_fk = ? AND user_fk = ?');
        $stmt->execute([$eventId, $userId]);
    }

    // Henter alle deltagere til et bestemt event
    public static function getParticipantsByEventId(string $id): array
    {
        $db = getDB();
        $stmt = $db->prepare('
            SELECT u.user_pk, u.user_name, u.user_last_name, u.user_profile_image
            FROM event_registrations r
            JOIN users u ON u.user_pk = r.user_fk
            WHERE r.event_fk = ?
            ORDER BY r.registered_at ASC
        ');
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    /* ==================================================
    OPRET / OPDATER / SLET
    ================================================== */

    // Soft-deleter et event ved at sætte deleted_at
    public static function delete(string $id): void
    {
        $db = getDB();

        $stmt = $db->prepare("
            UPDATE events
            SET deleted_at = NOW()
            WHERE event_pk = ?
        ");

        $stmt->execute([$id]);
    }

    // Opretter et nyt event
    public static function create(array $data): void
    {
        $db = getDB();
        $stmt = $db->prepare('
            INSERT INTO events (event_pk, event_title, event_subtitle, event_description, event_expectations, event_date, event_time, event_end_time, event_location, category_fk, event_image, created_by_fk)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $data['event_pk'],
            $data['event_title'],
            $data['event_subtitle'],
            $data['event_description'],
            $data['event_expectations'],
            $data['event_date'],
            $data['event_time'],
            $data['event_end_time'],
            $data['event_location'],
            $data['category_fk'],
            $data['event_image'],
            $data['created_by_fk'],
        ]);
    }

    // Opdaterer et eksisterende event
    public static function update(array $data): void
    {
        $db = getDB();

        // Tilføjer kun event_image til SQL'en hvis der er uploadet et nyt billede
        $stmt = $db->prepare('
            UPDATE events
            SET event_title = ?, event_subtitle = ?, event_description = ?, event_expectations = ?,
                event_date = ?, event_time = ?, event_end_time = ?, event_location = ?, category_fk = ?
                ' . ($data['event_image'] !== null ? ', event_image = ?' : '') . '
            WHERE event_pk = ?
        ');
        $params = [
            $data['event_title'],
            $data['event_subtitle'],
            $data['event_description'],
            $data['event_expectations'],
            $data['event_date'],
            $data['event_time'],
            $data['event_end_time'],
            $data['event_location'],
            $data['category_fk'],
        ];

        // Tilføjer billedsti til parameterlisten hvis billedet skal opdateres
        if ($data['event_image'] !== null) {
            $params[] = $data['event_image'];
        }
        $params[] = $data['event_pk'];
        $stmt->execute($params);
    }

    /* ==================================================
    REMINDERS
    ================================================== */

    // Henter events der starter om cirka 24 timer og endnu ikke har fået reminder sendt
    public static function getEventsNeedingReminder(): array
    {
        $db = getDB();

        $stmt = $db->prepare("
            SELECT *
            FROM events
            WHERE deleted_at IS NULL
            AND reminder_sent_at IS NULL
            AND TIMESTAMP(event_date, event_time) BETWEEN NOW() + INTERVAL 23 HOUR
            AND NOW() + INTERVAL 25 HOUR
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Marker et event som reminder sendt
    public static function markReminderAsSent(string $eventId): bool
    {
        $db = getDB();

        $stmt = $db->prepare("
            UPDATE events
            SET reminder_sent_at = NOW()
            WHERE event_pk = :event_pk
        ");

        return $stmt->execute([
            ':event_pk' => $eventId
        ]);
    }
}
