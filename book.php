<?php
require 'db.php';

$id = $_GET['id'];

$result = pg_query_params($db, "SELECT * FROM books WHERE id = $1", [$id]);
$book = pg_fetch_assoc($result);

if (!$book) {
    die("Book not found!");
}
?>

<!DOCTYPE html>
<html>
<head><title><?= htmlspecialchars($book['title']) ?></title></head>
<body>

    <h1><?= htmlspecialchars($book['title']) ?></h1>
    
    <h3>Description</h3>
    <p><?= htmlspecialchars($book['description']) ?></p>

    
    <h3>Loan this book</h3>
    <form action="loan.php" method="POST">
        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
        
        <label>Auth0 sub:</label>
        <input type="text" name="auth0_sub" required placeholder="Paste auth0 sub...">
        <button type="submit">Confirm Loan</button>
    </form>
</body>
</html>