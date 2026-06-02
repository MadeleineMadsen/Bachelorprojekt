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
    // Gemmer uploadet eventbillede og returnerer billedstien, eller null hvis intet billede
    private static function handleImageUpload(): ?string
    {
        // Validerer billedet via helper
        $image = validate_image_upload('image');

        // Returnerer null hvis der ikke er uploadet et nyt billede
        if ($image === null) {
            return null;
        }

        // Upload-mappe til eventbilleder
        $uploadDir = __DIR__ . '/../../public/assets/img/events/';

        // Opretter upload-mappen hvis den ikke findes
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Fuld sti til det nye billede
        $uploadPath = $uploadDir . $image['file_name'];

        // Gemmer billedet i uploads-mappen
        if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
            throw new Exception('Eventbilledet kunne ikke uploades.');
        }

        // Returnerer sti som kan gemmes i databasen
        return 'events/' . $image['file_name'];
    }

    // Validerer input fra event-formularen
    private static function validateEventInput(): array
    {
        // Validerer tekstfelter via helper-funktioner
        $title = validate_text('titel', 'Titel', 2, 100);
        $subtitle = validate_text('subtitel', 'Undertitel', 2, 150);

        $description = validate_text(
            'description',
            'Beskrivelse',
            10,
            2000
        );

        $expectations = validate_text(
            'description-bulletpoints',
            'Beskrivende punkter',
            10,
            2000
        );

        $whyJoin = validate_text(
            'why_join',
            'Hvorfor skal du deltage',
            10,
            2000
        );

        $location = validate_text(
            'location',
            'Lokation',
            2,
            150
        );

        // Henter dato og tidspunkt fra formularen
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $endTime = $_POST['end_time'] ?: null;

        // Henter kategori-id
        $category = (int) ($_POST['category'] ?? 0);

        // Validerer dato
        if ($date === '' || strtotime($date) === false) {
            throw new Exception('Dato skal udfyldes og være gyldig.');
        }

        // Validerer starttidspunkt
        if ($time === '') {
            throw new Exception('Tidspunkt skal udfyldes.');
        }

        // Validerer at sluttidspunkt ligger efter starttidspunkt
        if ($endTime !== null && $endTime <= $time) {
            throw new Exception(
                'Sluttidspunkt skal være efter starttidspunkt.'
            );
        }

        // Validerer kategori
        if ($category <= 0) {
            throw new Exception('Vælg venligst en kategori.');
        }

        // Returnerer validerede data
        return [
            'title' => $title,
            'subtitle' => $subtitle,
            'description' => $description,
            'expectations' => $expectations,
            'whyJoin' => $whyJoin,
            'date' => $date,
            'time' => $time,
            'endTime' => $endTime,
            'location' => $location,
            'category' => $category,
        ];
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
        unset($_SESSION['old']);
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
        redirect('/events');
    }

    // Opdaterer et eksisterende event
    public static function update(): void
    {
        $id = $_POST['event_pk'] ?? '';

        try {
            // Validerer input fra event-formularen
            $data = self::validateEventInput();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/event_create?id=' . urlencode($id));
        }

        $participants = EventModel::getParticipantsByEventId($id);

        try {
            $imagePath = self::handleImageUpload();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/event_create?id=' . urlencode($id));
        }

        // Beholder eksisterende billede hvis der ikke uploades et nyt
        if ($imagePath === null) {
            $existingEvent = EventModel::getById($id);
            $imagePath = $existingEvent['event_image'] ?? null;
        }

        EventModel::update([
            'event_pk' => $id,
            'event_title' => $data['title'],
            'event_subtitle' => $data['subtitle'],
            'event_description' => $data['description'],
            'event_expectations' => $data['expectations'],
            'event_why_join' => $data['whyJoin'],
            'event_date' => $data['date'],
            'event_time' => $data['time'],
            'event_end_time' => $data['endTime'],
            'event_location' => $data['location'],
            'category_fk' => $data['category'],
            'event_image' => $imagePath,
        ]);

        // Send mails i baggrunden så admin ikke skal vente
        $payload = base64_encode(json_encode([
            'participants' => $participants,
            'title'        => $data['title'],
        ]));
        $script = escapeshellarg(__DIR__ . '/../../private/send_event_emails.php');
        exec("php $script " . escapeshellarg($payload) . " > /dev/null 2>&1 &");

        $_SESSION['success'] = 'Eventet er opdateret.';
        redirect('/event_page?id=' . urlencode($id));
    }

    // Opretter et nyt event
    public static function create(): void
    {
        // Upload billede først så det ikke mistes ved valideringsfejl
        try {
            $imagePath = self::handleImageUpload();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old'] = $_POST;
            redirect('/event_create');
        }

        // Brug tidligere uploadet billede hvis intet nyt er valgt
        if ($imagePath === null && !empty($_POST['saved_image'])) {
            $imagePath = $_POST['saved_image'];
        }

        try {
            $data = self::validateEventInput();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old'] = $_POST;
            if ($imagePath !== null) {
                $_SESSION['old']['saved_image'] = $imagePath;
            }
            redirect('/event_create');
        }

        if ($imagePath === null) {
            $_SESSION['error'] = 'Du skal uploade et billede.';
            $_SESSION['old'] = $_POST;
            redirect('/event_create');
        }

        $uuid = bin2hex(random_bytes(16));

        EventModel::create([
            'event_pk' => $uuid,
            'event_title' => $data['title'],
            'event_subtitle' => $data['subtitle'],
            'event_description' => $data['description'],
            'event_expectations' => $data['expectations'],
            'event_why_join' => $data['whyJoin'],
            'event_date' => $data['date'],
            'event_time' => $data['time'],
            'event_end_time' => $data['endTime'],
            'event_location' => $data['location'],
            'category_fk' => $data['category'],
            'event_image' => $imagePath,
            'created_by_fk' => $_SESSION['user']['user_pk'],
        ]);

        $_SESSION['success'] = 'Eventet er oprettet.';
        redirect('/events');
    }
}
