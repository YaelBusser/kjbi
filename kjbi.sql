-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 05 mars 2020 à 23:15
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `kjbi`
--

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

DROP TABLE IF EXISTS `amis`;
CREATE TABLE IF NOT EXISTS `amis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pseudo2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `demande` int(11) NOT NULL,
  `accept` int(11) NOT NULL,
  `refuse` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=303 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `amis`
--

INSERT INTO `amis` (`id`, `pseudo1`, `pseudo2`, `demande`, `accept`, `refuse`) VALUES
(295, 'aaaaaaaaaaaa', 'maclevine', 0, 1, 0),
(260, 'aaaaaaaaaaaa', 'test', 0, 1, 0),
(294, 'test', 'maclevine', 0, 1, 0),
(302, 'maclevine', 'maclevine', 0, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `publications`
--

DROP TABLE IF EXISTS `publications`;
CREATE TABLE IF NOT EXISTS `publications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `media` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `publications`
--

INSERT INTO `publications` (`id`, `pseudo`, `media`, `comment`) VALUES
(18, 'maclevine', 'maclevine2.png', 'ee'),
(17, 'maclevine', 'maclevine1.jpg', 'OOOK !!!');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mdp` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `miniature` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bio` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `email`, `mdp`, `avatar`, `miniature`, `etat`, `bio`) VALUES
(2, 'maclevine', 'mrmaclevine@gmail.com', '1f444844b1ca616009c2b0e3564fecc065872b5b', '2.png', '2.jpg', 'online', 'Lycéen de 17 ans, co-fondateur de KJBi !!!!\r\n'),
(3, 'test', 'maclevine5@gmail.com', '58e6b3a414a1e090dfc6029add0f3555ccba127f', '0.jpg', '3.jpg', 'offline', 'Je suis aussi con parfois !\r\n'),
(4, 'troooooooooooool', 'e@gmail.com', '58e6b3a414a1e090dfc6029add0f3555ccba127f', '4.jpg', '4.jpg', '', ''),
(6, 'aaaaaaaaaaaa', 'ak@gmail.com', '58e6b3a414a1e090dfc6029add0f3555ccba127f', '6.jpg', '0.jpg', 'offline', 'Je suis con parfois !');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
