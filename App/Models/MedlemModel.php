<?php

require_once __DIR__ . '/../../private/db.php';

class MedlemModel {
    public static function getVisibleMembers(): array {
        $db = getDB();

        $stmt = $db->query("
            SELECT
                u.user_pk AS id,
                u.user_name,
                u.user_last_name,
                u.user_email,
                u.user_profile_image,
                u.role_fk,
                m.member_pk,
                m.education_fk,
                e.education_name,
                s.semester_number
            FROM users u
            LEFT JOIN members m 
                ON u.user_pk = m.user_fk
                AND m.status = 'approved'
                AND m.deleted_at IS NULL
            LEFT JOIN educations e ON m.education_fk = e.education_pk
            LEFT JOIN semesters s ON m.semester_fk = s.semester_pk
            WHERE u.user_deleted_at IS NULL
                AND (
                    u.role_fk = '1'
                    OR m.status = 'approved'
                )
            ORDER BY u.role_fk ASC, u.user_name ASC
        ");

        return $stmt->fetchAll();
    }

    public static function getStats(): array {
        $db = getDB();

        $stmt = $db->query("
            SELECT
                (
                    SELECT COUNT(*)
                    FROM members m
                    INNER JOIN users u ON m.user_fk = u.user_pk
                    WHERE m.status = 'approved'
                        AND m.deleted_at IS NULL
                        AND u.user_deleted_at IS NULL
                ) AS active_members,

                (
                    SELECT COUNT(*)
                    FROM users
                    WHERE role_fk = '1'
                        AND user_deleted_at IS NULL
                ) AS board_members,

                (
                    SELECT COUNT(*)
                    FROM events
                    WHERE YEAR(event_date) = YEAR(CURDATE())
                ) AS events_this_year
        ");

        return $stmt->fetch();
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
                u.user_email,
                u.user_profile_image
            FROM members m
            INNER JOIN users u ON m.user_fk = u.user_pk
            LEFT JOIN educations e ON m.education_fk = e.education_pk
            LEFT JOIN semesters s ON m.semester_fk = s.semester_pk
            WHERE m.status = 'pending'
                AND m.deleted_at IS NULL
                AND u.user_deleted_at IS NULL
            ORDER BY m.applied_at DESC
        ");

        return $stmt->fetchAll();
    }

    public static function createApplication(
        int $userId,
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

    public static function hasApplication(int $userId): bool
    {
        $db = getDB();

        $stmt = $db->prepare("
            SELECT member_pk
            FROM members
            WHERE user_fk = ?
            AND deleted_at IS NULL
            LIMIT 1
        ");

        $stmt->execute([$userId]);

        return (bool) $stmt->fetch();
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

    // Til at sende godkendelses-mail
    public static function getApplicationById(string $memberId): ?array
    {
        $db = getDB();

        $stmt = $db->prepare("
            SELECT
                m.member_pk,
                u.user_name,
                u.user_email
            FROM members m
            INNER JOIN users u ON m.user_fk = u.user_pk
            WHERE m.member_pk = ?
                AND m.deleted_at IS NULL
            LIMIT 1
        ");

        $stmt->execute([$memberId]);

        $application = $stmt->fetch();

        return $application ?: null;
    }

    public static function getUserProfileImage(int $userId): ?string
    {
        $db = getDB();

        $stmt = $db->prepare("
            SELECT user_profile_image
            FROM users
            WHERE user_pk = ?
            LIMIT 1
        ");

        $stmt->execute([$userId]);

        $image = $stmt->fetchColumn();

        return $image ?: null;
    }

    public static function updateUserProfileImage(int $userId, string $imageName): bool
        {
            $db = getDB();

            $stmt = $db->prepare("
                UPDATE users
                SET user_profile_image = :image
                WHERE user_pk = :user_id
            ");

            return $stmt->execute([
                ':image' => $imageName,
                ':user_id' => $userId
            ]);
        }

    public static function approve(string $memberId, string $adminId): bool {
        $db = getDB();

        $stmt = $db->prepare("
            UPDATE members
            SET status = 'approved',
                approved_by_fk = ?,
                approved_at = NOW()
            WHERE member_pk = ?
                AND deleted_at IS NULL
        ");

        return $stmt->execute([$adminId, $memberId]);
    }

    public static function reject(string $memberId): bool {
        $db = getDB();

        $stmt = $db->prepare("
            UPDATE members
            SET status = 'rejected'
            WHERE member_pk = ?
                AND deleted_at IS NULL
        ");

        return $stmt->execute([$memberId]);
    }

    public static function delete(string $memberId): bool {
        $db = getDB();

        $stmt = $db->prepare("
            UPDATE members
            SET deleted_at = NOW()
            WHERE member_pk = ?
                AND deleted_at IS NULL
        ");

        return $stmt->execute([$memberId]);
    }
}