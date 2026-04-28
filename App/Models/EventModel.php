<?php

require_once __DIR__ . '/../../private/db.php';

class EventModel {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(): array {
        $stmt = $this->db->query('
            SELECT e.*, COUNT(r.registration_pk) AS participant_count
            FROM events e
            LEFT JOIN event_registrations r ON r.event_fk = e.event_pk
            GROUP BY e.event_pk
            ORDER BY e.event_date ASC
        ');
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare('
            SELECT e.*, COUNT(r.registration_pk) AS participant_count
            FROM events e
            LEFT JOIN event_registrations r ON r.event_fk = e.event_pk
            WHERE e.event_pk = ?
            GROUP BY e.event_pk
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getLatest(int $limit = 3): array {
        $stmt = $this->db->prepare('
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
