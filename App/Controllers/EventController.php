<?php

require_once __DIR__ . '/../Models/EventModel.php';
require_once __DIR__ . '/../../private/mailhelpers.php';
require_once __DIR__ . '/../../private/helpers.php';

class EventController
{

    public static function getByUser(int $userId): array
    {
        return array_map([self::class, 'formatDates'], EventModel::getByUserId($userId));
    }

    public static function isRegistered(string $eventId, int $userId): bool
    {
        return EventModel::isRegistered($eventId, $userId);
    }

    public static function register(string $eventId, int $userId): void
    {
        EventModel::registerUser($eventId, $userId);

        $event = EventModel::getById($eventId);

        sendEventConfirmMail(
            $_SESSION['user']['user_email'],
            $_SESSION['user']['user_name'],
            $event['event_title']
        );

        $_SESSION['success'] = 'Du er nu tilmeldt eventet.';
    }

    public static function unregister(string $eventId, int $userId): void
    {
        $event = EventModel::getById($eventId);

        EventModel::unregisterUser($eventId, $userId);

        sendEventRemoveMail(
            $_SESSION['user']['user_email'],
            $_SESSION['user']['user_name'],
            $event['event_title']
        );

        $_SESSION['success'] = 'Du er nu afmeldt eventet.';
    }

    public static function toggleRegistration(): void
    {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/events');
        }

        require_csrf();

        $eventId = $_POST['event_id'] ?? '';
        $action = $_POST['action'] ?? 'register';

        if ($action === 'unregister') {
            self::unregister($eventId, $_SESSION['user']['user_pk']);
        } else {
            self::register($eventId, $_SESSION['user']['user_pk']);
        }

        redirect('/event_page?id=' . urlencode($eventId));
    }

    private static function groupByDate(array $events): array
    {
        $grouped = [];
        foreach ($events as $event) {
            $date = $event['event_date'];
            $grouped[$date][] = [
                'title' => $event['event_title'],
                'image' => !empty($event['event_image']) ? '/assets/img/' . $event['event_image'] : '/assets/img/placeholder.webp',
                'pk' => $event['event_pk'],
            ];
        }
        return $grouped;
    }

    public static function getAllForCalendar(): array
    {
        return self::groupByDate(EventModel::getAll());
    }

    public static function getByUserForCalendar(int $userId): array
    {
        return self::groupByDate(EventModel::getByUserId($userId));
    }

    public static function getRegisteredEventIds(int $userId): array
    {
        $events = EventModel::getByUserId($userId);
        return array_column($events, 'event_pk');
    }

    public static function showCalendar(): void
    {
        require_login();

        $isAdmin = is_admin();

        $calendarEvents = self::getAllForCalendar();

        $registeredEventIds = $isAdmin
            ? []
            : self::getRegisteredEventIds($_SESSION['user']['user_pk']);

        $currentPage = 'calendar';
        $view = '/calendar.php';
        $isProfileSection = true;

        load_view($view, [
            'calendarEvents' => $calendarEvents,
            'registeredEventIds' => $registeredEventIds,
            'currentPage' => $currentPage,
            'isProfileSection' => true,
        ]);
    }

    public static function getCategories(): array
    {
        return EventModel::getAllCategories();
    }

    public static function showAll(): void
    {
        $events = self::getAll();
        $categories = self::getCategories();

        $currentPage = 'events';
        $view = '/events.php';

        load_view($view, [
            'events' => $events,
            'categories' => $categories,
            'currentPage' => $currentPage,
        ]);
    }

    public static function showFrontpage(): void
    {
        $events = self::getLatest(3);

        $currentPage = '';
        $view = '/home.php';

        load_view($view, [
            'events' => $events,
            'currentPage' => $currentPage,
        ]);
    }

    public static function showSingle(): void
    {
        $event = self::getById($_GET['id'] ?? '');

        if (!$event) {
            redirect('/events');
        }

        $dato = $event['dato'];

        $participants = self::getParticipants($_GET['id'] ?? '');

        $isLoggedIn = is_logged_in();

        $isRegistered = false;

        if ($isLoggedIn) {
            $isRegistered = self::isRegistered(
                $_GET['id'] ?? '',
                $_SESSION['user']['user_pk']
            );
        }

        $currentPage = 'events';
        $view = '/event_page.php';

        load_view($view, [
            'event' => $event,
            'dato' => $dato,
            'participants' => $participants,
            'isRegistered' => $isRegistered,
            'currentPage' => $currentPage,
        ]);
    }

    public static function showUserEvents(): void
    {
        require_login();

        $events = self::getByUser($_SESSION['user']['user_pk']);

        $currentPage = 'my_events';
        $view = '/my_events.php';
        $isProfileSection = true;

        load_view($view, [
            'events' => $events,
            'currentPage' => $currentPage,
            'isProfileSection' => true,
        ]);
    }

    public static function showEdit(): void
    {
        require_role(1, '/events');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::updateEvent();
            exit;
        }

        $event = self::getById($_GET['id'] ?? '');

        if (!$event) {
            redirect('/events');
        }

        $categories = self::getCategories();

        $currentPage = 'events';
        $view = '/event_create.php';
        $isProfileSection = true;

        load_view($view, [
            'event' => $event,
            'categories' => $categories,
            'currentPage' => $currentPage,
            'isProfileSection' => true,
        ]);
    }

    public static function showCreate(): void
    {
        require_admin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::createEvent();
            exit;
        }

        $categories = self::getCategories();

        $currentPage = 'event_create';
        $view = '/event_create.php';
        $isProfileSection = true;

        load_view($view, [
            'categories' => $categories,
            'currentPage' => $currentPage,
            'isProfileSection' => true,
        ]);
    }

    public static function updateEvent(): void
    {
        require_role(1, '/events');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/events');
        }

        require_csrf();

        self::update();
    }

    public static function deleteEvent(): void
    {
        require_role(1, '/events');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/events');
        }

        require_csrf();

        self::delete($_POST['event_id'] ?? '');
    }

    public static function createEvent(): void
    {
        require_admin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/event_create');
        }

        require_csrf();

        self::create();
    }

    public static function getAll(): array
    {
        return array_map([self::class, 'formatDates'], EventModel::getAll());
    }

    public static function getLatest(int $limit = 3): array
    {
        return array_map([self::class, 'formatDates'], EventModel::getLatest($limit));
    }

    public static function getParticipants(string $id): array
    {
        return EventModel::getParticipantsByEventId($id);
    }

    public static function getById(string $id): array|false
    {
        $event = EventModel::getById($id);

        if (!$event) {
            return false;
        }

        return self::formatDates($event);
    }

    public static function delete(string $id): void
    {
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

        $_SESSION['success'] = 'Eventet er slettet.';
        header('Location: /events');
        exit;
    }

    public static function update(): void
    {

        $id = $_POST['event_pk'] ?? '';
        $title = trim($_POST['titel'] ?? '');
        $subtitle = trim($_POST['subtitel'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $expectations = trim($_POST['description-bulletpoints'] ?? '');
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $endTime = $_POST['end_time'] ?: null;
        $location = trim($_POST['location'] ?? '');
        $category = $_POST['category'] ?? '';

        // Hent deltagere før update
        $participants = EventModel::getParticipantsByEventId($id);

        $imagePath = null;

        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/../../public/assets/img/events/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
            $imagePath = 'events/' . $filename;
        }

        EventModel::update([
            'event_pk' => $id,
            'event_title' => $title,
            'event_subtitle' => $subtitle,
            'event_description' => $description,
            'event_expectations' => $expectations,
            'event_date' => $date,
            'event_time' => $time,
            'event_end_time' => $endTime,
            'event_location' => $location,
            'category_fk' => $category,
            'event_image' => $imagePath,
        ]);

        // Send mail til alle deltagere
        foreach ($participants as $participant) {

            sendEventUpdatedMail(
                $participant['user_email'],
                $participant['user_name'],
                $title
            );
        }

        $_SESSION['success'] = 'Eventet er opdateret.';
        header('Location: /event_page?id=' . urlencode($id));
        exit;
    }

    public static function create(): void
    {
        $title = trim($_POST['titel'] ?? '');
        $subtitle = trim($_POST['subtitel'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $expectations = trim($_POST['description-bulletpoints'] ?? '');
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $endTime = $_POST['end_time'] ?? null;
        $location = trim($_POST['location'] ?? '');
        $category = $_POST['category'] ?? '';

        if (empty($_FILES['image']['name']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Du skal uploade et billede.';
            header('Location: /event_create');
            exit;
        }

        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/../../public/assets/img/events/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
            $imagePath = 'events/' . $filename;
        }

        $uuid = bin2hex(random_bytes(16));

        EventModel::create([
            'event_pk' => $uuid,
            'event_title' => $title,
            'event_subtitle' => $subtitle,
            'event_description' => $description,
            'event_expectations' => $expectations,
            'event_date' => $date,
            'event_time' => $time,
            'event_end_time' => $endTime ?: null,
            'event_location' => $location,
            'category_fk' => $category,
            'event_image' => $imagePath,
            'created_by_fk' => $_SESSION['user']['user_pk'],
        ]);

        $_SESSION['success'] = 'Eventet er oprettet.';
        header('Location: /events');
        exit;
    }

    private static function formatDates(array $event): array
    {
        $dage = ['Søndag', 'Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lørdag'];
        $måneder = ['Januar', 'Februar', 'Marts', 'April', 'Maj', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'December'];
        $måneder_kort = ['JAN', 'FEB', 'MAR', 'APR', 'MAJ', 'JUN', 'JUL', 'AUG', 'SEP', 'OKT', 'NOV', 'DEC'];
        $ts = strtotime($event['event_date']);

        $event['date_day'] = date('d', $ts);
        $event['date_month_da'] = $måneder_kort[(int) date('n', $ts) - 1];
        $event['dato'] = $dage[date('w', $ts)] . ' d. ' . date('j', $ts) . ' ' . $måneder[(int) date('n', $ts) - 1];

        return $event;
    }
}
