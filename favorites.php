<?php
// User's favourites page
session_start();
include 'db.php';

// Redirects to the login page if you are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];

// Remove a book from your favourites if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $bookId = (int)$_POST['remove_id'];
    mysqli_query($conn, "DELETE FROM favorites WHERE user_id=$uid AND book_id=$bookId");
    header("Location: favorites.php");
    exit();
}

// Search for all the user's favourite books
$result = mysqli_query($conn, "SELECT b.* FROM books b 
    INNER JOIN favorites f ON b.id = f.book_id 
    WHERE f.user_id = $uid 
    ORDER BY f.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favourites - Digital Bookstore</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; color: #1e293b; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        h1 { font-size: 1.8rem; margin-bottom: 25px; }
        .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 24px; }
        .book-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; transition: box-shadow 0.2s; }
        .book-card:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.1); transform: translateY(-2px); }
        .book-card img { width: 100%; height: 265px; object-fit: contain; margin-bottom: 12px; }
        .genre-tag { background: #eff6ff; color: #2563eb; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
        .book-card h3 { font-size: 0.97rem; margin: 10px 0 5px; }
        .book-card .author { color: #64748b; font-size: 0.84rem; margin-bottom: 14px; }
        .btn-view { display: block; text-align: center; padding: 9px; background: #2563eb; color: white; border-radius: 5px; text-decoration: none; font-weight: 600; font-size: 0.88rem; margin-bottom: 8px; }
        .btn-remove { display: block; width: 100%; padding: 9px; background: white; color: #dc2626; border: 1px solid #dc2626; border-radius: 5px; font-size: 0.88rem; font-weight: 600; cursor: pointer; }
        .btn-remove:hover { background: #fee2e2; }
        .empty { text-align: center; padding: 80px 20px; color: #94a3b8; }
        .empty h2 { font-size: 1.4rem; margin-bottom: 10px; }
        .empty a { color: #2563eb; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    <h1>❤️ My Favourites</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="book-grid">
            <?php while ($book = mysqli_fetch_assoc($result)):
                $imgPath = "images/" . $book['image'];
                $img = (!empty($book['image']) && file_exists($imgPath)) ? $imgPath : "https://placehold.co/200x300?text=No+Cover";
            ?>
            <div class="book-card">
                <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                <?php if (!empty($book['genre'])): ?>
                    <span class="genre-tag"><?php echo htmlspecialchars($book['genre']); ?></span>
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                <p class="author">By <?php echo htmlspecialchars($book['author']); ?></p>
                <a href="book.php?id=<?php echo $book['id']; ?>" class="btn-view">View Details</a>
                <form method="POST">
                    <input type="hidden" name="remove_id" value="<?php echo $book['id']; ?>">
                    <button type="submit" class="btn-remove">Remove from Favourites</button>
                </form>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty">
            <h2>Your favourites list is empty</h2>
            <p>Browse books and click "Add to Favourites" to save them here.</p><br>
            <a href="index.php">Browse Books →</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
