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

        $education = trim($_POST['education'] ?? '');
        $semester = (int) ($_POST['semester'] ?? 0);
        $applicationText = trim($_POST['description'] ?? '');

        if ($education === '' || $semester <= 0 || $applicationText === '') {
            header('Location: /medlem_sog?error=missing_fields');
            exit;
        }

        MedlemModel::createApplication(
            $userId,
            $education,
            $semester,
            $applicationText
        );

        header('Location: /medlem_sog?success=sent');
        exit;
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