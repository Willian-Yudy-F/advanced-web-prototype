<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "advanced_web";
$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) { die("Erro: " . mysqli_connect_error()); }
?>