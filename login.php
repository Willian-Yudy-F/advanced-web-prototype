<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email); // "s" = string
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
    
    if ($user = mysqli_fetch_assoc($result)) {
        // Verifica a senha usando o hash seguro
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
?>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<head>
    <meta charset="UTF-8">
    <title>Digital Bookstore</title>
    <style>
        /* COLE O CÓDIGO AQUI DENTRO */
        :root {
            --primary: #2563eb;
            --dark: #1e293b;
            --light: #f8fafc;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        /* ... restante do código que te enviei ... */
    </style>
</head>
