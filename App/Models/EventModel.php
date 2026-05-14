<?php

require_once __DIR__ . '/../../private/db.php';

class EventModel {

    public static function getAllCategories(): array {
        $db = getDB();
        return $db->query('SELECT * FROM event_categories ORDER BY category_name ASC')->fetchAll();
    }

    public static function getAll(): array {
        $db   = getDB();
        $stmt = $db->query('
            SELECT e.*, c.category_name, COUNT(r.registration_pk) AS participant_count
            FROM events e
            LEFT JOIN event_categories c ON c.category_pk = e.category_fk
            LEFT JOIN event_registrations r ON r.event_fk = e.event_pk
            GROUP BY e.event_pk
            ORDER BY e.event_date ASC
        ');
        return $stmt->fetchAll();
    }

    public static function getById(string $id): array|false {
        $db   = getDB();
        $stmt = $db->prepare('
            SELECT e.*, c.category_name, COUNT(r.registration_pk) AS participant_count
            FROM events e
            LEFT JOIN event_categories c ON c.category_pk = e.category_fk
            LEFT JOIN event_registrations r ON r.event_fk = e.event_pk
            WHERE e.event_pk = ?
            GROUP BY e.event_pk
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getByUserId(int $userId): array {
        $db   = getDB();
        $stmt = $db->prepare('
            SELECT e.*, c.category_name, COUNT(r2.registration_pk) AS participant_count
            FROM event_registrations r
            JOIN events e ON e.event_pk = r.event_fk
            LEFT JOIN event_categories c ON c.category_pk = e.category_fk
            LEFT JOIN event_registrations r2 ON r2.event_fk = e.event_pk
            WHERE r.user_fk = ?
            GROUP BY e.event_pk
            ORDER BY e.event_date ASC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function isRegistered(string $eventId, int $userId): bool {
        $db   = getDB();
        $stmt = $db->prepare('SELECT 1 FROM event_registrations WHERE event_fk = ? AND user_fk = ?');
        $stmt->execute([$eventId, $userId]);
        return (bool) $stmt->fetch();
    }

    public static function registerUser(string $eventId, int $userId): void {
        $uuid = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
        $db   = getDB();
        $stmt = $db->prepare('INSERT IGNORE INTO event_registrations (registration_pk, event_fk, user_fk) VALUES (?, ?, ?)');
        $stmt->execute([$uuid, $eventId, $userId]);
    }

    public static function unregisterUser(string $eventId, int $userId): void {
        $db   = getDB();
        $stmt = $db->prepare('DELETE FROM event_registrations WHERE event_fk = ? AND user_fk = ?');
        $stmt->execute([$eventId, $userId]);
    }

    public static function getParticipantsByEventId(string $id): array {
        $db   = getDB();
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

    public static function delete(string $id): void {
        $db = getDB();

        $stmt = $db->prepare("
            UPDATE events
            SET deleted_at = NOW()
            WHERE event_pk = ?
        ");

        $stmt->execute([$id]);
    }

    public static function create(array $data): void {
        $db   = getDB();
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

    public static function update(array $data): void {
        $db   = getDB();
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
        if ($data['event_image'] !== null) {
            $params[] = $data['event_image'];
        }
        $params[] = $data['event_pk'];
        $stmt->execute($params);
    }

    public static function getLatest(int $limit = 3): array {
        $db   = getDB();
        $stmt = $db->prepare('
            SELECT e.*, COUNT(r.registration_pk) AS participant_count
            FROM events e
            LEFT JOIN event_registrations r ON r.event_fk = e.event_pk
            GROUP BY e.event_pk
            ORDER BY e.event_date ASC
            LIMIT ?
        ');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getEventsNeedingReminder(): array
    {
        $db = getDB();

        $stmt = $db->prepare("
            SELECT *
            FROM events
            WHERE event_deleted_at IS NULL
            AND reminder_sent_at IS NULL
            AND TIMESTAMP(event_date, event_time) BETWEEN NOW() + INTERVAL 23 HOUR
            AND NOW() + INTERVAL 25 HOUR
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }

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
