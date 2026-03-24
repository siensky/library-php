<?php
require 'db.php';
$books = pg_query($db, "SELECT * FROM books");
$authors = pg_query($db, "SELECT * FROM authors");

if (!$books) {
    die("Error in SQL query: " . pg_last_error($db));
}

if (!$authors){
    die("Error in SQL query: " . pg_last_error($db));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link rel="stylesheet" href="/css/index.css">
</head>
<body>
    <h1 id="library-title" >Library</h1>
<div class="books-section" >
<h1>Books</h1>
    <ul class="book-list">
    <?php while ($book = pg_fetch_assoc($books)): ?>

<li>
    <a href="/book.php?id=<?=$book['id']?>"><?=$book['title']?></a>
</li>

<?php endwhile; ?>
    </ul>
</div>

<div class="authors-section" >
<h1>Authors</h1>
    <ul class="author-list" >
    <?php while ($author = pg_fetch_assoc($authors)): ?>

<li>
    <a href="/author.php?id=<?=$author['id']?>"><?=$author['name']?></a>
</li>

<?php endwhile; ?>
    </ul>
</div>

</body>
</html>