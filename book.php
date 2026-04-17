<?php
// Book details page — includes reviews, ratings and favourites
session_start();
include 'db.php';

// Validate the ID received via the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = (int)$_GET['id'];

$message = '';

// Processes POST requests (review or favourite)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if (!isset($_SESSION['user_id'])) {
        $message = 'error:You must be signed in to perform this action.';

    } elseif ($_POST['action'] === 'add_review') {
        $rating  = (int)$_POST['rating'];
        $comment = trim(mysqli_real_escape_string($conn, $_POST['comment']));
        $uid     = $_SESSION['user_id'];
        $uname   = mysqli_real_escape_string($conn, $_SESSION['username']);

        if ($rating < 1 || $rating > 5) {
            $message = 'error:Please select a star rating.';
        } elseif (empty($comment)) {
            $message = 'error:Please write your review comment.';
        } else {
          // Checks whether the user has already reviewed this book
            $exists = mysqli_query($conn, "SELECT id FROM reviews WHERE book_id=$id AND user_id=$uid");
            if (mysqli_num_rows($exists) > 0) {
                $message = 'error:You have already reviewed this book.';
            } else {
                mysqli_query($conn, "INSERT INTO reviews (book_id, user_id, username, rating, comment) VALUES ($id, $uid, '$uname', $rating, '$comment')");
                $message = 'success:Your review has been submitted. Thank you!';
            }
        }

    } elseif ($_POST['action'] === 'toggle_favorite') {
        $uid = $_SESSION['user_id'];
        // Toggle between adding to and removing from favourites
        $fav = mysqli_query($conn, "SELECT id FROM favorites WHERE user_id=$uid AND book_id=$id");
        if (mysqli_num_rows($fav) > 0) {
            mysqli_query($conn, "DELETE FROM favorites WHERE user_id=$uid AND book_id=$id");
        } else {
            mysqli_query($conn, "INSERT INTO favorites (user_id, book_id) VALUES ($uid, $id)");
        }
        header("Location: book.php?id=$id");
        exit();
    }
}

//  Toggle between adding to favourites and removing from favourites
$stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$book = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// If the book isn't available, go back to the home page
if (!$book) { header("Location: index.php"); exit(); }

// Check if it is in the logged-in user's favorites
$isFav = false;
if (isset($_SESSION['user_id'])) {
    $uid   = $_SESSION['user_id'];
    $check = mysqli_query($conn, "SELECT id FROM favorites WHERE user_id=$uid AND book_id=$id");
    $isFav = mysqli_num_rows($check) > 0;
}

// Search for reviews of the book
$reviews    = mysqli_query($conn, "SELECT * FROM reviews WHERE book_id=$id ORDER BY created_at DESC");
$avgData    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(rating) as avg, COUNT(*) as total FROM reviews WHERE book_id=$id"));
$avgRating  = round((float)$avgData['avg'], 1);
$totalRevs  = (int)$avgData['total'];

// Checks if the image exists — uses a placeholder if it isn't found
$imgPath     = "images/" . $book['image'];
$displayImg  = (!empty($book['image']) && file_exists($imgPath)) ? $imgPath : "https://placehold.co/300x400?text=No+Cover";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - Digital Bookstore</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; color: #1e293b; }
        .container { max-width: 1050px; margin: 30px auto; padding: 0 20px; }

        /* Breadcrumb */
        .breadcrumb { color: #64748b; font-size: 0.88rem; margin-bottom: 20px; }
        .breadcrumb a { color: #2563eb; text-decoration: none; }

        /* Book Detail */
        .book-detail { background: white; border-radius: 10px; padding: 35px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); display: flex; gap: 40px; margin-bottom: 30px; }
        .book-cover img { width: 250px; border-radius: 6px; box-shadow: 0 6px 18px rgba(0,0,0,0.15); }
        .book-info { flex: 1; }
        .genre-tag { background: #eff6ff; color: #2563eb; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; display: inline-block; margin-bottom: 12px; }
        .book-info h1 { font-size: 1.9rem; color: #1e293b; margin-bottom: 6px; }
        .book-author { color: #64748b; font-size: 1.05rem; margin-bottom: 16px; }

        /* Stars */
        .avg-stars { display: flex; align-items: center; gap: 8px; margin-bottom: 18px; }
        .stars { color: #f59e0b; font-size: 1.2rem; letter-spacing: 2px; }
        .avg-num { font-weight: 700; font-size: 1rem; }
        .rev-count { color: #94a3b8; font-size: 0.88rem; }

        .book-desc { color: #475569; line-height: 1.8; margin-bottom: 22px; }
        .metadata { background: #f8fafc; border-radius: 8px; padding: 15px 18px; margin-bottom: 22px; }
        .metadata p { font-size: 0.88rem; color: #64748b; margin: 5px 0; }
        .metadata p span { font-weight: 700; color: #1e293b; }

        /* Fav Butoon */
        .btn-fav { padding: 11px 22px; border-radius: 6px; font-size: 0.9rem; font-weight: 600; cursor: pointer; border: 2px solid #2563eb; transition: all 0.2s; }
        .btn-fav.active { background: #2563eb; color: white; }
        .btn-fav.inactive { background: white; color: #2563eb; }
        .btn-fav:hover { background: #2563eb; color: white; }

        /* Reviews seccion */
        .reviews-section { background: white; border-radius: 10px; padding: 35px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); }
        .reviews-section h2 { font-size: 1.3rem; margin-bottom: 22px; padding-bottom: 12px; border-bottom: 2px solid #e2e8f0; }

        .review-form-box { background: #f8fafc; border-radius: 8px; padding: 24px; margin-bottom: 28px; }
        .review-form-box h3 { font-size: 1.05rem; margin-bottom: 18px; }

        /* Star rating interactive (CSS pure) */
        .star-picker { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 4px; margin-bottom: 14px; }
        .star-picker input { display: none; }
        .star-picker label { font-size: 2rem; color: #d1d5db; cursor: pointer; transition: color 0.15s; }
        .star-picker input:checked ~ label,
        .star-picker label:hover,
        .star-picker label:hover ~ label { color: #f59e0b; }

        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-weight: 600; font-size: 0.88rem; margin-bottom: 5px; color: #374151; }
        .form-group textarea { width: 100%; padding: 11px 13px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.93rem; resize: vertical; min-height: 95px; font-family: inherit; }
        .form-group textarea:focus { outline: none; border-color: #2563eb; }
        .btn-submit { padding: 11px 26px; background: #2563eb; color: white; border: none; border-radius: 6px; font-size: 0.93rem; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { background: #1d4ed8; }

        .msg-success { background: #d1fae5; color: #065f46; padding: 12px 15px; border-radius: 6px; margin-bottom: 15px; font-size: 0.9rem; }
        .msg-error   { background: #fee2e2; color: #dc2626;  padding: 12px 15px; border-radius: 6px; margin-bottom: 15px; font-size: 0.9rem; }
        .login-notice { background: #eff6ff; color: #1e40af; padding: 14px; border-radius: 8px; text-align: center; font-size: 0.92rem; }
        .login-notice a { color: #2563eb; font-weight: 600; }

        /*  Review Cards */
        .review-card { padding: 18px 0; border-bottom: 1px solid #f1f5f9; }
        .review-card:last-child { border-bottom: none; }
        .rc-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; }
        .rc-user { font-weight: 700; color: #1e293b; font-size: 0.95rem; }
        .rc-stars { color: #f59e0b; font-size: 0.95rem; margin-left: 6px; }
        .rc-date { color: #94a3b8; font-size: 0.8rem; }
        .rc-text { color: #475569; line-height: 1.65; font-size: 0.92rem; }
        .no-reviews { color: #94a3b8; text-align: center; padding: 24px; }

        @media (max-width: 720px) {
            .book-detail { flex-direction: column; }
            .book-cover img { width: 100%; max-width: 260px; }
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">

    <!-- Nav Breadcrumb  -->
    <div class="breadcrumb">
        <a href="index.php">Home</a> &rsaquo;
        <?php if (!empty($book['genre'])): ?>
            <a href="index.php?genre=<?php echo urlencode($book['genre']); ?>"><?php echo htmlspecialchars($book['genre']); ?></a> &rsaquo;
        <?php endif; ?>
        <?php echo htmlspecialchars($book['title']); ?>
    </div>

    <!-- Book Detail -->
    <div class="book-detail">
        <div class="book-cover">
            <img src="<?php echo $displayImg; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
        </div>
        <div class="book-info">
            <?php if (!empty($book['genre'])): ?>
                <span class="genre-tag"><?php echo htmlspecialchars($book['genre']); ?></span>
            <?php endif; ?>

            <h1><?php echo htmlspecialchars($book['title']); ?></h1>
            <p class="book-author">By <?php echo htmlspecialchars($book['author']); ?></p>

            <!-- Average score -->
            <div class="avg-stars">
                <span class="stars">
                    <?php for ($i = 1; $i <= 5; $i++) echo $i <= round($avgRating) ? '★' : '☆'; ?>
                </span>
                <?php if ($totalRevs > 0): ?>
                    <span class="avg-num"><?php echo $avgRating; ?>/5</span>
                    <span class="rev-count">(<?php echo $totalRevs; ?> review<?php echo $totalRevs !== 1 ? 's' : ''; ?>)</span>
                <?php else: ?>
                    <span class="rev-count">No reviews yet</span>
                <?php endif; ?>
            </div>

            <?php if (!empty($book['description'])): ?>
                <p class="book-desc"><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
            <?php endif; ?>

            <!-- Metadata -->
            <div class="metadata">
                <p><span>Author:</span> <?php echo htmlspecialchars($book['author']); ?></p>
                <?php if (!empty($book['genre'])): ?>
                    <p><span>Genre:</span> <?php echo htmlspecialchars($book['genre']); ?></p>
                <?php endif; ?>
                <p><span>Product ID:</span> #<?php echo $book['id']; ?></p>
            </div>

            <!-- Favorite button -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST">
                    <input type="hidden" name="action" value="toggle_favorite">
                    <button type="submit" class="btn-fav <?php echo $isFav ? 'active' : 'inactive'; ?>">
                        <?php echo $isFav ? '❤️ Remove from Favourites' : '🤍 Add to Favourites'; ?>
                    </button>
                </form>
            <?php else: ?>
                <a href="login.php" style="display:inline-block;padding:11px 22px;border:2px solid #2563eb;color:#2563eb;border-radius:6px;text-decoration:none;font-weight:600;font-size:0.9rem;">Sign in to Add to Favourites</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
        <h2>Customer Reviews</h2>

        <!-- Review submission form -->
        <div class="review-form-box">
            <h3>Write a Review</h3>

            <?php if (!empty($message)):
                [$type, $msg] = explode(':', $message, 2);
                echo "<div class='" . ($type === 'success' ? 'msg-success' : 'msg-error') . "'>$msg</div>";
            endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST">
                    <input type="hidden" name="action" value="add_review">
                    <div class="form-group">
                        <label>Your Rating</label>
                        <!-- Star rating interativo (CSS puro, sem JavaScript) -->
                        <div class="star-picker">
                            <input type="radio" name="rating" id="s5" value="5"><label for="s5">★</label>
                            <input type="radio" name="rating" id="s4" value="4"><label for="s4">★</label>
                            <input type="radio" name="rating" id="s3" value="3"><label for="s3">★</label>
                            <input type="radio" name="rating" id="s2" value="2"><label for="s2">★</label>
                            <input type="radio" name="rating" id="s1" value="1"><label for="s1">★</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment">Your Review</label>
                        <textarea id="comment" name="comment" placeholder="Share your thoughts about this book..."></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Submit Review</button>
                </form>
            <?php else: ?>
                <div class="login-notice">
                    <a href="login.php">Sign in</a> to write a review and rate this book.
                </div>
            <?php endif; ?>
        </div>

        <!-- List of existing reviews -->
        <?php if (mysqli_num_rows($reviews) > 0): ?>
            <?php while ($rev = mysqli_fetch_assoc($reviews)): ?>
                <div class="review-card">
                    <div class="rc-header">
                        <div>
                            <span class="rc-user"><?php echo htmlspecialchars($rev['username']); ?></span>
                            <span class="rc-stars">
                                <?php for ($i = 1; $i <= 5; $i++) echo $i <= $rev['rating'] ? '★' : '☆'; ?>
                            </span>
                        </div>
                        <span class="rc-date"><?php echo date('M d, Y', strtotime($rev['created_at'])); ?></span>
                    </div>
                    <p class="rc-text"><?php echo nl2br(htmlspecialchars($rev['comment'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-reviews">No reviews yet. Be the first to review this book!</div>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
