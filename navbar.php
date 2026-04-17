<?php
// Navigation component — included on all pages
// session_start() must be called on the page that includes this file
?>
<style>
.main-nav { background: #1e293b; padding: 0 30px; display: flex; justify-content: space-between; align-items: center; font-family: 'Segoe UI', sans-serif; height: 60px; }
.main-nav .logo a { color: white; font-size: 1.3rem; font-weight: bold; text-decoration: none; }
.nav-links { list-style: none; display: flex; gap: 5px; margin: 0; padding: 0; align-items: center; }
.nav-links li { position: relative; }
.nav-links a { color: #cbd5e1; text-decoration: none; font-size: 0.9rem; padding: 8px 12px; border-radius: 4px; display: block; }
.nav-links a:hover { background: #334155; color: white; }
.nav-links .welcome-link { color: #38bdf8; font-weight: 600; }
.nav-links .logout-link { color: #f87171; }
.nav-links .register-btn { background: #2563eb; color: white !important; padding: 7px 14px; border-radius: 4px; }
.nav-links .register-btn:hover { background: #1d4ed8; }

/* Dropdown de Browse by Genre */
.dropdown { position: relative; }
.dropdown-menu { display: none; position: absolute; top: 100%; left: 0; background: white; border: 1px solid #e2e8f0; border-radius: 6px; min-width: 160px; box-shadow: 0 8px 20px rgba(0,0,0,0.12); z-index: 100; }
.dropdown-menu a { color: #374151 !important; padding: 10px 16px; font-size: 0.88rem; }
.dropdown-menu a:hover { background: #f1f5f9; color: #2563eb !important; }
.dropdown:hover .dropdown-menu { display: block; }
.dropdown > a::after { content: ' ▾'; font-size: 0.75rem; }
</style>

<nav class="main-nav">
    <div class="logo"><a href="index.php"> Digital Bookstore</a></div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>

        <!--  navigation with dropdowns -->
        <li class="dropdown">
            <a href="#">Browse</a>
            <div class="dropdown-menu">
                <a href="index.php">All Books</a>
                <a href="index.php?genre=Fiction">Fiction</a>
                <a href="index.php?genre=Non-Fiction">Non-Fiction</a>
                <a href="index.php?genre=Romance">Romance</a>
                <a href="index.php?genre=Thriller">Thriller</a>
                <a href="index.php?genre=Finance">Finance</a>
            </div>
        </li>

        <li><a href="sitemap.php">Sitemap</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- User is logged in: displays name, favourites and logout -->
            <li><a href="favorites.php">My Favourites</a></li>
            <li><a href="account.php" class="welcome-link">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
            <li><a href="logout.php" class="logout-link">Logout</a></li>
        <?php else: ?>
            <!-- User is not logged in: shows login and register -->
            <li><a href="login.php">Sign In</a></li>
            <li><a href="register.php" class="register-btn">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
