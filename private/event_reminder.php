<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mailhelpers.php';
require_once __DIR__ . '/../App/Models/EventModel.php';

// Henter events som mangler reminder mail
$events = EventModel::getEventsNeedingReminder();

// Loop gennem events
foreach ($events as $event) {

    // Henter deltagere til eventet
    $participants = EventModel::getParticipantsByEventId($event['event_pk']);


    // Sender reminder mail til hver deltager
    foreach ($participants as $participant) {
        sendEventReminderMail(
            $participant['user_email'],
            $participant['user_name'],
            $event['event_title']
        );
    }

    // Marker reminder som sendt
    EventModel::markReminderAsSent($event['event_pk']);
}