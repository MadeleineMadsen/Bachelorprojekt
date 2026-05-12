<?php

require_once __DIR__ . '/../Models/MedlemModel.php';
require_once __DIR__ . '/../../private/helpers.php';

class MedlemController {

    public static function getApproved(): array {
        return MedlemModel::getApproved();
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
            header('Location: /medlem_sog?error=missing_fields');
            exit;
        }

        if (MedlemModel::hasApplication($userId)) {
            header('Location: /medlem_sog?error=already_applied');
            exit;
        }

        $profileImageName = null;

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/assets/img/uploads/';

            $fileTmp = $_FILES['profile_image']['tmp_name'];
            $fileName = $_FILES['profile_image']['name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($fileExt, $allowedExtensions)) {
                header('Location: /medlem_sog');
                exit;
            }

            $profileImageName = uniqid('profile_', true) . '.' . $fileExt;

            if (!move_uploaded_file($fileTmp, $uploadDir . $profileImageName)) {
                header('Location: /medlem_sog?error=image_upload_failed');
                exit;
            }
            
            MedlemModel::updateUserProfileImage($userId, $profileImageName);

            $_SESSION['user']['user_profile_image'] = $profileImageName;
        } else {
            header('Location: /medlem_sog');
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

            header('Location: /medlem_sog?success=sent');
            exit;
        }

        header('Location: /medlem_sog?error=failed');
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

        MedlemModel::delete($memberId);

        header('Location: /medlem_godkend');
        exit;
    }
}