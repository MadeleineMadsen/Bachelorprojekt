<?php

require_once __DIR__ . '/../Models/MemberModel.php';
require_once __DIR__ . '/../../private/mailhelpers.php';
require_once __DIR__ . '/../../private/helpers.php';

class MemberController
{
    public static function getVisibleMembers(): array
    {
        return MemberModel::getVisibleMembers();
    }

    public static function getPending(): array
    {
        return MemberModel::getPending();
    }

    public static function getStats(): array
    {
        return MemberModel::getStats();
    }

    public static function applicationPage(): void
    {
        require_user();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();

            self::createApplication();
            exit;
        }

        $educations = MemberModel::getEducations();
        $semesters = MemberModel::getSemesters();
        $memberStats = self::getStats();

        $currentPage = 'medlem_sog';
        $view = '/medlem_sog.php';

        load_view($view, [
            'educations' => $educations,
            'semesters' => $semesters,
            'memberStats' => $memberStats,
            'currentPage' => $currentPage,
        ]);
    }

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

    public static function showMembers(): void
    {
        $members = self::getVisibleMembers();
        $educations = MemberModel::getEducations();
        $memberStats = self::getStats();

        $currentPage = 'medlemmer';
        $view = '/medlemmer.php';

        load_view($view, [
            'members' => $members,
            'educations' => $educations,
            'memberStats' => $memberStats,
            'currentPage' => $currentPage,
        ]);
    }

    public static function showAbout(): void
    {
        $members = self::getVisibleMembers();

        $currentPage = 'om';
        $view = '/about.php';

        load_view($view, [
            'members' => $members,
            'currentPage' => $currentPage,
        ]);
    }

    public static function createApplication(): void
    {
        $userId = $_SESSION['user']['user_pk'] ?? $_SESSION['user']['id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = 'Du skal være logget ind for at sende en ansøgning.';
            header('Location: /log_ind');
            exit;
        }

        $educationFk = (int) ($_POST['education_fk'] ?? 0);
        $semesterFk = (int) ($_POST['semester_fk'] ?? 0);
        $applicationText = trim($_POST['description'] ?? '');

        if ($educationFk <= 0 || $semesterFk <= 0 || $applicationText === '') {
            $_SESSION['error'] = 'Udfyld venligst alle felter i ansøgningen.';
            header('Location: /membership_apply#membership-form');
            exit;
        }

        if (MemberModel::hasApplication((int) $userId)) {
            $_SESSION['error'] = 'Du har allerede sendt en medlemsansøgning.';
            header('Location: /membership_apply#membership-form');
            exit;
        }

        $existingProfileImage = MemberModel::getUserProfileImage($userId);
        $profileImageName = $existingProfileImage;

        if (
            isset($_FILES['profile_image']) &&
            $_FILES['profile_image']['error'] === UPLOAD_ERR_OK
        ) {
            $uploadDir = __DIR__ . '/../../public/assets/img/uploads/';

            $fileTmp = $_FILES['profile_image']['tmp_name'];
            $fileName = $_FILES['profile_image']['name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($fileExt, $allowedExtensions)) {
                $_SESSION['error'] = 'Profilbilledet skal være jpg, jpeg, png eller webp.';
                header('Location: /membership_apply#membership-form');
                exit;
            }

            $profileImageName = uniqid('profile_', true) . '.' . $fileExt;

            if (!move_uploaded_file($fileTmp, $uploadDir . $profileImageName)) {
                $_SESSION['error'] = 'Profilbilledet kunne ikke uploades. Prøv igen.';
                header('Location: /membership_apply#membership-form');
                exit;
            }

            MemberModel::updateUserProfileImage($userId, $profileImageName);

            $_SESSION['user']['user_profile_image'] = $profileImageName;
        }

        if (empty($profileImageName)) {
            $_SESSION['error'] = 'Upload venligst et profilbillede.';
            header('Location: /membership_apply#membership-form');
            exit;
        }

        $created = MemberModel::createApplication(
            $userId,
            $educationFk,
            $semesterFk,
            $applicationText
        );

        if ($created) {
            sendMembershipConfirmationMail(
                $_SESSION['user']['user_email'],
                $_SESSION['user']['user_name']
            );

            $_SESSION['success'] = 'Din ansøgning er afsendt. Du modtager en bekræftelsesmail.';
            header('Location: /membership_apply#membership-form');
            exit;
        }

        $_SESSION['error'] = 'Der skete en fejl. Din ansøgning blev ikke sendt.';
        header('Location: /membership_apply#membership-form');
        exit;
    }

    public static function approve(): void
    {
        $memberId = $_POST['member_pk'] ?? null;
        $adminId = $_SESSION['user']['user_pk'] ?? $_SESSION['user']['id'] ?? null;

        if ($memberId && $adminId) {
            $application = MemberModel::getApplicationById($memberId);
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

        header('Location: /membership_approve');
        exit;
    }

    public static function reject(): void
    {
        $memberId = $_POST['member_pk'] ?? null;

        if ($memberId) {
            $application = MemberModel::getApplicationById($memberId);
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

        header('Location: /membership_approve');
        exit;
    }

    public static function delete(): void
    {
        $memberId = $_POST['member_pk'] ?? null;

        if (!$memberId) {
            $_SESSION['error'] = 'Medlemmet kunne ikke fjernes.';
            header('Location: /membership_approve');
            exit;
        }

        $member = MemberModel::getMemberById($memberId);
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

        header('Location: /membership_approve');
        exit;
    }

    public static function approveMember(): void
    {
        require_admin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/');
        }

        require_csrf();

        self::approve();
    }

    public static function rejectMember(): void
    {
        require_admin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/');
        }

        require_csrf();

        self::reject();
    }

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