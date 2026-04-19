<?php
// index.php - Home com hero + filtro por genero + Bestsellers
session_start();
include 'db.php';

// Le o filtro de genero da URL (ex: index.php?genre=Finance)
$genreFilter = isset($_GET['genre']) ? trim($_GET['genre']) : '';

// Monta a query com prepared statement se tiver filtro
if ($genreFilter !== '') {
    $sql = "SELECT * FROM books WHERE genre = ? ORDER BY title ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $genreFilter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $sectionTitle = "Genre: " . htmlspecialchars($genreFilter);
    $sectionSub   = "Books in this category";
} else {
    // Sem filtro: pega 8 aleatorios como "Bestsellers"
    $result = mysqli_query($conn, "SELECT * FROM books ORDER BY RAND() LIMIT 8");
    $sectionTitle = "Bestsellers in Finance";
    $sectionSub   = "Inspiring books to grow your knowledge and wealth";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Bookstore - Quality books for everyone</title>
</head>
<body>

<?php include 'navbar.php'; ?>

<?php if ($genreFilter === ''): ?>
<!-- Hero banner so aparece na home (sem filtro) -->
<section class="bt-hero">
    <div class="container">
        <h1>Knowledge is wealth. Start here.</h1>
        <p>Curated finance &amp; mindset books reviewed by real readers. Find your next great read and build the habits that change your life.</p>
        <a href="#bestsellers" class="cta">Explore the collection</a>
    </div>
</section>
<?php endif; ?>

<main class="container" id="bestsellers">
    <div class="section-title">
        <h2><?php echo $sectionTitle; ?></h2>
        <span class="sub"><?php echo $sectionSub; ?></span>
    </div>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <div class="book-grid">
            <?php while ($book = mysqli_fetch_assoc($result)):
                $imgPath = "images/" . $book['image'];
                $displayImage = (!empty($book['image']) && file_exists($imgPath))
                    ? $imgPath
                    : "https://placehold.co/400x500?text=No+Cover";

                // Media de rating (se a tabela reviews existir)
                $avg = 0; $total = 0;
                $r = @mysqli_query($conn, "SELECT AVG(rating) a, COUNT(*) c FROM reviews WHERE book_id=" . (int)$book['id']);
                if ($r && ($row = mysqli_fetch_assoc($r))) {
                    $avg   = $row['a'] ? round($row['a'], 1) : 0;
                    $total = (int)$row['c'];
                }
                $fullStars = (int)round($avg);
            ?>
            <article class="book-card">
                <div class="cover">
                    <img src="<?php echo $displayImage; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                </div>

                <?php if (!empty($book['genre'])): ?>
                    <span class="genre-tag"><?php echo htmlspecialchars($book['genre']); ?></span>
                <?php endif; ?>

                <h3 class="title"><?php echo htmlspecialchars($book['title']); ?></h3>
                <p class="author">by <?php echo htmlspecialchars($book['author']); ?></p>

                <div class="rating">
                    <?php echo str_repeat("&#9733;", $fullStars) . str_repeat("&#9734;", 5 - $fullStars); ?>
                    <span class="count">(<?php echo $total; ?>)</span>
                </div>

                <div class="actions">
                    <a href="book.php?id=<?php echo $book['id']; ?>" class="btn btn-primary">View details</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form method="POST" action="toggle_favorite.php" style="margin:0;">
                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                            <button type="submit" class="btn btn-outline">&#9825; Add to Favourites</button>
                        </form>
                    <?php endif; ?>
                </div>
            </article>
            <?php endwhile; ?>
        </div>

        <?php if ($genreFilter !== ''): ?>
            <p style="text-align:center; margin: 20px 0 40px;">
                <a href="index.php" class="btn btn-outline" style="display:inline-block; padding: 10px 24px;">&larr; Back to all books</a>
            </p>
        <?php endif; ?>

    <?php else: ?>
        <div class="empty-state">
            <h2>No books found in this genre</h2>
            <p>Try another category or browse all books.</p>
            <p style="margin-top:20px;"><a href="index.php">Back to Home</a></p>
        </div>
    <?php endif; ?>
</main>

<footer class="bt-footer">
    <div class="container">
        &copy; <?php echo date('Y'); ?> Digital Bookstore &middot; Built by Luiza Miranda Gomes &amp; Willian Yudy Futema
        &middot; INT1059 Advanced Web
        <br>
        <a href="index.php">Home</a> |
        <a href="sitemap.php">Sitemap</a> |
        <a href="register.php">Create account</a>
    </div>
</footer>

</body>
</html>
