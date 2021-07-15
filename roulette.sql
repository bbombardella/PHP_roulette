-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  ven. 21 mai 2021 à 20:02
-- Version du serveur :  8.0.18
-- Version de PHP :  7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `roulette`
--

-- --------------------------------------------------------

--
-- Structure de la table `game`
--

DROP TABLE IF EXISTS `game`;
CREATE TABLE IF NOT EXISTS `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bet` int(11) NOT NULL,
  `profit` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_game_player` (`player`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `game`
--

INSERT INTO `game` (`id`, `player`, `date`, `bet`, `profit`) VALUES
(1, 1, '2021-03-03 21:13:23', 2, 0),
(2, 1, '2021-03-03 21:13:30', 2, 0),
(3, 1, '2021-03-03 21:13:34', 2, 0),
(4, 1, '2021-03-03 21:13:37', 2, 4),
(5, 1, '2021-03-03 21:14:14', 2, 4),
(6, 1, '2021-03-03 21:14:22', 4, 8),
(7, 1, '2021-03-03 21:14:26', 5, 0),
(8, 1, '2021-03-03 21:14:29', 5, 10),
(9, 1, '2021-03-03 21:14:32', 5, 0),
(10, 1, '2021-03-03 21:14:35', 5, 10),
(11, 1, '2021-03-03 21:14:38', 5, 10),
(12, 1, '2021-03-10 16:15:06', 2, 0),
(13, 1, '2021-03-17 19:43:13', 5, 0),
(14, 1, '2021-03-17 19:44:57', 2, 0),
(15, 1, '2021-03-17 19:50:54', 5, 0),
(16, 1, '2021-03-17 19:53:10', 1, 2),
(17, 1, '2021-03-17 19:53:51', 1, 0),
(18, 1, '2021-03-17 19:53:58', 1, 2),
(19, 1, '2021-03-17 19:55:45', 1, 0),
(20, 1, '2021-03-17 19:55:48', 1, 0),
(24, 1, '2021-05-16 22:29:45', 45, 90);

-- --------------------------------------------------------

--
-- Structure de la table `player`
--

DROP TABLE IF EXISTS `player`;
CREATE TABLE IF NOT EXISTS `player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `money` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `player`
--

INSERT INTO `player` (`id`, `name`, `password`, `money`) VALUES
(1, 'bbombardella', '$2y$10$mN3WfpaVPcUEK5Mx7y76NOsN3W/qJs4FeYHgEspGJ11kbpY8h1mDO', 2548);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `game`
--
ALTER TABLE `game`
  ADD CONSTRAINT `fk_game_player` FOREIGN KEY (`player`) REFERENCES `player` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
