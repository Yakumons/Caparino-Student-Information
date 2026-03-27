-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2026 at 06:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `student_number` varchar(20) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `student_number`, `lastname`, `firstname`, `email`, `phone`, `address`, `created_at`) VALUES
(7, NULL, '240111191', 'Caparino', 'Cristine Joy', 'cristinejoycaparino@gmail.com', '9917739904', 'Caloocan City', '2026-03-27 17:12:14'),
(8, NULL, '240109824', 'Esperanza', 'Dustin Alyson', 'allysondustinesperanza1234@gmail.com', '9757979134', 'Quezon City', '2026-03-27 17:12:14'),
(9, NULL, '240108568', 'Fry', 'Gian Christian', 'frygianchristianpastee@gmail.com', '9862843179', 'Caloocan City', '2026-03-27 17:12:14'),
(10, NULL, '240110807', 'Jaropohop', 'Arnel', 'arneljaropohop13@gmail.com', '9457152833', 'Quezon City', '2026-03-27 17:12:14'),
(11, NULL, '240109701', 'Fernandez', 'Jasmine Marie', 'jasminemariefernandez08@gmail.com', '9352333517', 'Caloocan City', '2026-03-27 17:12:14'),
(12, NULL, '240108641', 'Eclarte', 'Hannah Kate', 'katelclarte10@gmail.com', '9953434914', 'Quezon City', '2026-03-27 17:12:14'),
(13, NULL, '240111136', 'Gonzaga', 'Diego Rey', 'diegonzaga123@gmail.com', '9482110966', 'Caloocan City', '2026-03-27 17:12:14'),
(14, NULL, '240109143', 'Copuz', 'Juan Emmanuel', 'copuzemmanuel023@gmail.com', '9957634003', 'Caloocan City', '2026-03-27 17:12:14'),
(15, NULL, '240113524', 'Patlano', 'Hedric', 'hedricpatlano31@gmail.com', '9689976463', 'Quezon City', '2026-03-27 17:12:14'),
(16, NULL, '240100946', 'Verbo', 'Mark Stephen', 'bachaw@gmail.com', '9393520113', 'Caloocan City', '2026-03-27 17:12:14'),
(17, NULL, '240100999', 'Laroa', 'John Cristian', 'cristianlorao9@gmail.com', '9929862859', 'Quezon City', '2026-03-27 17:12:14'),
(18, NULL, '240112154', 'Sarte', 'Nash', 'nashsarte5@gmail.com', '9164510134', 'Quezon City', '2026-03-27 17:12:14'),
(19, NULL, '240110220', 'Lazaro', 'John Christopher', 'christopherlazaro036@gmail.com', '9924089105', 'Valenzuela City', '2026-03-27 17:12:14'),
(20, NULL, '240106156', 'Rabaya', 'Avelino', 'avelinorabaya17@gmail.com', '9934835372', 'Caloocan City', '2026-03-27 17:12:14'),
(21, NULL, '240110140', 'Espinosa', 'Ronald James', 'espinosaron@gmail.com', '9917812641', 'Caloocan City', '2026-03-27 17:12:14'),
(22, NULL, '240118137', 'Apa-ap', 'Charitie', 'charitieapaap4@gmail.com', '9389463341', 'Caloocan City', '2026-03-27 17:12:14'),
(23, NULL, '240112787', 'Tubeo', 'Dharen Daves', 'dharendaves@gmail.com', '9707577810', 'Quezon City', '2026-03-27 17:12:14'),
(24, NULL, '240113829', 'Mancol', 'Denher John', 'denher08@gmail.com', '9830111418', 'Caloocan City', '2026-03-27 17:12:14'),
(25, NULL, '240116839', 'Bagaipo', 'Roniel Dream', 'ronieldreambagaipo@gmail.com', '9087928088', 'Quezon City', '2026-03-27 17:12:14'),
(26, NULL, '240113837', 'Ranola', 'Cassandramher', 'cassandramheranola@gmail.com', '9988230088', 'Quezon City', '2026-03-27 17:12:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role_id`, `created_at`) VALUES
(1, 'admin', 'admin@student.com', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 1, '2026-03-27 15:56:03'),
(4, 'john', 'johns@student.com', '703b0a3d6ad75b649a28adde7d83c6251da457549263bc7ff45ec709b0a8448b', 2, '2026-03-27 16:56:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_number` (`student_number`),
  ADD KEY `idx_students_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_roles` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
