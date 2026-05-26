<?php

require_once __DIR__ . '/../Models/EventModel.php';
require_once __DIR__ . '/EventController.php';
require_once __DIR__ . '/../../private/mailhelpers.php';
require_once __DIR__ . '/../../private/helpers.php';


    /* ==================================================
    ADMIN: OPRET / REDIGER / SLET EVENTS
    ================================================== */
class AdminEventController
{
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

    // Viser redigeringssiden for et event
    public static function showEdit(): void
    {
        require_role(1, '/events');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::updateEvent();
            exit;
        }

        $event = EventController::getById($_GET['id'] ?? '');

        if (!$event) {
            redirect('/events');
        }

        $categories = EventController::getCategories();

        load_view('/event_create.php', [
            'event' => $event,
            'categories' => $categories,
            'currentPage' => 'events',
            'isProfileSection' => true,
        ]);
    }

    // Viser siden til oprettelse af nyt event
    public static function showCreate(): void
    {
        require_admin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::createEvent();
            exit;
        }

        $categories = EventController::getCategories();

        load_view('/event_create.php', [
            'categories' => $categories,
            'currentPage' => 'event_create',
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

        $participants = EventModel::getParticipantsByEventId($id);

        $imagePath = self::handleImageUpload();

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

    // Opretter et nyt event
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

        $imagePath = self::handleImageUpload();

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
}
