📚 Digital Bookstore - Advanced Web Project
A dynamic, database-driven web application developed for the Advanced Web course. This project simulates a digital bookstore where books are dynamically fetched from a MySQL database and rendered with a modern, responsive UI.


🚀 Key Features
• Dynamic Data Fetching: Automatically retrieves and displays book information from a relational database.
• Randomized Discovery: Implements a logic to fetch 4 random books on every refresh to enhance user discovery.
• Asset Management: Includes a backend check to verify if book covers exist on the server before rendering, preventing broken image links.
• Responsive Grid: A custom CSS Flexbox implementation for a seamless experience across desktop and mobile devices.
• Secure Output: Data is sanitized using htmlspecialchars() to prevent Cross-Site Scripting (XSS) vulnerabilities.


🛠️ Tech Stack
• Backend: PHP 8.x
• Database: MySQL
• Frontend: HTML5, CSS3 (Flexbox)
• Architecture: Modular design using PHP include for database connections and navigation components.


📂 Project Structure
• index.php: The main landing page containing the logic for the book grid.
• db.php: Database connection configuration.
• navbar.php: Reusable navigation component.
• /images: Directory for book cover assets.
