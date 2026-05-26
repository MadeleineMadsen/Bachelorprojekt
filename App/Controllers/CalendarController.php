<?php

require_once __DIR__ . '/../Models/EventModel.php';
require_once __DIR__ . '/../../private/helpers.php';

class CalendarController
{
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

        $registeredEventIds = $isAdmin
            ? []
            : self::getRegisteredEventIds($_SESSION['user']['user_pk']);

        load_view('/calendar.php', [
            'calendarEvents' => $calendarEvents,
            'registeredEventIds' => $registeredEventIds,
            'currentPage' => 'calendar',
            'isProfileSection' => true,
        ]);
    }
}
