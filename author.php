<?php
require 'db.php';

$author_id = $_GET['id'];

// 1. Get the Author's info
$author_res = pg_query_params($db, "SELECT * FROM authors WHERE id = $1", [$author_id]);
$author = pg_fetch_assoc($author_res);

// 2. Get all books where author_id matches
$books_res = pg_query_params($db, "SELECT * FROM books WHERE author_id = $1", [$author_id]);

if (!$author) {
    die("Author not found!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($author['name']) ?></title>
    <link rel="stylesheet" href="/css/author.css">
</head>
<body>

    <h1><?= htmlspecialchars($author['name']) ?></h1>
    
    <div class="author-description">
    <h3>About the Author</h3>
    <p><?= htmlspecialchars($author['description'] ?? 'No bio available.') ?></p>
    </div>


    
<div class="authors-books">
<h3>Books by this author:</h3>
    <ul>
        <?php while ($b = pg_fetch_assoc($books_res)): ?>
            <li>
                <a href="bok.php?id=<?= $b['id'] ?>"><?= htmlspecialchars($b['title']) ?></a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>