# Digital Bookstore

A web application for browsing and reviewing books, built with PHP and MySQL.

## Technologies
- PHP 8.2
- MySQL
- HTML5 / CSS3
- XAMPP (local server)

## Requirements
- XAMPP (Apache + MySQL)
- PHP 8.x
- Web browser

## Installation

1. Clone the repository:
   git clone https://github.com/your-username/bookstore.git

2. Move the project folder to XAMPP:
   - Mac: /Applications/XAMPP/xamppfiles/htdocs/bookstore
   - Windows: C:\xampp\htdocs\bookstore

3. Import the database:
   - Open http://localhost/phpmyadmin
   - Create a database named advanced_web
   - Click Import and select the file advanced_web.sql

4. Configure the connection (db.php):
   - host: localhost
   - user: root
   - password: (empty)
   - database: advanced_web

5. Access the project:
   http://localhost/bookstore/index.php

## Features
- Home page with book listings
- Category filtering by genre
- Book detail page with description and metadata
- Star rating system (1-5 stars)
- User reviews
- User registration and login
- Favourites list
- Search functionality
- User account page

## Project Structure
- index.php — Home page
- book.php — Book detail and reviews
- login.php — User login
- register.php — User registration
- account.php — User account management
- favorites.php — User favourites
- search.php — Search results
- navbar.php — Navigation menu
- db.php — Database connection
- style.css — Stylesheet
- advanced_web.sql — Database export
