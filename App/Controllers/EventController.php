<?php

require_once __DIR__ . '/../Models/EventModel.php';

class EventController {

    public static function getAll(): array {
        return array_map([self::class, 'formatDates'], EventModel::getAll());
    }

    public static function getLatest(int $limit = 3): array {
        return array_map([self::class, 'formatDates'], EventModel::getLatest($limit));
    }

    public static function getById(string $id): array|false {
        $event = EventModel::getById($id);

        if (!$event) {
            return false;
        }

        return self::formatDates($event);
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
