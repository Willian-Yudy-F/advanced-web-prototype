<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<p>1 - Iniciando...</p>";

session_start();
echo "<p>2 - Sessão OK</p>";

include 'db.php';
echo "<p>3 - DB OK</p>";

include 'navbar.php';
echo "<p>4 - Navbar OK</p>";

echo "<h2 style='color:green'>✅ Tudo funcionando!</h2>";
?>
