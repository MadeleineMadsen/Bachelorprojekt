<?php

require_once __DIR__ . '/../../private/db.php';

class EventModel {

    public static function getAll(): array {
        $db   = getDB();
        $stmt = $db->query('
            SELECT e.*, COUNT(r.registration_pk) AS participant_count
            FROM events e
            LEFT JOIN event_registrations r ON r.event_fk = e.event_pk
            GROUP BY e.event_pk
            ORDER BY e.event_date ASC
        ');
        return $stmt->fetchAll();
    }

    public static function getById(string $id): array|false {
        $db   = getDB();
        $stmt = $db->prepare('
            SELECT e.*, COUNT(r.registration_pk) AS participant_count
            FROM events e
            LEFT JOIN event_registrations r ON r.event_fk = e.event_pk
            WHERE e.event_pk = ?
            GROUP BY e.event_pk
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
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
}
