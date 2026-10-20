-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Temps de generació: 20-10-2026 a les 17:19:44
-- Versió del servidor: 9.4.0
-- Versió de PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de dades: `gallery`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `images`
--

CREATE TABLE `images` (
  `id` int NOT NULL,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Bolcament de dades per a la taula `images`
--

INSERT INTO `images` (`id`, `url`, `title`, `description`) VALUES
(1, 'via-lactea.jpg', 'Via lactea 2', ''),
(2, 'figueres.jpg', 'Figueres', ''),
(3, 'calella.jpg', 'Calella', ''),
(4, 'ny.jpg', 'Nova York', ''),
(5, 'platja.jpg', 'Fraser Island', ''),
(6, 'llac.jpg', 'Lake Moraine', '');

--
-- Índexs per a les taules bolcades
--

--
-- Índexs per a la taula `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `images`
--
ALTER TABLE `images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;
