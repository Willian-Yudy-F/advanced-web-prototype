<?php
// search.php - Resultado da busca (texto + filtro de genero)
session_start();
include 'db.php';

$q     = isset($_GET['q'])     ? trim($_GET['q'])     : '';
$genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';

// Monta a query com prepared statement de acordo com os filtros enviados
if ($q !== '' && $genre !== '') {
    $sql   = "SELECT * FROM books
              WHERE (title LIKE ? OR author LIKE ? OR description LIKE ?)
                AND genre = ?
              ORDER BY title ASC";
    $like  = "%" . $q . "%";
    $stmt  = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $like, $like, $like, $genre);
} elseif ($q !== '') {
    $sql   = "SELECT * FROM books
              WHERE title LIKE ? OR author LIKE ? OR description LIKE ?
              ORDER BY title ASC";
    $like  = "%" . $q . "%";
    $stmt  = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
} elseif ($genre !== '') {
    $sql   = "SELECT * FROM books WHERE genre = ? ORDER BY title ASC";
    $stmt  = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $genre);
} else {
    // Sem filtro nenhum: volta para a home
    header("Location: index.php");
    exit();
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$count  = $result ? mysqli_num_rows($result) : 0;

// Monta um titulo amigavel para a secao de resultados
$parts = [];
if ($q     !== '') $parts[] = 'containing "' . htmlspecialchars($q) . '"';
if ($genre !== '') $parts[] = 'in ' . htmlspecialchars($genre);
$titleSuffix = $parts ? ' ' . implode(' ', $parts) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search results - Digital Bookstore</title>
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container">
    <div class="section-title">
        <h2>Search results<?php echo $titleSuffix; ?></h2>
        <span class="sub"><?php echo $count; ?> book<?php echo $count === 1 ? '' : 's'; ?> found</span>
    </div>

    <?php if ($count > 0): ?>
        <div class="book-grid">
            <?php while ($book = mysqli_fetch_assoc($result)):
                $imgPath = "images/" . $book['image'];
                $displayImage = (!empty($book['image']) && file_exists($imgPath))
                    ? $imgPath
                    : "https://placehold.co/400x500?text=No+Cover";

                // Media de estrelas (se a tabela reviews existir)
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

        <p style="text-align:center; margin: 20px 0 40px;">
            <a href="index.php" class="btn btn-outline" style="display:inline-block; padding: 10px 24px;">&larr; Back to all books</a>
        </p>

    <?php else: ?>
        <div class="empty-state">
            <h2>No books matched your search</h2>
            <p>Try different keywords or browse by genre.</p>
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
