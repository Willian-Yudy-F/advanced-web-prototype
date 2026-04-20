# Digital Bookstore

A fully functional web application for browsing and reviewing books, built with PHP and MySQL.

## Live Demo
http://digitalbookstore.free.nf

## Technologies
- PHP 8.2
- MySQL
- HTML5 / CSS3
- XAMPP (local development)
- InfinityFree (hosting)

## Requirements
- XAMPP (Apache + MySQL)
- PHP 8.x
- Web browser

## Installation

1. Clone the repository:
   git clone https://github.com/Willian-Yudy-F/bookstore.git

2. Move the project folder to XAMPP:
   - Mac: /Applications/XAMPP/xamppfiles/htdocs/bookstore
   - Windows: C:\xampp\htdocs\bookstore

3. Import the database:
   - Open http://localhost/phpmyadmin
   - Create a database named advanced_web
   - Click Import and select the file advanced_web.sql

4. Configure the connection in db.php:
   - host: localhost
   - user: root
   - password: (empty)
   - database: advanced_web

5. Access the project:
   http://localhost/bookstore/index.php

## Features
- Home page with random book listings
- Category filtering by genre (Finance, Investing, Business, Mindset, Lifestyle, Habits)
- Book detail page with title, description and metadata
- Star rating system (1-5 stars)
- User reviews — submit and view reviews per book
- User registration and login system
- Favourites list — add, remove and manage favourites
- Search functionality
- User account page — update profile and manage reviews

## Project Structure
- index.php — Home page
- book.php — Book detail, reviews and rating
- login.php — User login
- register.php — User registration
- account.php — User account management
- favorites.php — User favourites list
- toggle_favorite.php — Add/remove favourites
- search.php — Search results
- navbar.php — Navigation menu
- db.php — Database connection
- style.css — Stylesheet
- advanced_web.sql — Database export

## Deployment
This project is hosted on InfinityFree.
Live URL: http://digitalbookstore.free.nf
