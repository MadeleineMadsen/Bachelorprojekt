<?php

// Importerer event-model samt helper-funktioner til mail, login, redirects osv.
require_once __DIR__ . '/../Models/EventModel.php';
require_once __DIR__ . '/../../private/mailhelpers.php';
require_once __DIR__ . '/../../private/helpers.php';

class EventController
{

    /* ==================================================
    TILMELDING / AFMELDING
    ================================================== */

    // Henter alle events en bestemt bruger er tilmeldt
    public static function getByUser(int $userId): array
    {
        return array_map([self::class, 'formatDates'], EventModel::getByUserId($userId));
    }

    // Tjekker om en bruger allerede er tilmeldt et event
    public static function isRegistered(string $eventId, int $userId): bool
    {
        return EventModel::isRegistered($eventId, $userId);
    }

    // Tilmelder en bruger til et event og sender bekræftelsesmail
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

    // Afmelder en bruger fra et event og sender mail
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

    // Håndterer klik på tilmeld eller afmeld-knappen
    public static function toggleRegistration(): void
    {
        require_login();

        // Tilmelding/afmelding må kun ske via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/events');
        }

        require_csrf();

        $eventId = $_POST['event_id'] ?? '';
        $action = $_POST['action'] ?? 'register';

        // Vælger handling ud fra formularens action-værdi
        if ($action === 'unregister') {
            self::unregister($eventId, $_SESSION['user']['user_pk']);
        } else {
            self::register($eventId, $_SESSION['user']['user_pk']);
        }

        redirect('/event_page?id=' . urlencode($eventId));
    }

    /* ==================================================
    KALENDER
    ================================================== */

    // Grupperer events efter dato, så de kan vises i kalenderen
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

    // Henter alle events formateret til kalenderen
    public static function getAllForCalendar(): array
    {
        return self::groupByDate(EventModel::getAll());
    }

    // Henter brugerens egne events formateret til kalenderen
    public static function getByUserForCalendar(int $userId): array
    {
        return self::groupByDate(EventModel::getByUserId($userId));
    }

    // Henter kun event-id'er for de events brugeren er tilmeldt
    public static function getRegisteredEventIds(int $userId): array
    {
        $events = EventModel::getByUserId($userId);
        return array_column($events, 'event_pk');
    }

    // Viser kalendersiden
    public static function showCalendar(): void
    {
        require_login();

        $isAdmin = is_admin();

        $calendarEvents = self::getAllForCalendar();

        // Admin skal ikke markeres som tilmeldt events
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

    /* ==================================================
    VISNING AF EVENTS
    ================================================== */

    // Henter alle event-kategorier
    public static function getCategories(): array
    {
        return EventModel::getAllCategories();
    }

    // Viser siden med alle events
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

    // Viser forsiden med de nyeste events
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

    // Viser en specifik eventside
    public static function showSingle(): void
    {
        $event = self::getById($_GET['id'] ?? '');

        // Sender brugeren tilbage hvis eventet ikke findes
        if (!$event) {
            redirect('/events');
        }

        $dato = $event['dato'];

        $participants = self::getParticipants($_GET['id'] ?? '');

        $isLoggedIn = is_logged_in();

        $isRegistered = false;

        // Tjekker kun tilmelding hvis brugeren er logget ind
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

    // Viser brugerens egne tilmeldte events
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

    /* ==================================================
    ADMIN: OPRET / REDIGER / SLET EVENTS
    ================================================== */

    // Viser redigeringssiden for et event
    public static function showEdit(): void
    {
        require_role(1, '/events');

        // Hvis formularen sendes, opdateres eventet
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

    // Viser siden til oprettelse af nyt event
    public static function showCreate(): void
    {
        require_admin();

        // Hvis formularen sendes, oprettes eventet
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

    // Validerer adgang og request før event opdateres
    public static function updateEvent(): void
    {
        require_role(1, '/events');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/events');
        }

        require_csrf();

        self::update();
    }

    // Validerer adgang og request før event slettes
    public static function deleteEvent(): void
    {
        require_role(1, '/events');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/events');
        }

        require_csrf();

        self::delete($_POST['event_id'] ?? '');
    }

    // Validerer adgang og request før event oprettes
    public static function createEvent(): void
    {
        require_admin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/event_create');
        }

        require_csrf();

        self::create();
    }

    /* ==================================================
    DATAHENTNING
    ================================================== */

    // Henter alle events og formaterer datoer
    public static function getAll(): array
    {
        return array_map([self::class, 'formatDates'], EventModel::getAll());
    }

    // Henter de nyeste events og formaterer datoer
    public static function getLatest(int $limit = 3): array
    {
        return array_map([self::class, 'formatDates'], EventModel::getLatest($limit));
    }

    // Henter deltagere til et event
    public static function getParticipants(string $id): array
    {
        return EventModel::getParticipantsByEventId($id);
    }

    // Henter ét event ud fra id og formaterer datoer
    public static function getById(string $id): array|false
    {
        $event = EventModel::getById($id);

        if (!$event) {
            return false;
        }

        return self::formatDates($event);
    }

    /* ==================================================
    CRUD-FUNKTIONER
    ================================================== */

    // Sletter et event og sender besked til alle tilmeldte deltagere
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

    // Opdaterer et eksisterende event
    public static function update(): void
    {
        // Henter input fra redigeringsformularen
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

        // Henter deltagere før update, så de kan få besked efter ændringen
        $participants = EventModel::getParticipantsByEventId($id);

        $imagePath = self::handleImageUpload();

        // Gemmer ændringerne i databasen
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

        // Sender opdateringsmail til alle deltagere
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

    // Gemmer uploadet billede og returnerer stien, eller null hvis intet billede
    private static function handleImageUpload(): ?string
    {
        if (empty($_FILES['image']['name'])) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/assets/img/events/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = bin2hex(random_bytes(8)) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);

        return 'events/' . $filename;
    }

    // Opretter et nyt event
    public static function create(): void
    {
        // Henter input fra oprettelsesformularen
        $title = trim($_POST['titel'] ?? '');
        $subtitle = trim($_POST['subtitel'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $expectations = trim($_POST['description-bulletpoints'] ?? '');
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $endTime = $_POST['end_time'] ?? null;
        $location = trim($_POST['location'] ?? '');
        $category = $_POST['category'] ?? '';

        // Kræver at der uploades et billede ved oprettelse
        if (empty($_FILES['image']['name']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Du skal uploade et billede.';
            header('Location: /event_create');
            exit;
        }

        $imagePath = self::handleImageUpload();

        // Opretter unikt id til eventet
        $uuid = bin2hex(random_bytes(16));

        // Gemmer eventet i databasen
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

    /* ==================================================
    FORMATERING
    ================================================== */

    // Tilføjer danske datoformater til et event-array
    private static function formatDates(array $event): array
    {
        $dage = ['Søndag', 'Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lørdag'];
        $måneder = ['Januar', 'Februar', 'Marts', 'April', 'Maj', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'December'];
        $måneder_kort = ['JAN', 'FEB', 'MAR', 'APR', 'MAJ', 'JUN', 'JUL', 'AUG', 'SEP', 'OKT', 'NOV', 'DEC'];
        $ts = strtotime($event['event_date']);

        // Bruges fx til event cards
        $event['date_day'] = date('d', $ts);
        $event['date_month_da'] = $måneder_kort[(int) date('n', $ts) - 1];

        // Bruges fx på single event-siden
        $event['dato'] = $dage[date('w', $ts)] . ' d. ' . date('j', $ts) . ' ' . $måneder[(int) date('n', $ts) - 1];

        return $event;
    }
}
