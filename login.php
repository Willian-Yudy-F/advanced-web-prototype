<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE email=?');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user['name'];
        header('Location: index.php');
        exit();
    } else {
        $error = 'Incorrect email or password.';
    }
}
?>
<html><head><meta charset='UTF-8'><title>Sign In</title><link rel='stylesheet' href='style.css'></head>
<body>
<?php include 'navbar.php'; ?>
<div style='max-width:400px;margin:60px auto;padding:30px;background:white;border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.1)'>
<h2>Sign In</h2>
<?php if ($error): ?><p style='color:red'><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
<form method='POST'>
<label>Email:<br><input type='email' name='email' required style='width:100%;padding:10px;margin:8px 0 16px;border:1px solid #ddd;border-radius:6px'></label>
<label>Password:<br><input type='password' name='password' required style='width:100%;padding:10px;margin:8px 0 16px;border:1px solid #ddd;border-radius:6px'></label>
<button type='submit' style='width:100%;padding:12px;background:#2563eb;color:white;border:none;border-radius:6px;font-size:1em;cursor:pointer'>Sign In</button>
</form>
<p style='text-align:center;margin-top:15px'><a href='register.php'>Create account</a></p>
</div>
</body></html>
