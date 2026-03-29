<?php 
include 'db.php'; 
include 'navbar.php'; 
?>
<h1 style="font-family: Arial;">Nossa Coleção de Livros</h1>
<div style="display: flex; flex-wrap: wrap; gap: 20px; font-family: Arial;">
    <?php
    // Seleciona 4 livros aleatórios (Exigência do Brief)
    $sql = "SELECT * FROM books ORDER BY RAND() LIMIT 4";
    $result = mysqli_query($conn, $sql);

    while($book = mysqli_fetch_assoc($result)) {
        echo "<div style='border: 1px solid #ddd; padding: 15px; width: 220px; text-align: center;'>";
        echo "<img src='images/" . $book['image'] . "' style='width: 100%; height: 250px; object-fit: cover;'>";
        echo "<h3>" . $book['title'] . "</h3>";
        echo "<p>Autor: " . $book['author'] . "</p>";
        echo "<a href='book.php?id=" . $book['id'] . "' style='background: #007bff; color: white; padding: 8px 15px; text-decoration: none; border-radius: 3px;'>Ver Detalhes</a>";
        echo "</div>";
    }
    ?>
</div>