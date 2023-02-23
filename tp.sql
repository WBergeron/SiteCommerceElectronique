-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : jeu. 23 fév. 2023 à 18:38
-- Version du serveur : 10.10.2-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tp`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `idCategorie` int(11) NOT NULL AUTO_INCREMENT,
  `categorie` varchar(64) NOT NULL,
  PRIMARY KEY (`idCategorie`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`idCategorie`, `categorie`) VALUES
(1, 'Switchs'),
(2, 'Frames'),
(3, 'Touches'),
(4, 'Accessoires'),
(5, 'Tout Inclus');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `idProduit` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `price` double NOT NULL,
  `quantity_in_stock` int(11) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `categorie` int(11) DEFAULT NULL,
  PRIMARY KEY (`idProduit`),
  KEY `IDX_BE2DDF8C497DD634` (`categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`idProduit`, `name`, `price`, `quantity_in_stock`, `description`, `image_path`, `categorie`) VALUES
(1, 'Cherry MX Switch Set Red', 52.99, 500, 'Lot de 110 switches Red, 3 pins de Cherry. Il conviendrons a la plus part des gens. Les Cherry red sont des switches linéaires a 2mm de distance d\'activation pour un total de 44mm de distance. Pour être enclenché, elle a besoin de 45 cN de force. Pour plus de détail, veuillez consulté le site de Cherry: https://www.cherrymx.de/en/cherry-mx/mx-original/mx-red.html', '\\img\\switchs\\cherry-red-swicth-set_.jpg', 1),
(2, 'Cherry MX Switch Set Blue', 58.99, 300, 'Lot de 110 switches Blue, 3 pins de Cherry. Il conviendra a ceux qui recherche un click d\'activation. Les Cherry blue sont des switches a click a 2.2mm de distance d\'activation pour un total de 4mm de distance. Pour être enclenché, elle a besoin de 60 cN de force. Pour plus de détail, veuillez consulté le site de Cherry: https://www.cherrymx.de/en/cherry-mx/mx-original/mx-blue.html', 'img/switchs/cherry-blue-switch-set_.jpg', 1),
(3, 'Cherry MX Switch Set Brown', 52.99, 400, 'Lot de 110 switches Browns, 3 pins de Cherry. Il conviendra a ceux qui recherche un petit click d\'activation avec un petit kick back. Les Cherry browns sont des switches a click a 2mm de distance d\'activation pour un total de 4mm de distance. Pour être enclenché, elle a besoin de 55 cN de force. Pour plus de détail, veuillez consulté le site de Cherry: https://www.cherrymx.de/en/cherry-mx/mx-original/mx-brown.html', 'img/switchs/cherry-brown-switch-set_.jpg', 1),
(4, 'Cherry MX Switch Set Silver', 79.99, 200, 'Lot de 110 switches Silvers, 3 pins de Cherry. Il conviendrons a des gens qui demande des switches performante avec de la lumière RGB. Les Cherry silvers sont des switches linéaires a 1.2mm de distance d\'activation pour un total de 3.4mm de distance. Pour être enclenché, elle a besoin de 45 cN de force. Ajout de lumière RGB situer en haut de la switch. Pour plus de détail, veuillez consulté le site de Cherry: https://www.cherrymx.de/en/cherry-mx/mx-original/mx-speed-silver.html', 'img/switchs/cherry-rgb-silver-switch_.jpg', 1),
(5, 'Kailh Super Speed Switch Red', 65.99, 300, 'Lot de 110 switches Super Speed Red, 3 pins de Kailh. Ils conviendront a des gens qui demande des switches performante pour le gaming. Les Kailh super speed red sont des switches linéaires a 1.8mm de distance d\'activation pour un total de 4mm de distance. Pour être enclenché, elle a besoin de 37 cN de force.', 'img/switchs/kailh-super-speed-red-switch-set_.jpg', 1),
(6, 'Kailh Super Speed Switch Bronze', 72.99, 50, 'Lot de 110 switches Super Speed Bronze, 3 pins de Kailh. Ils conviendront a des gens qui demande des switches performante pour écrire rapidement. Les Kailh super speed bronze sont des switches à \"bumb\" a 1.4mm de distance d\'activation pour un total de 3.5mm de distance. Pour être enclenché, elle a besoin de 48 cN de force.', '\\img\\switchs\\kailh-super-speed-bronze-switch-set_.jpg', 1),
(7, 'Keychron Q3 QMK Custom Mechanical Keyboard', 164.99, 50, 'Clavier mécanique en \"hot-swap\", full aluminium Q3 de Keychron. Le clavier est muni du \"Double-Gasket\", une technologie d\'amortissement du clavier suite au click d\'une switch. Format 80% (TKL), couleur noir.', 'img/keyboards/Keychron-Q3-QMKVIA-Black.jpg', 2),
(8, 'Keychron Q1 QMK Custom Mechanical Keyboard V2', 159.99, 40, 'Clavier mécanique en \"hot-swap\", full aluminium Q1 de Keychron. Le clavier est muni du \"Double-Gasket\", une technologie d\'amortissement du clavier suite au click d\'une switch. Format 75%, couleur noir.', '\\img\\keyboards\\Keychron-Q1-QMK-V2-Black.jpg', 2),
(9, 'Keychron Q6 QMK Custom Mechanical Keyboard ISO Layout', 185.99, 30, 'Clavier mécanique en \"hot-swap\", full aluminium Q6 de Keychron. Le clavier est muni du \"Double-Gasket\", une technologie d\'amortissement du clavier suite au click d\'une switch. Format 100%, couleur noir. Le \"layout\" du clavier est ISO', 'img/keyboards/Keychron-Q6-QMK-Black.jpg', 2),
(10, 'Keychron Q9 QMK Custom Mechanical Keyboard Black', 120.99, 30, 'Clavier mécanique en \"hot-swap\", full aluminium Q9 de Keychron. Le clavier est muni du \"Double-Gasket\", une technologie d\'amortissement du clavier suite au click d\'une switch. Format 40%, couleur noir.', 'img/keyboards/Keychron-Q9-QMK-Black.jpg', 2),
(11, 'Keychron K2 Pro QMK/VIA Wireless Mechanical Keyboard', 99.99, 50, 'Clavier mécanique en \"hot-swap\", plastique K2 Pro de Keychron. Le clavier est muni de \"Bluetooth 5.1\" se qui permet de se connecter facilement. Format 75%, couleur noir.', 'img/keyboards/Keychron-K2-Pro-QMK-VIA-Black.jpg', 2),
(12, 'TUT Vacation keycaps SET', 81.99, 12, 'Set complet de touche style vacance de TUT. Elles sont produit par injection de couleur. Compatible avec les touches Cherry MX et similaire. Profile de touche Cherry. Couleur bleu foncé.', '\\img\\keycaps\\TutVacanceKeycaps.jpg', 3),
(13, 'EPBT YUKIHANA', 99.99, 4, 'Set complet de touche style hiver de ePBT. Elles sont produit par injection de couleur. Compatible avec les touches Cherry MX et similaire. Profile de touche Cherry. Couleur bleu clair et blanc.', 'img\\keycaps\\ePBTHiverKeycaps.jpg', 3),
(14, 'M7 GAME CONSOLE ARTISAN KEYCAP', 18.99, 48, 'Touche seule en forme de console de jeu fait par M7.\r\nFait d\'aluminium pour le console et de silicone pour les boutons. Pèse 40g. Compatible avec le profile de touche Cherry. RGB compatible face nord', 'img\\keycaps\\M7ConsoleGameKeycap.jpg', 3);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `FK_BE2DDF8C497DD634` FOREIGN KEY (`categorie`) REFERENCES `categories` (`idCategorie`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
