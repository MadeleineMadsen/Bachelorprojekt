<?php

require_once __DIR__ . '/../Models/MedlemModel.php';

class MedlemController {

    public static function getApproved(): array {
        return MedlemModel::getApproved();
    }

    public static function getPending(): array {
        return MedlemModel::getPending();
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

        MedlemModel::createApplication(
            $userId,
            $educationFk,
            $semesterFk,
            $applicationText
        );

        header('Location: /medlem_sog?success=sent');
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
            MedlemModel::approve($memberId, $adminId);
        }

        header('Location: /medlem_godkend');
        exit;
    }

    public static function reject(): void {
        $memberId = $_POST['member_pk'] ?? null;

        if ($memberId) {
            MedlemModel::reject($memberId);
        }

        header('Location: /medlem_godkend');
        exit;
    }

    public static function delete(): void {
        $memberId = $_POST['member_pk'] ?? null;

        if ($memberId) {
            MedlemModel::delete($memberId);
        }

        header('Location: /medlem_godkend');
        exit;
    }
}