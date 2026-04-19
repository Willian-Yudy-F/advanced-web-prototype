-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 29, 2026 at 01:49 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `advanced_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `description`, `image`) VALUES
(1, 'The Psychology of Money', 'Morgan Housel', 'Explores how ego, preconceived notions, and pride influence financial decisions more than complex math.', 'book1.jpg'),
(2, 'Rich Dad Poor Dad', 'Robert Kiyosaki', 'The classic primer on the difference between working for money and having your money work for you.', 'book2.jpg'),
(3, 'The Millionaire Next Door', 'Thomas J. Stanley', 'A data-driven look at how the truly wealthy in America actually live (hint: it’s not in mansions).', 'book3.jpg'),
(4, 'Think and Grow Rich', 'Napoleon Hill', 'The grandfather of personal development; focuses on the psychological power of desire and persistence.', 'book4.jpg'),
(5, 'The Richest Man in Babylon', 'George S. Clason', 'Financial advice told through ancient parables, emphasizing the \"pay yourself first\" principle.', 'book5.jpg'),
(6, 'Your Money or Your Life', 'Vicki Robin', 'A transformative guide that helps you view money as \"life energy\" to achieve true independence.', 'book6.jpg'),
(7, 'Secrets of the Millionaire Mind', 'T. Harv Eker', 'Identifies your \"money blueprint\" and provides declarations to reset your mind for success.', 'book7.jpg'),
(8, 'I Will Teach You to Be Rich', 'Ramit Sethi', 'A practical, no-guilt 6-week program for \"rich life\" automation and conscious spending.', 'book8.jpg'),
(9, 'The Total Money Makeover', 'Dave Ramsey', 'A straightforward, aggressive plan to get out of debt and build a solid emergency fund.', 'book9.jpg'),
(10, 'Atomic Habits', 'James Clear', 'While not strictly about finance, it’s vital for building the small daily habits that lead to wealth.', 'book10.jpg'),
(11, 'Financial Freedom', 'Grant Sabatier', 'A roadmap to making more money in less time to reach \"time liberty\" as fast as possible.', 'book11.jpg'),
(12, 'The Simple Path to Wealth', 'JL Collins', 'Born from letters to his daughter, this book advocates for low-cost index fund investing.', 'book12.jpg'),
(13, 'Die With Zero', 'Bill Perkins', 'A counter-intuitive approach to optimizing life experiences over just hoarding cash.', 'book13.jpg'),
(14, 'Money: Master the Game', 'Tony Robbins', 'Interviews with the world’s greatest investors condensed into a 7-step blueprint.', 'book14.jpg'),
(15, 'The Almanac of Naval Ravikant', 'Eric Jorgenson', 'A collection of Naval’s wisdom on building wealth through leverage and specific knowledge.', 'book15.jpg'),
(16, 'Broke Millennial', 'Erin Lowry', 'A relatable guide for young adults to get their financial sh*t together, from debt to investing.', 'book16.jpg'),
(17, 'The 4-Hour Workweek', 'Timothy Ferriss', 'Challenges the \"deferred-life plan\" and teaches how to design a lifestyle of luxury now.', 'book17.jpg'),
(18, 'Playing with FIRE', 'Scott Rieckens', 'Documents the journey into the Financial Independence, Retire Early (FIRE) movement.', 'book18.jpg'),
(19, 'Profit First', 'Mike Michalowicz', 'Essential for entrepreneurs; flips the accounting formula to ensure the business stays healthy.', 'book19.jpg'),
(20, 'Mindset', 'Carol S. Dweck', 'The definitive book on \"growth vs. fixed\" mindsets, which is the foundation of all financial growth.', 'book20.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
