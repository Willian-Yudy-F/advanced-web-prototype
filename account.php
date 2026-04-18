<?php
// User account page — update profile, password and manage reviews
session_start();
include 'db.php';

// Redirects to the login page if you are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid     = $_SESSION['user_id'];
$message = '';

// Processes the form fields
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        // Update your username and email address
        $newUser  = trim(mysqli_real_escape_string($conn, $_POST['username']));
        $newEmail = trim(mysqli_real_escape_string($conn, $_POST['email']));
        $conflict = mysqli_query($conn, "SELECT id FROM users WHERE (username='$newUser' OR email='$newEmail') AND id != $uid");
        if (mysqli_num_rows($conflict) > 0) {
            $message = 'error:Username or email is already in use.';
        } else {
            mysqli_query($conn, "UPDATE users SET username='$newUser', email='$newEmail' WHERE id=$uid");
            $_SESSION['username'] = $newUser;
            $message = 'success:Profile updated successfully!';
        }

    } elseif ($action === 'update_password') {
        // Update your password after checking the current one
        $current = $_POST['current_password'];
        $newPass = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];
        $userData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT password FROM users WHERE id=$uid"));

        if (!password_verify($current, $userData['password'])) {
            $message = 'error:Current password is incorrect.';
        } elseif ($newPass !== $confirm) {
            $message = 'error:New passwords do not match.';
        } elseif (strlen($newPass) < 6) {
            $message = 'error:Password must be at least 6 characters.';
        } else {
            $hashed = password_hash($newPass, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE id=$uid");
            $message = 'success:Password updated successfully!';
        }

    } elseif ($action === 'delete_review') {
        // Remove a user review
        $rid = (int)$_POST['review_id'];
        mysqli_query($conn, "DELETE FROM reviews WHERE id=$rid AND user_id=$uid");
        $message = 'success:Review deleted.';
    }
}

// Retrieve the user's current data
$user    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$uid"));
// Search for user reviews by book title
$reviews = mysqli_query($conn, "SELECT r.*, b.title AS book_title FROM reviews r 
    INNER JOIN books b ON r.book_id = b.id 
    WHERE r.user_id=$uid ORDER BY r.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Digital Bookstore</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; color: #1e293b; }
        .container { max-width: 860px; margin: 30px auto; padding: 0 20px; }
        h1 { font-size: 1.8rem; margin-bottom: 24px; }
        .card { background: white; border-radius: 10px; padding: 28px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); margin-bottom: 24px; }
        .card h2 { font-size: 1.15rem; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #e2e8f0; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-weight: 600; font-size: 0.88rem; color: #374151; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 11px 13px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.95rem; }
        .form-group input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .btn { padding: 11px 22px; border: none; border-radius: 6px; font-size: 0.93rem; font-weight: 600; cursor: pointer; }
        .btn-blue { background: #2563eb; color: white; }
        .btn-blue:hover { background: #1d4ed8; }
        .btn-red { background: white; color: #dc2626; border: 1px solid #dc2626; padding: 6px 13px; font-size: 0.83rem; font-weight: 600; cursor: pointer; border-radius: 5px; }
        .btn-red:hover { background: #fee2e2; }
        .msg-success { background: #d1fae5; color: #065f46; padding: 12px 15px; border-radius: 6px; margin-bottom: 18px; font-size: 0.9rem; }
        .msg-error   { background: #fee2e2; color: #dc2626;  padding: 12px 15px; border-radius: 6px; margin-bottom: 18px; font-size: 0.9rem; }
        .review-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 16px 0; border-bottom: 1px solid #f1f5f9; }
        .review-row:last-child { border-bottom: none; }
        .rev-title a { font-weight: 700; color: #2563eb; text-decoration: none; font-size: 0.95rem; }
        .rev-stars { color: #f59e0b; font-size: 0.92rem; margin: 4px 0; }
        .rev-comment { color: #475569; font-size: 0.88rem; line-height: 1.55; }
        .rev-date { color: #94a3b8; font-size: 0.78rem; margin-top: 4px; }
        .no-rev { color: #94a3b8; text-align: center; padding: 20px; font-size: 0.92rem; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    <h1>👤 My Account</h1>

    <?php if (!empty($message)):
        [$type, $msg] = explode(':', $message, 2);
        echo "<div class='" . ($type === 'success' ? 'msg-success' : 'msg-error') . "'>$msg</div>";
    endif; ?>

    <!-- Update your username and email address -->
    <div class="card">
        <h2>Update Profile</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update_profile">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" class="btn btn-blue">Save Changes</button>
        </form>
    </div>

    <!-- Change password -->
    <div class="card">
        <h2>Change Password</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update_password">
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" placeholder="Enter current password" required>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" placeholder="At least 6 characters" required>
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" placeholder="Repeat new password" required>
            </div>
            <button type="submit" class="btn btn-blue">Update Password</button>
        </form>
    </div>

    <!-- User reviews -->
    <div class="card">
        <h2>My Reviews</h2>
        <?php if (mysqli_num_rows($reviews) > 0): ?>
            <?php while ($rev = mysqli_fetch_assoc($reviews)): ?>
            <div class="review-row">
                <div>
                    <div class="rev-title"><a href="book.php?id=<?php echo $rev['book_id']; ?>"><?php echo htmlspecialchars($rev['book_title']); ?></a></div>
                    <div class="rev-stars"><?php for ($i = 1; $i <= 5; $i++) echo $i <= $rev['rating'] ? '★' : '☆'; ?></div>
                    <p class="rev-comment"><?php echo nl2br(htmlspecialchars($rev['comment'])); ?></p>
                    <p class="rev-date"><?php echo date('M d, Y', strtotime($rev['created_at'])); ?></p>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="delete_review">
                    <input type="hidden" name="review_id" value="<?php echo $rev['id']; ?>">
                    <button type="submit" class="btn-red" onclick="return confirm('Delete this review?')">Delete</button>
                </form>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-rev">You haven't written any reviews yet.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
