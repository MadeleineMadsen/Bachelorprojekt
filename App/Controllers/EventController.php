<?php

require_once __DIR__ . '/../Models/EventModel.php';
require_once __DIR__ . '/../../private/mailhelpers.php';

class EventController {

    public static function getByUser(int $userId): array {
        return array_map([self::class, 'formatDates'], EventModel::getByUserId($userId));
    }

    public static function isRegistered(string $eventId, int $userId): bool {
        return EventModel::isRegistered($eventId, $userId);
    }

    public static function register(string $eventId, int $userId): void {
    EventModel::registerUser($eventId, $userId);

    $event = EventModel::getById($eventId);

        sendEventConfirmMail(
            $_SESSION['user']['user_email'],
            $_SESSION['user']['user_name'],
            $event['event_title']
        );
    }

    public static function unregister(string $eventId, int $userId): void {
        $event = EventModel::getById($eventId);

        EventModel::unregisterUser($eventId, $userId);

        sendEventRemoveMail(
            $_SESSION['user']['user_email'],
            $_SESSION['user']['user_name'],
            $event['event_title']
        );
    }

    private static function groupByDate(array $events): array {
        $grouped = [];
        foreach ($events as $event) {
            $date = $event['event_date'];
            $grouped[$date][] = [
                'title' => $event['event_title'],
                'image' => !empty($event['event_image']) ? '/assets/img/' . $event['event_image'] : '/assets/img/placeholder.webp',
                'pk'    => $event['event_pk'],
            ];
        }
        return $grouped;
    }

    public static function getAllForCalendar(): array {
        return self::groupByDate(EventModel::getAll());
    }

    public static function getByUserForCalendar(int $userId): array {
        return self::groupByDate(EventModel::getByUserId($userId));
    }

    public static function getRegisteredEventIds(int $userId): array {
        $events = EventModel::getByUserId($userId);
        return array_column($events, 'event_pk');
    }

    public static function getCategories(): array {
        return EventModel::getAllCategories();
    }

    public static function getAll(): array {
        return array_map([self::class, 'formatDates'], EventModel::getAll());
    }

    public static function getLatest(int $limit = 3): array {
        return array_map([self::class, 'formatDates'], EventModel::getLatest($limit));
    }

    public static function getParticipants(string $id): array {
        return EventModel::getParticipantsByEventId($id);
    }

    public static function getById(string $id): array|false {
        $event = EventModel::getById($id);

        if (!$event) {
            return false;
        }

        return self::formatDates($event);
    }

    public static function delete(string $id): void {
        $event = EventModel::getById($id);
        $participants = EventModel::getParticipantsByEventId($id);

        foreach ($participants as $participant) {
            sendEventDeletedMail(
                $participant['user_email'],
                $participant['user_name'],
                $event['event_title']
            );
        }

        EventModel::delete($id);
    }

    public static function update(): void {

        $id           = $_POST['event_pk'] ?? '';
        $title        = trim($_POST['titel'] ?? '');
        $subtitle     = trim($_POST['subtitel'] ?? '');
        $description  = trim($_POST['description'] ?? '');
        $expectations = trim($_POST['description-bulletpoints'] ?? '');
        $date         = $_POST['date'] ?? '';
        $time         = $_POST['time'] ?? '';
        $endTime      = $_POST['end_time'] ?: null;
        $location     = trim($_POST['location'] ?? '');
        $category     = $_POST['category'] ?? '';

        // Hent deltagere før update
        $participants = EventModel::getParticipantsByEventId($id);

        $imagePath = null;

        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/../../public/assets/img/events/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
            $imagePath = 'events/' . $filename;
        }

        EventModel::update([
            'event_pk'           => $id,
            'event_title'        => $title,
            'event_subtitle'     => $subtitle,
            'event_description'  => $description,
            'event_expectations' => $expectations,
            'event_date'         => $date,
            'event_time'         => $time,
            'event_end_time'     => $endTime,
            'event_location'     => $location,
            'category_fk'        => $category,
            'event_image'        => $imagePath,
        ]);

        // Send mail til alle deltagere
        foreach ($participants as $participant) {

            sendEventUpdatedMail(
                $participant['user_email'],
                $participant['user_name'],
                $title
            );
        }

        header('Location: /eventside?id=' . urlencode($id));
        exit;
    }

    public static function create(): void {
        $title        = trim($_POST['titel'] ?? '');
        $subtitle     = trim($_POST['subtitel'] ?? '');
        $description  = trim($_POST['description'] ?? '');
        $expectations = trim($_POST['description-bulletpoints'] ?? '');
        $date         = $_POST['date'] ?? '';
        $time         = $_POST['time'] ?? '';
        $endTime      = $_POST['end_time'] ?? null;
        $location     = trim($_POST['location'] ?? '');
        $category     = $_POST['category'] ?? '';

        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/../../public/assets/img/events/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
            $imagePath = '/assets/img/events/' . $filename;
        }

        $uuid = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        EventModel::create([
            'event_pk'          => $uuid,
            'event_title'       => $title,
            'event_subtitle'    => $subtitle,
            'event_description' => $description,
            'event_expectations'=> $expectations,
            'event_date'        => $date,
            'event_time'        => $time,
            'event_end_time'    => $endTime ?: null,
            'event_location'    => $location,
            'category_fk'       => $category,
            'event_image'       => $imagePath,
            'created_by_fk'     => $_SESSION['user']['user_pk'],
        ]);

        header('Location: /events');
        exit;
    }

    private static function formatDates(array $event): array {
        $dage          = ['Søndag','Mandag','Tirsdag','Onsdag','Torsdag','Fredag','Lørdag'];
        $måneder       = ['Januar','Februar','Marts','April','Maj','Juni','Juli','August','September','Oktober','November','December'];
        $måneder_kort  = ['JAN','FEB','MAR','APR','MAJ','JUN','JUL','AUG','SEP','OKT','NOV','DEC'];
        $ts            = strtotime($event['event_date']);

        $event['date_day']      = date('d', $ts);
        $event['date_month_da'] = $måneder_kort[(int)date('n', $ts) - 1];
        $event['dato']          = $dage[date('w', $ts)] . ' d. ' . date('j', $ts) . ' ' . $måneder[(int)date('n', $ts) - 1];

        return $event;
    }
}
