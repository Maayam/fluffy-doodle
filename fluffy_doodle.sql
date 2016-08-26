-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 26 Août 2016 à 19:42
-- Version du serveur :  5.7.11
-- Version de PHP :  5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `fluffy_doodle`
--

-- --------------------------------------------------------

--
-- Structure de la table `plot`
--

CREATE TABLE `plot` (
  `id` int(11) NOT NULL,
  `lat` decimal(10,6) NOT NULL,
  `lng` decimal(10,6) NOT NULL,
  `name` varchar(63) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `plot`
--

INSERT INTO `plot` (`id`, `lat`, `lng`, `name`, `note`) VALUES
(1, '47.191173', '-1.549901', 'MY FIRST PLOT', 'hello world!'),
(6, '47.193122', '-1.548826', 'Random Plot...', 'I\'m random!'),
(7, '47.197166', '-1.541820', 'Random Plot...', 'I\'m random!'),
(8, '47.190401', '-1.569371', 'Random Plot...', 'I\'m random!'),
(9, '47.193492', '-1.563106', 'Random Plot...', 'I\'m random!'),
(10, '47.200549', '-1.544480', 'Random Plot...', 'I\'m random!');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `plot`
--
ALTER TABLE `plot`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `plot`
--
ALTER TABLE `plot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
