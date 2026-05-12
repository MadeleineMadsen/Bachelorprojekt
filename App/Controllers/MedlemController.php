<?php

require_once __DIR__ . '/../Models/MedlemModel.php';
require_once __DIR__ . '/../../private/helpers.php';

class MedlemController {

    public static function getVisibleMembers(): array {
        return MedlemModel::getVisibleMembers();
    }

    public static function getPending(): array {
        return MedlemModel::getPending();
    }

    public static function getStats(): array {
        return MedlemModel::getStats();
    }

    public static function createApplication(): void {

        $userId = $_SESSION['user']['user_pk'] ?? $_SESSION['user']['id'] ?? null;

        if (!$userId) {
            header('Location: /log_ind');
            exit;
        }

        $educationFk = (int) ($_POST['education_fk'] ?? 0);
        $semesterFk = (int) ($_POST['semester_fk'] ?? 0);
        $applicationText = trim($_POST['description'] ?? '');
        
        if ($educationFk <= 0 || $semesterFk <= 0 || $applicationText === '') {
            $_SESSION['error'] = 'Udfyld venligst alle felter i ansøgningen.';
            header('Location: /medlem_sog#membership-form');
            exit;
        }

        if (MedlemModel::hasApplication((int) $userId)) {
            $_SESSION['error'] = 'Du har allerede sendt en medlemsansøgning.';
            header('Location: /medlem_sog#membership-form');
            exit;
        }

        $existingProfileImage = MedlemModel::getUserProfileImage($userId);
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
                header('Location: /medlem_sog#membership-form');
                exit;
            }

            $profileImageName = uniqid('profile_', true) . '.' . $fileExt;

            if (!move_uploaded_file($fileTmp, $uploadDir . $profileImageName)) {
                $_SESSION['error'] = 'Profilbilledet kunne ikke uploades. Prøv igen.';
                header('Location: /medlem_sog#membership-form');
                exit;
            }

            MedlemModel::updateUserProfileImage($userId, $profileImageName);

            $_SESSION['user']['user_profile_image'] = $profileImageName;
        }

        if (empty($profileImageName)) {
            $_SESSION['error'] = 'Upload venligst et profilbillede.';
            header('Location: /medlem_sog#membership-form');
            exit;
        }

        $created = MedlemModel::createApplication(
            $userId,
            $educationFk,
            $semesterFk,
            $applicationText
        );

        if ($created) {

            // Til når det skal fungere til rigtige mails
            // sendMembershipConfirmationMail(
            //     $_SESSION['user']['user_email'],
            //     $_SESSION['user']['user_name']
            // );

            // Testmail
            sendMembershipConfirmationMail(
                'kamiweb1031@gmail.com',
                $_SESSION['user']['user_name']
            );

            $_SESSION['success'] = 'Din ansøgning er afsendt. Du modtager en bekræftelsesmail.';
            header('Location: /medlem_sog#membership-form');
            exit;
        }

        $_SESSION['error'] = 'Der skete en fejl. Din ansøgning blev ikke sendt.';
        header('Location: /medlem_sog#membership-form');
        exit;
    }

    public static function showApplicationForm(): void {
        $educations = MedlemModel::getEducations();
        $semesters = MedlemModel::getSemesters();

        require __DIR__ . '/../Views/medlem_sog.php';
    }

    public static function approve(): void {
        $memberId = $_POST['member_pk'] ?? null;
        $adminId = $_SESSION['user']['user_pk'] ?? $_SESSION['user']['id'] ?? null;

        if ($memberId && $adminId) {
            $application = MedlemModel::getApplicationById($memberId);

            $approved = MedlemModel::approve($memberId, $adminId);

            if ($approved && $application) {
                sendMembershipApprovedMail(
                    'kamiweb1031@gmail.com',
                    $application['user_name']
                );
            }
        }

        header('Location: /medlem_godkend');
        exit;
    }

    public static function reject(): void {
        $memberId = $_POST['member_pk'] ?? null;

        if ($memberId) {

            $application = MedlemModel::getApplicationById($memberId);

            $rejected = MedlemModel::reject($memberId);

            if ($rejected && $application) {

                // Til rigtige mails senere:
                // sendMembershipRejectedMail(
                //     $application['user_email'],
                //     $application['user_name']
                // );

                // Testmail til eksamen:
                sendMembershipRejectedMail(
                    'kamiweb1031@gmail.com',
                    $application['user_name']
                );
            }
        }

        header('Location: /medlem_godkend');
        exit;
    }

    public static function delete(): void
    {
        $memberId = $_POST['member_pk'] ?? null;

        if (!$memberId) {
            header('Location: /medlem_godkend');
            exit;
        }

        $member = MedlemModel::getMemberById($memberId);

        $deleted = MedlemModel::delete($memberId);

        if ($deleted && $member) {
            // Til rigtige mails senere:
            // sendMembershipRemovedMail(
            //     $member['user_email'],
            //     $member['user_name']
            // );

            // Testmail til eksamen:
            sendMembershipRemovedMail(
                'kamiweb1031@gmail.com',
                $member['user_name']
            );
        }

        header('Location: /medlem_godkend');
        exit;
    }
}