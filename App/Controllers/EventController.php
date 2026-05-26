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
