<?php

class UserModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(
        string $userName,
        string $lastName,
        string $email,
        string $password,
        int $roleFk = 2
    ): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "INSERT INTO users 
                (user_name, user_last_name, user_email, user_password, role_fk)
            VALUES 
                (:user_name, :user_last_name, :user_email, :user_password, :role_fk)"
        );

        return $stmt->execute([
            ':user_name' => $userName,
            ':user_last_name' => $lastName,
            ':user_email' => $email,
            ':user_password' => $hashedPassword,
            ':role_fk' => $roleFk
        ]);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE user_email = :user_email LIMIT 1"
        );

        $stmt->execute([
            ':user_email' => $email
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE user_pk = :user_pk LIMIT 1"
        );

        $stmt->execute([
            ':user_pk' => $id
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function findMemberByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM members WHERE user_fk = :user_fk LIMIT 1"
        );

        $stmt->execute([
            ':user_fk' => $userId
        ]);

        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        return $member ?: null;
    }

    public function updateProfile(
        int $id,
        string $userName,
        string $lastName,
        string $email
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE users
            SET user_name = :user_name,
                user_last_name = :user_last_name,
                user_email = :user_email
            WHERE user_pk = :user_pk"
        );

        return $stmt->execute([
            ':user_pk' => $id,
            ':user_name' => $userName,
            ':user_last_name' => $lastName,
            ':user_email' => $email
        ]);
    }

    public function updatePassword(int $id, string $password): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "UPDATE users
            SET user_password = :user_password
            WHERE user_pk = :user_pk"
        );

        return $stmt->execute([
            ':user_pk' => $id,
            ':user_password' => $hashedPassword
        ]);
    }

    public function updateMemberProfile(
        int $userId,
        string $education,
        string $semester
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE members
            SET education = :education,
                semester = :semester
            WHERE user_fk = :user_fk"
        );

        return $stmt->execute([
            ':user_fk' => $userId,
            ':education' => $education,
            ':semester' => $semester
        ]);
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT users.*, members.*, roles.role_name
            FROM users
            LEFT JOIN members ON users.user_pk = members.user_fk
            LEFT JOIN roles ON users.role_fk = roles.role_pk
            ORDER BY users.user_pk DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}