<?php
require 'db.php';

$book_id = $_POST['book_id'];
$auth0_sub = $_POST['auth0_sub'];

$due_at = date('Y-m-d H:i:s', strtotime('+14 days'));

if (!$book_id || !$auth0_sub) {
    die("Missing required information to process loan.");
}

$check_query = pg_query_params($db, "SELECT * FROM loans WHERE book_id = $1", [$book_id]);

$found_rows =  pg_num_rows($check_query);

if ($found_rows > 0){
    echo "<h3>book is borrowed </h3>";
    exit;
}

$insert = pg_query_params($db, "INSERT INTO loans (book_id, auth0_sub, due_at) VALUES ($1, $2, $3)", [$book_id, $auth0_sub, $due_at]);

if ($insert) {

    echo "<h3>Loan is registered. Return date: $due_at </h3>";
} else {
    echo "<h2>Something went wrong with the database.</h2>";
}



?>


