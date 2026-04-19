<?php
session_start();
include 'db.php';
include 'navbar.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM books WHERE id = $id";
$result = mysqli_query($conn, $sql);
$book = mysqli_fetch_assoc($result);

if (!$book) {
    echo "<h2>Book not found!</h2><a href='index.php'>Back to Home</a>";
    exit();
}

// Handle review submission
$review_error = '';
$review_success = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        $review_error = "You must be logged in to submit a review.";
    } else {
        $rating  = intval($_POST['rating']);
        $comment = trim($_POST['comment']);
        $user_id = $_SESSION['user_id'];
        if ($rating < 1 || $rating > 5) {
            $review_error = "Please select a rating.";
        } elseif (empty($comment)) {
            $review_error = "Please write a comment.";
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO reviews (book_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iiis", $id, $user_id, $rating, $comment);
            if (mysqli_stmt_execute($stmt)) {
                $review_success = "Review submitted successfully!";
            } else {
                $review_error = "Failed to submit review. Please try again.";
            }
        }
    }
}

// Fetch reviews
$reviews_result = mysqli_query($conn, "SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.book_id = $id ORDER BY r.created_at DESC");

// Average rating
$avg_result = mysqli_query($conn, "SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM reviews WHERE book_id = $id");
$avg_data = mysqli_fetch_assoc($avg_result);
$avg_rating = round($avg_data['avg_rating'], 1);
$total_reviews = $avg_data['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($book['title']); ?> - Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: auto; }
        .breadcrumb { margin-bottom: 20px; color: #666; }
        .breadcrumb a { color: #007bff; text-decoration: none; }
        .book-details { display: flex; gap: 40px; flex-wrap: wrap; }
        .book-image img { width: 250px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .book-info h1 { margin-top: 0; color: #333; }
        .metadata { margin-top: 20px; padding: 10px; background: #f9f9f9; border-left: 4px solid #007bff; font-size: 0.9em; color: #555; }
        .stars { color: #f5a623; font-size: 1.4em; }
        .avg-rating { font-size: 1.1em; color: #555; margin-bottom: 10px; }
        .reviews-section { margin-top: 40px; }
        .review-card { background: #f9f9f9; border-radius: 8px; padding: 15px; margin-bottom: 15px; border-left: 4px solid #007bff; }
        .review-card .reviewer { font-weight: bold; color: #333; }
        .review-card .review-date { font-size: 0.8em; color: #999; }
        .review-form { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-top: 20px; }
        .review-form h3 { margin-top: 0; }
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-start; gap: 4px; }
        .star-rating input { display: none; }
        .star-rating label { font-size: 2em; color: #ccc; cursor: pointer; }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label { color: #f5a623; }
        .review-form textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 1em; resize: vertical; }
        .btn-submit { margin-top: 10px; padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 1em; }
        .btn-submit:hover { background: #0056b3; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
        .login-prompt { background: #fff3cd; color: #856404; padding: 12px; border-radius: 6px; margin-top: 15px; }
        .login-prompt a { color: #856404; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="breadcrumb">
        <a href="index.php">Home</a> &gt; <strong><?php echo htmlspecialchars($book['title']); ?></strong>
    </div>

    <div class="book-details">
        <div class="book-image">
            <?php
            $imagePath = "images/" . $book['image'];
            if (!file_exists($imagePath) || empty($book['image'])) {
                $imagePath = "https://via.placeholder.com/250x370?text=Cover+Unavailable";
            }
            ?>
            <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
        </div>

        <div class="book-info">
            <h1><?php echo htmlspecialchars($book['title']); ?></h1>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
            <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
            <p><strong>Description:</strong></p>
            <p><?php echo htmlspecialchars($book['description']); ?></p>

            <div class="avg-rating">
                <?php if ($total_reviews > 0): ?>
                    <span class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php echo $i <= round($avg_rating) ? "★" : "☆"; ?>
                        <?php endfor; ?>
                    </span>
                    <strong><?php echo $avg_rating; ?>/5</strong> (<?php echo $total_reviews; ?> reviews)
                <?php else: ?>
                    <span style="color:#999">No reviews yet. Be the first!</span>
                <?php endif; ?>
            </div>

            <div class="metadata">
                <p><strong>Technical Information:</strong></p>
                <ul>
                    <li>Product ID: #<?php echo $book['id']; ?></li>
                    <li>Genre: <?php echo htmlspecialchars($book['genre']); ?></li>
                    <li>Status: Available</li>
                </ul>
            </div>

            <br><a href="index.php" style="color:#007bff;text-decoration:none;">← Back to list</a>
        </div>
    </div>

    <!-- REVIEWS SECTION -->
    <div class="reviews-section">
        <h2>Reviews (<?php echo $total_reviews; ?>)</h2>

        <?php if ($review_success): ?>
            <div class="alert-success"><?php echo $review_success; ?></div>
        <?php endif; ?>
        <?php if ($review_error): ?>
            <div class="alert-error"><?php echo $review_error; ?></div>
        <?php endif; ?>

        <!-- Review Form -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="review-form">
            <h3>Write a Review</h3>
            <form method="POST">
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5">
                    <label for="star5">★</label>
                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4">★</label>
                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3">★</label>
                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2">★</label>
                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1">★</label>
                </div>
                <br>
                <textarea name="comment" rows="4" placeholder="Share your thoughts about this book..."></textarea>
                <br>
                <button type="submit" name="submit_review" class="btn-submit">Submit Review</button>
            </form>
        </div>
        <?php else: ?>
        <div class="login-prompt">
            <a href="login.php">Sign in</a> to write a review.
        </div>
        <?php endif; ?>

        <!-- Display Reviews -->
        <?php if ($total_reviews > 0): ?>
            <?php while ($review = mysqli_fetch_assoc($reviews_result)): ?>
            <div class="review-card">
                <div class="reviewer">
                    <?php echo htmlspecialchars($review['name']); ?>
                    <span class="stars" style="font-size:1em;">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php echo $i <= $review['rating'] ? "★" : "☆"; ?>
                        <?php endfor; ?>
                    </span>
                    <span class="review-date"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                </div>
                <p><?php echo htmlspecialchars($review['comment']); ?></p>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color:#999;margin-top:20px;">No reviews yet for this book.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
