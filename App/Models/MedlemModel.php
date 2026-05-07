<?php

require_once __DIR__ . '/../../private/db.php';

class MedlemModel {
    public static function getApproved(): array {
        $db = getDB();

        $stmt = $db->query("
            SELECT
                m.member_pk,
                m.education_fk,
                m.semester_fk,
                e.education_name,
                s.semester_number,
                m.application_text,
                m.applied_at,
                u.user_name,
                u.user_last_name,
                u.user_email
            FROM members m
            INNER JOIN users u ON m.user_fk = u.user_pk
            LEFT JOIN educations e ON m.education_fk = e.education_pk
            LEFT JOIN semesters s ON m.semester_fk = s.semester_pk
            WHERE m.status = 'approved'
                AND m.deleted_at = 0
                AND u.user_deleted_at = 0
            ORDER BY u.user_name ASC
        ");

        return $stmt->fetchAll();
    }

    public static function getPending(): array {
        $db = getDB();

        $stmt = $db->query("
            SELECT
                m.member_pk,
                m.education_fk,
                m.semester_fk,
                e.education_name,
                s.semester_number,
                m.application_text,
                m.applied_at,
                u.user_name,
                u.user_last_name,
                u.user_email
            FROM members m
            INNER JOIN users u ON m.user_fk = u.user_pk
            LEFT JOIN educations e ON m.education_fk = e.education_pk
            LEFT JOIN semesters s ON m.semester_fk = s.semester_pk
            WHERE m.status = 'pending'
                AND m.deleted_at = 0
                AND u.user_deleted_at = 0
            ORDER BY m.applied_at DESC
        ");

        return $stmt->fetchAll();
    }

    public static function createApplication(
        string $userId,
        int $educationFk,
        int $semesterFk,
        string $applicationText
    ): bool {
        $db = getDB();

        $stmt = $db->prepare("
            INSERT INTO members
                (member_pk, user_fk, education_fk, semester_fk, application_text, status)
            VALUES
                (UUID(), ?, ?, ?, ?, 'pending')
        ");

        return $stmt->execute([
            $userId,
            $educationFk,
            $semesterFk,
            $applicationText
        ]);
    }

    public static function getEducations(): array
    {
        $db = getDB();

        $stmt = $db->query("
            SELECT *
            FROM educations
            ORDER BY education_name ASC
        ");

        return $stmt->fetchAll();
    }

    public static function getSemesters(): array
    {
        $db = getDB();

        $stmt = $db->query("
            SELECT *
            FROM semesters
            ORDER BY semester_number ASC
        ");

        return $stmt->fetchAll();
    }

    public static function approve(string $memberId, string $adminId): bool {
        $db = getDB();

        $stmt = $db->prepare("
            UPDATE members
            SET status = 'approved',
                approved_by_fk = ?,
                approved_at = NOW()
            WHERE member_pk = ?
                AND deleted_at = 0
        ");

        return $stmt->execute([$adminId, $memberId]);
    }

    public static function reject(string $memberId): bool {
        $db = getDB();

        $stmt = $db->prepare("
            UPDATE members
            SET status = 'rejected'
            WHERE member_pk = ?
                AND deleted_at = 0
        ");

        return $stmt->execute([$memberId]);
    }

    public static function delete(string $memberId): bool {
        $db = getDB();

        $stmt = $db->prepare("
            UPDATE members
            SET deleted_at = UNIX_TIMESTAMP()
            WHERE member_pk = ?
                AND deleted_at = 0
        ");

        return $stmt->execute([$memberId]);
    }
}