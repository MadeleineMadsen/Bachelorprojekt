<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mailhelpers.php';
require_once __DIR__ . '/../App/Models/EventModel.php';

$events = EventModel::getEventsNeedingReminder();

foreach ($events as $event) {
    $participants = EventModel::getParticipantsByEventId($event['event_pk']);

    foreach ($participants as $participant) {
        sendEventReminderMail(
            $participant['user_email'],
            $participant['user_name'],
            $event['event_title']
        );
    }

    EventModel::markReminderAsSent($event['event_pk']);
}