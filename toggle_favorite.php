<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || !isset($_POST['book_id'])) {
    header('Location: index.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['book_id']);
$check = mysqli_prepare($conn, 'SELECT id FROM favorites WHERE user_id=? AND book_id=?');
mysqli_stmt_bind_param($check, 'ii', $user_id, $book_id);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);
if (mysqli_stmt_num_rows($check) > 0) {
    $stmt = mysqli_prepare($conn, 'DELETE FROM favorites WHERE user_id=? AND book_id=?');
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $book_id);
    mysqli_stmt_execute($stmt);
} else {
    $stmt = mysqli_prepare($conn, 'INSERT INTO favorites (user_id, book_id) VALUES (?, ?)');
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $book_id);
    mysqli_stmt_execute($stmt);
}
header('Location: book.php?id=' . $book_id);
exit();
?>