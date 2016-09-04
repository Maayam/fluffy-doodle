-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 04 Septembre 2016 à 18:04
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
(3, '47.192745', '-1.536369', 'saucisse', 'saucisse'),
(4, '47.227962', '-1.596794', 'fritte', 'fritte'),
(5, '47.233324', '-1.519547', 'fritte mayo', 'fritte mayo'),
(6, '47.207441', '-1.604347', 'glace', 'glace'),
(7, '47.180146', '-1.566925', 'trou', 'trou'),
(8, '47.230760', '-1.555939', 'pour', 'pour'),
(9, '47.218169', '-1.501694', 'patate', 'patate');

-- --------------------------------------------------------

--
-- Structure de la table `plot_tag`
--

CREATE TABLE `plot_tag` (
  `plot_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `tag`
--

INSERT INTO `tag` (`id`, `name`) VALUES
(1, 'barbecue'),
(2, 'Nantes'),
(3, 'MOI'),
(4, 'tartine'),
(5, 'raclette'),
(6, 'projecteur'),
(7, 'indoor'),
(8, 'outdoor'),
(9, 'park'),
(10, 'tourist');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `plot`
--
ALTER TABLE `plot`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `plot_tag`
--
ALTER TABLE `plot_tag`
  ADD PRIMARY KEY (`plot_id`,`tag_id`),
  ADD KEY `IDX_1F32E28D680D0B01` (`plot_id`),
  ADD KEY `IDX_1F32E28DBAD26311` (`tag_id`);

--
-- Index pour la table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `plot`
--
ALTER TABLE `plot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `plot_tag`
--
ALTER TABLE `plot_tag`
  ADD CONSTRAINT `FK_1F32E28D680D0B01` FOREIGN KEY (`plot_id`) REFERENCES `plot` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_1F32E28DBAD26311` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
