<?php
$env = parse_ini_file(__DIR__ . '/.env');

$db = pg_connect("host={$env['DB_HOST']} " .
"port={$env['DB_PORT']} " .
"dbname={$env['DB_NAME']} " .
"user={$env['DB_USER']} " .
"password={$env['DB_PASSWORD']}");

if(!$db){
    die("could not connect to db");
};