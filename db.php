<?php
$host = "sql113.infinityfree.com";
$user = "if0_41704491";
$pass = "YOUR_PASSWORD_HERE";
$dbname = "if0_41704491_host";
$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) { die("Connection error: " . mysqli_connect_error()); }
?>
