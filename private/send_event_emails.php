<?php

if (empty($argv[1])) {
    exit(1);
}

$data = json_decode(base64_decode($argv[1]), true);

if (!isset($data['participants'], $data['title'])) {
    exit(1);
}

require_once __DIR__ . '/mailhelpers.php';

foreach ($data['participants'] as $participant) {
    sendEventUpdatedMail(
        $participant['user_email'],
        $participant['user_name'],
        $data['title']
    );
}
