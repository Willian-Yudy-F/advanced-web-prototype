<?php 
include 'db.php'; 
include 'navbar.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Digital Bookstore - Home</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .book-grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .book-card { border: 1px solid #ddd; padding: 15px; width: 220px; text-align: center; border-radius: 8px; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .book-card img { width: 100%; height: 280px; object-fit: cover; border-radius: 5px; margin-bottom: 10px; }
        .btn { background: #007bff; color: white; padding: 10px; text-decoration: none; border-radius: 5px; display: block; margin-top: 10px; }
    </style>
</head>
<body>

    <h1 style="text-align:center;">Our Book Collection</h1>

    <div class="book-grid">
        <?php
        // Pega 4 livros aleatórios do seu banco
        $sql = "SELECT * FROM books ORDER BY RAND() LIMIT 4";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while($book = mysqli_fetch_assoc($result)) {
                
                // Caminho da imagem
                $imagePath = "images/" . $book['image'];
                $displayImage = (file_exists($imagePath) && !empty($book['image'])) ? $imagePath : "https://via.placeholder.com/200x300?text=No+Cover";

                echo "<div class='book-card'>";
                echo "<img src='$displayImage' alt='Book Cover'>";
                echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                echo "<p>Author: " . htmlspecialchars($book['author']) . "</p>";
                // Link para a página de detalhes
                echo "<a href='book.php?id=" . $book['id'] . "' class='btn'>View Details</a>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center;'>No books found. Please check your database table 'books'.</p>";
        }
        ?>
    </div>

</body>
</html>
