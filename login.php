<?php
// Processes the user's login
session_start();
include 'db.php';

// If you are already logged in, you will be redirected to the home page
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Search for the user by username in the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // Login successful: saves ID and username for the session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Digital Bookstore</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; display: flex; flex-direction: column; min-height: 100vh; }
        .page-body { display: flex; justify-content: center; align-items: center; flex: 1; padding: 40px 20px; }
        .form-card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 420px; }
        .form-card h2 { color: #1e293b; margin-bottom: 6px; font-size: 1.8rem; }
        .form-card .subtitle { color: #64748b; margin-bottom: 28px; font-size: 0.95rem; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; color: #374151; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem; }
        .form-group input { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem; }
        .form-group input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .btn-submit { width: 100%; padding: 13px; background: #2563eb; color: white; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { background: #1d4ed8; }
        .error-box { background: #fee2e2; color: #dc2626; padding: 12px 14px; border-radius: 6px; margin-bottom: 18px; font-size: 0.9rem; }
        .bottom-link { text-align: center; margin-top: 18px; color: #64748b; font-size: 0.9rem; }
        .bottom-link a { color: #2563eb; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="page-body">
        <div class="form-card">
            <h2>Sign In</h2>
            <p class="subtitle">Welcome back! Please enter your details.</p>

            <?php if ($error): ?>
                <div class="error-box"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn-submit">Sign In</button>
            </form>
            <div class="bottom-link">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
        </div>
    </div>
</body>
</html>
