<?php

class UserModel
{
    // Databaseforbindelse til alle bruger-relaterede queries
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /* ==================================================
    OPRET / BEKRÆFT BRUGER
    ================================================== */

    // Opretter en ny bruger med hashed adgangskode og bekræftelsesnøgle
    public function create(
        string $userName,
        string $lastName,
        string $email,
        string $password,
        string $verificationKey
    ): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "INSERT INTO users 
            (
                user_name, 
                user_last_name, 
                user_email, 
                user_password,
                user_verification_key
            )
        VALUES 
            (
                :user_name, 
                :user_last_name, 
                :user_email, 
                :user_password,
                :user_verification_key
            )"
        );

        return $stmt->execute([
            ':user_name' => $userName,
            ':user_last_name' => $lastName,
            ':user_email' => $email,
            ':user_password' => $hashedPassword,
            ':user_verification_key' => $verificationKey
        ]);
    }

    // Bekræfter en bruger via mail-link
    public function verifyUser(string $key): bool
    {
        $stmt = $this->db->prepare("
        UPDATE users
        SET user_verified_at = NOW(),
            user_verification_key = NULL
        WHERE user_verification_key = ?
            AND user_verified_at IS NULL
            AND user_deleted_at IS NULL
    ");

        $stmt->execute([$key]);

        return $stmt->rowCount() > 0;
    }

    /* ==================================================
    HENT BRUGERDATA
    ================================================== */

    // Finder en aktiv bruger ud fra email
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users 
            WHERE user_email = :user_email 
            AND user_deleted_at IS NULL
            LIMIT 1"
        );

        $stmt->execute([
            ':user_email' => $email
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    // Finder en bruger ud fra bruger-id
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

    // Finder medlemsoplysninger til en bestemt bruger
    public function findMemberByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT 
                members.*,
                educations.education_name,
                semesters.semester_number
            FROM members
            LEFT JOIN educations ON members.education_fk = educations.education_pk
            LEFT JOIN semesters ON members.semester_fk = semesters.semester_pk
            WHERE members.user_fk = :user_fk
            LIMIT 1"
        );

        $stmt->execute([
            ':user_fk' => $userId
        ]);

        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        return $member ?: null;
    }

    // Henter alle brugere med rolle- og medlemsdata
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

    /* ==================================================
    OPDATER PROFIL
    ================================================== */

    // Opdaterer brugerens basisoplysninger
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

    // Opdaterer brugerens adgangskode
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

    // Opdaterer medlemsoplysninger som uddannelse og semester
    public function updateMemberProfile(
        int $userId,
        int $educationFk,
        int $semesterFk
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE members
            SET education_fk = :education_fk,
                semester_fk = :semester_fk
            WHERE user_fk = :user_fk"
        );

        return $stmt->execute([
            ':user_fk' => $userId,
            ':education_fk' => $educationFk,
            ':semester_fk' => $semesterFk
        ]);
    }

    // Opdaterer brugerens profilbillede
    public function updateProfileImage(int $userId, string $fileName): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users
            SET user_profile_image = :user_profile_image
            WHERE user_pk = :user_pk"
        );

        return $stmt->execute([
            ':user_profile_image' => $fileName,
            ':user_pk' => $userId
        ]);
    }

    /* ==================================================
    SLET BRUGER
    ================================================== */

    // Soft-deleter en bruger ved at sætte user_deleted_at
    public function softDelete(int $userId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users
            SET user_deleted_at = NOW()
            WHERE user_pk = :user_pk"
        );

        return $stmt->execute([
            ':user_pk' => $userId
        ]);
    }

    /* ==================================================
    LOGIN-SIKKERHED
    ================================================== */

    // Registrerer et fejlet loginforsøg og returnerer samlet antal forsøg
    public function recordFailedAttempt(int $userId): int
    {
        $this->db->prepare(
            "UPDATE users
            SET failed_login_attempts = failed_login_attempts + 1
            WHERE user_pk = :user_pk"
        )->execute([':user_pk' => $userId]);

        $stmt = $this->db->prepare(
            "SELECT failed_login_attempts FROM users WHERE user_pk = :user_pk"
        );
        $stmt->execute([':user_pk' => $userId]);

        return (int) $stmt->fetchColumn();
    }

    // Låser en bruger efter for mange fejlede loginforsøg
    public function lockAccount(int $userId, string $unlockKey): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users
            SET locked_at = NOW(),
                login_unlock_key = :login_unlock_key,
                failed_login_attempts = 0
            WHERE user_pk = :user_pk"
        );

        return $stmt->execute([
            ':login_unlock_key' => $unlockKey,
            ':user_pk' => $userId
        ]);
    }

    // Nulstiller antal fejlede loginforsøg
    public function resetLoginAttempts(int $userId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users
            SET failed_login_attempts = 0
            WHERE user_pk = :user_pk"
        );

        return $stmt->execute([':user_pk' => $userId]);
    }

    // Låser en konto op via oplåsningslink
    public function unlockAccount(string $key): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users
            SET locked_at = NULL,
                login_unlock_key = NULL,
                failed_login_attempts = 0
            WHERE login_unlock_key = :key
                AND locked_at IS NOT NULL
                AND user_deleted_at IS NULL"
        );

        $stmt->execute([':key' => $key]);

        return $stmt->rowCount() > 0;
    }
}