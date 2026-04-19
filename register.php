<?php
// Process the registration of a new user
session_start();
include 'db.php';

// If you are already logged in, you will be redirected to the home page
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check whether the username or email address is already in use
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username or email is already in use.";
        } else {
            // Encrypt the password before saving it to the database
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed')";
            if (mysqli_query($conn, $sql)) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Digital Bookstore</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; display: flex; flex-direction: column; min-height: 100vh; }
        .page-body { display: flex; justify-content: center; align-items: center; flex: 1; padding: 40px 20px; }
        .form-card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
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
            <h2>Create Account</h2>
            <p class="subtitle">Join the Digital Bookstore community.</p>

            <?php if ($error): ?>
                <div class="error-box"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Choose a username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="At least 6 characters" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat your password" required>
                </div>
                <button type="submit" class="btn-submit">Create Account</button>
            </form>
            <div class="bottom-link">
                Already have an account? <a href="login.php">Sign in here</a>
            </div>
        </div>
    </div>
</body>
</html>
