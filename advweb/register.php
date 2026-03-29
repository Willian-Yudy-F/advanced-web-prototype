<?php 
include 'db.php'; 
include 'navbar.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>Conta criada! <a href='login.php'>Faça login aqui</a></p>";
    }
}
?>
<form method="POST" style="font-family: Arial; max-width: 300px;">
    <h2>Registrar Usuário</h2>
    Nome: <input type="text" name="name" required style="width: 100%; margin-bottom: 10px;">
    Email: <input type="email" name="email" required style="width: 100%; margin-bottom: 10px;">
    Senha: <input type="password" name="password" required style="width: 100%; margin-bottom: 20px;">
    <button type="submit" style="width: 104%;">Criar Conta</button>
</form>