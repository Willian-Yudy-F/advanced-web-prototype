<?php 
// 1. Conexão e Configurações
include 'db.php'; 
include 'navbar.php'; 

// 2. Pegar o ID da URL de forma segura
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    // Se não houver ID, volta para a home
    header("Location: index.php");
    exit();
}

// 3. Buscar os dados do livro no banco
$sql = "SELECT * FROM books WHERE id = $id";
$result = mysqli_query($conn, $sql);
$book = mysqli_fetch_assoc($result);

// Se o livro não existir no banco
if (!$book) {
    echo "<h2>Book not found!</h2>";
    echo "<a href='index.php'>Back to Home</a>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $book['title']; ?> - Details</title>
    <style>
        .container { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: auto; }
        .breadcrumb { margin-bottom: 20px; color: #666; }
        .breadcrumb a { color: #007bff; text-decoration: none; }
        .book-details { display: flex; gap: 40px; }
        .book-image img { width: 300px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .book-info h1 { margin-top: 0; color: #333; }
        .metadata { margin-top: 20px; padding: 10px; background: #f9f9f9; border-left: 4px solid #007bff; font-size: 0.9em; color: #555; }
    </style>
</head>
<body>

<div class="container">
    <div class="breadcrumb">
        <a href="index.php">Home</a> > Details > <strong><?php echo $book['title']; ?></strong>
    </div>

    <div class="book-details">
        <div class="book-image">
            <?php
            // LÓGICA DO "PULO DO GATO" PARA IMAGEM
            $imagePath = "images/" . $book['image'];
            // Verifica se o arquivo existe na pasta; se não, usa um placeholder
            if (!file_exists($imagePath) || empty($book['image'])) {
                $imagePath = "https://via.placeholder.com/300x450?text=Cover+Unavailable";
            }
            ?>
            <img src="<?php echo $imagePath; ?>" alt="<?php echo $book['title']; ?>">
        </div>

        <div class="book-info">
            <h1><?php echo $book['title']; ?></h1>
            <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
            <p><strong>Description:</strong></p>
            <p><?php echo $book['description']; ?></p>

            <div class="metadata">
                <p><strong>Technical Information (Metadata):</strong></p>
                <ul>
                    <li>Product ID: #<?php echo $book['id']; ?></li>
                    <li>Reference: Digital_Media_<?php echo str_replace(' ', '_', $book['title']); ?></li>
                    <li>Status: Active Database</li>
                </ul>
            </div>
            
            <br>
            <a href="index.php" style="color: #007bff; text-decoration: none;">← Back to list</a>
        </div>
    </div>
</div>

</body>
</html>
