<?php
// Make sure session is started before any output
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Genres shown in the dropdown (keep in sync with the `genre` column of `books`)
$bt_genres = ['Finance', 'Investing', 'Business', 'Mindset', 'Lifestyle', 'Habits'];

// What is the current genre filter (used to highlight the active tab)
$bt_current = $_GET['genre'] ?? '';
?>
<link rel="stylesheet" href="style.css">

<!-- Main header: logo + search bar + account links -->
<header class="bt-header">
  <div class="container bar">
    <a href="index.php" class="bt-logo">Digital<span>Bookstore</span></a>

    <form action="search.php" method="GET" class="bt-search">
      <select name="genre">
        <option value="">All genres</option>
        <?php foreach ($bt_genres as $g): ?>
          <option value="<?php echo $g; ?>" <?php echo ($bt_current === $g ? 'selected' : ''); ?>>
            <?php echo $g; ?>
          </option>
        <?php endforeach; ?>
      </select>
      <input type="text" name="q" placeholder="Search by title, author or description..."
             value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
      <button type="submit">Search</button>
    </form>

    <nav class="bt-account">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="favorites.php">&#9825; Favourites</a>
        <a href="account.php">My Account</a>
        <a href="logout.php" class="cta">Logout</a>
      <?php else: ?>
        <a href="login.php">Sign In</a>
        <a href="register.php" class="cta">Create account</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<!-- Secondary bar with genre links (dropdown) -->
<nav class="bt-subnav">
  <div class="container menu">
    <a href="index.php" class="<?php echo ($bt_current === '' ? 'active' : ''); ?>">Home</a>

    <div class="dropdown">
      <a href="#" class="<?php echo (in_array($bt_current, $bt_genres) ? 'active' : ''); ?>">Genres &#9662;</a>
      <div class="dropdown-menu">
        <?php foreach ($bt_genres as $g): ?>
          <a href="index.php?genre=<?php echo urlencode($g); ?>"><?php echo $g; ?></a>
        <?php endforeach; ?>
      </div>
    </div>

    <?php foreach ($bt_genres as $g): ?>
      <a href="index.php?genre=<?php echo urlencode($g); ?>"
         class="<?php echo ($bt_current === $g ? 'active' : ''); ?>"><?php echo $g; ?></a>
    <?php endforeach; ?>

    <a href="sitemap.php">Sitemap</a>
  </div>
</nav>
