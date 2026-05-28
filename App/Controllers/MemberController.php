<?php

// Importerer medlems-model samt helper-funktioner til mail, login, redirects osv.
require_once __DIR__ . '/../Models/MemberModel.php';
require_once __DIR__ . '/../../private/mailhelpers.php';
require_once __DIR__ . '/../../private/helpers.php';

class MemberController
{
    /* ==================================================
    DATAHENTNING
    ================================================== */

    // Henter alle medlemmer der må vises offentligt
    public static function getVisibleMembers(): array
    {
        return MemberModel::getVisibleMembers();
    }

    // Henter alle afventende medlemsansøgninger
    public static function getPending(): array
    {
        return MemberModel::getPending();
    }

    // Henter statistik om medlemmer
    public static function getStats(): array
    {
        return MemberModel::getStats();
    }

    /* ==================================================
    VISNING AF SIDER
    ================================================== */

    // Viser siden hvor brugere kan ansøge om medlemskab
    public static function applicationPage(): void
    {
        require_user();

        // Hvis formularen sendes, oprettes en ansøgning
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();

            self::createApplication();
            exit;
        }

        $educations = MemberModel::getEducations();
        $semesters = MemberModel::getSemesters();
        $memberStats = self::getStats();

        $currentPage = 'membership_apply';
        $view = '/membership_apply.php';

        load_view($view, [
            'educations' => $educations,
            'semesters' => $semesters,
            'memberStats' => $memberStats,
            'currentPage' => $currentPage,
        ]);
    }

    // Viser admin-siden til godkendelse af medlemsansøgninger
    public static function showApprovalPage(): void
    {
        require_admin();

        $applications = self::getPending();
        $educations = MemberModel::getEducations();
        $members = self::getVisibleMembers();

        $currentPage = 'membership_approve';
        $view = '/membership_approve.php';
        $isProfileSection = true;

        load_view($view, [
            'applications' => $applications,
            'educations' => $educations,
            'members' => $members,
            'currentPage' => $currentPage,
            'isProfileSection' => true,
        ]);
    }

    // Viser siden med alle aktive medlemmer
    public static function showMembers(): void
    {
        $members = self::getVisibleMembers();
        $educations = MemberModel::getEducations();
        $memberStats = self::getStats();

        $currentPage = 'members';
        $view = '/members.php';

        load_view($view, [
            'members' => $members,
            'educations' => $educations,
            'memberStats' => $memberStats,
            'currentPage' => $currentPage,
        ]);
    }

    // Viser om-siden med synlige medlemmer
    public static function showAbout(): void
    {
        $members = self::getVisibleMembers();

        $currentPage = 'about';
        $view = '/about.php';

        load_view($view, [
            'members' => $members,
            'currentPage' => $currentPage,
        ]);
    }

    /* ==================================================
    MEDLEMSANSØGNING
    ================================================== */

    // Opretter en ny medlemsansøgning
    public static function createApplication(): void
    {
        // Finder den aktuelle bruger fra sessionen
        $userId = $_SESSION['user']['user_pk'] ?? $_SESSION['user']['id'] ?? null;

        // Brugeren skal være logget ind for at ansøge
        if (!$userId) {
            $_SESSION['error'] = 'Du skal være logget ind for at sende en ansøgning.';
            redirect('/login');
        }

        // Henter input fra ansøgningsformularen
        $educationFk = (int) ($_POST['education_fk'] ?? 0);
        $semesterFk = (int) ($_POST['semester_fk'] ?? 0);
        $applicationText = trim($_POST['description'] ?? '');

        // Sikrer at alle felter er udfyldt
        if ($educationFk <= 0 || $semesterFk <= 0 || $applicationText === '') {
            $_SESSION['error'] = 'Udfyld venligst alle felter i ansøgningen.';
            redirect('/membership_apply#membership-form');
        }

        // Forhindrer at brugeren sender flere ansøgninger
        if (MemberModel::hasApplication((int) $userId)) {
            $_SESSION['error'] = 'Du har allerede sendt en medlemsansøgning.';
            redirect('/membership_apply#membership-form');
        }

        // Henter eksisterende profilbillede hvis brugeren allerede har et
        $existingProfileImage = MemberModel::getUserProfileImage($userId);
        $profileImageName = $existingProfileImage;

        // Håndterer upload af nyt profilbillede
        try {
            $image = validate_image_upload('profile_image');

            if ($image !== null) {
                $uploadDir = __DIR__ . '/../../public/assets/img/uploads/';
                $uploadPath = $uploadDir . $image['file_name'];

                if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
                    throw new Exception('Profilbilledet kunne ikke uploades. Prøv igen.');
                }

                $profileImageName = $image['file_name'];

                MemberModel::updateUserProfileImage($userId, $profileImageName);

                $_SESSION['user']['user_profile_image'] = $profileImageName;
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/membership_apply#membership-form');
        }

        // Kræver at brugeren har et profilbillede før ansøgningen sendes
        if (empty($profileImageName)) {
            $_SESSION['error'] = 'Upload venligst et profilbillede.';
            redirect('/membership_apply#membership-form');
        }

        // Opretter medlemsansøgningen i databasen
        $created = MemberModel::createApplication(
            $userId,
            $educationFk,
            $semesterFk,
            $applicationText
        );

        // Sender bekræftelsesmail hvis ansøgningen blev oprettet
        if ($created) {
            sendMembershipConfirmationMail(
                $_SESSION['user']['user_email'],
                $_SESSION['user']['user_name']
            );

            $_SESSION['success'] = 'Din ansøgning er afsendt. Du modtager en bekræftelsesmail.';
            redirect('/membership_apply#membership-form');
        }

        $_SESSION['error'] = 'Der skete en fejl. Din ansøgning blev ikke sendt.';
        redirect('/membership_apply#membership-form');
    }

    /* ==================================================
    ADMIN: GODKEND / AFVIS / SLET
    ================================================== */

    // Godkender en medlemsansøgning
    public static function approve(): void
    {
        $memberId = $_POST['member_pk'] ?? null;
        $adminId = $_SESSION['user']['user_pk'] ?? $_SESSION['user']['id'] ?? null;

        if ($memberId && $adminId) {
            // Henter ansøgningen før den godkendes, så maildata er tilgængelig
            $application = MemberModel::getApplicationById($memberId);

            // Godkender ansøgningen i databasen
            $approved = MemberModel::approve($memberId, $adminId);

            if ($approved && $application) {
                sendMembershipApprovedMail(
                    $application['user_email'],
                    $application['user_name']
                );

                $_SESSION['success'] = 'Medlemsansøgningen er godkendt.';
            } else {
                $_SESSION['error'] = 'Medlemsansøgningen kunne ikke godkendes.';
            }
        } else {
            $_SESSION['error'] = 'Medlemsansøgningen kunne ikke godkendes.';
        }

        redirect('/membership_approve');
    }

    // Afviser en medlemsansøgning
    public static function reject(): void
    {
        $memberId = $_POST['member_pk'] ?? null;

        if ($memberId) {
            // Henter ansøgningen før den afvises, så maildata er tilgængelig
            $application = MemberModel::getApplicationById($memberId);

            // Afviser ansøgningen i databasen
            $rejected = MemberModel::reject($memberId);

            if ($rejected && $application) {
                sendMembershipRejectedMail(
                    $application['user_email'],
                    $application['user_name']
                );

                $_SESSION['success'] = 'Medlemsansøgningen er afvist.';
            } else {
                $_SESSION['error'] = 'Medlemsansøgningen kunne ikke afvises.';
            }
        } else {
            $_SESSION['error'] = 'Medlemsansøgningen kunne ikke afvises.';
        }

        redirect('/membership_approve');
    }

    // Fjerner et eksisterende medlem
    public static function delete(): void
    {
        $memberId = $_POST['member_pk'] ?? null;

        // Stopper hvis der ikke findes et medlems-id
        if (!$memberId) {
            $_SESSION['error'] = 'Medlemmet kunne ikke fjernes.';
            redirect('/membership_approve');
        }

        // Henter medlemmet før sletning, så maildata er tilgængelig
        $member = MemberModel::getMemberById($memberId);

        // Sletter eller skjuler medlemmet via modellen
        $deleted = MemberModel::delete($memberId);

        if ($deleted && $member) {
            sendMembershipRemovedMail(
                $member['user_email'],
                $member['user_name']
            );

            $_SESSION['success'] = 'Medlemmet er fjernet.';
        } else {
            $_SESSION['error'] = 'Medlemmet kunne ikke fjernes.';
        }

        redirect('/membership_approve');
    }

    /* ==================================================
    ADMIN: REQUEST-HÅNDTERING
    ================================================== */

    // Validerer admin, request method og CSRF før godkendelse
    public static function approveMember(): void
    {
        require_admin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/');
        }

        require_csrf();

        self::approve();
    }

    // Validerer admin, request method og CSRF før afvisning
    public static function rejectMember(): void
    {
        require_admin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/');
        }

        require_csrf();

        self::reject();
    }

    // Validerer admin, request method og CSRF før fjernelse af medlem
    public static function deleteMember(): void
    {
        require_admin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/');
        }

        require_csrf();

        self::delete();
    }
}