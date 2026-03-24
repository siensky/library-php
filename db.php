<?php
// 1. Kolla om vi har en DATABASE_URL (finns på Render)
$databaseUrl = getenv("DATABASE_URL");

if ($databaseUrl) {
    // Vi är på Render - använd den färdiga strängen direkt
    $db = pg_connect($databaseUrl);
} else {
    // 2. Vi är lokalt - ladda .env om den finns, annars hårdkoda för localhost
    if (file_exists(__DIR__ . '/.env')) {
        $env = parse_ini_file(__DIR__ . '/.env');
        $db = pg_connect("host=" . $env['DB_HOST'] . " port=" . $env['DB_PORT'] . " dbname=" . $env['DB_NAME'] . " user=" . $env['DB_USER'] . " password=" . $env['DB_PASS']);
    } else {
        // Fallback för lokal utveckling om .env saknas
        $db = pg_connect("host=localhost port=5432 dbname=library user=postgres password=password");
    }
}

if (!$db) {
    die("Could not connect to db: " . pg_last_error());
}
?>