<?php
// Site map — lists all available pages
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemap - Digital Bookstore</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; color: #1e293b; }
        .container { max-width: 700px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 10px; padding: 35px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); }
        h1 { font-size: 1.8rem; margin-bottom: 24px; }
        h2 { font-size: 0.78rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin: 20px 0 10px; }
        ul { list-style: none; }
        li { margin: 7px 0; }
        a { color: #2563eb; text-decoration: none; font-size: 0.93rem; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="card">
        <h1>Sitemap</h1>

        <h2>Main</h2>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="sitemap.php">Sitemap</a></li>
        </ul>

        <h2>Account</h2>
        <ul>
            <li><a href="login.php">Sign In</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="favorites.php">My Favourites</a></li>
            <li><a href="account.php">My Account</a></li>
        </ul>

        <h2>Browse by Genre</h2>
        <ul>
            <li><a href="index.php?genre=Fiction">Fiction</a></li>
            <li><a href="index.php?genre=Non-Fiction">Non-Fiction</a></li>
            <li><a href="index.php?genre=Romance">Romance</a></li>
            <li><a href="index.php?genre=Thriller">Thriller</a></li>
            <li><a href="index.php?genre=Finance">Finance</a></li>
        </ul>
    </div>
</div>
</body>
</html>
