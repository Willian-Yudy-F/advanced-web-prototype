<?php 
include 'db.php'; 
include 'navbar.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user'] = $user['name'];
        header("Location: index.php");
    } else {
        echo "<p style='color: red;'>Incorrect email or password.!</p>";
    }
}
?>
<form method="POST" style="font-family: Arial; max-width: 300px;">
    <h2>Login</h2>
    Email: <input type="email" name="email" required style="width: 100%; margin-bottom: 10px;">
    Senha: <input type="password" name="password" required style="width: 100%; margin-bottom: 20px;">
    <button type="submit" style="width: 104%;">Entrar</button>
</form>
