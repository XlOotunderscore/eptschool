-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 23 mai 2024 à 00:35
-- Version du serveur : 8.4.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ept_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE IF NOT EXISTS `classe` (
  `code_cl` char(7) NOT NULL,
  `libelle_cl` varchar(30) NOT NULL,
  `effectif` int DEFAULT NULL,
  `code_fil` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`code_cl`),
  KEY `fk_classe_filiere` (`code_fil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `classe`
--

INSERT INTO `classe` (`code_cl`, `libelle_cl`, `effectif`, `code_fil`) VALUES
('ISTIC1', 'IGL L1', 58, 'STIC'),
('TSEAI1', 'IGL L2', 35, 'EAI'),
('TSGI1', 'IGL L3', 56, 'STGI'),
('TSSTIC1', 'IGL L3', 58, 'STIC');

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

DROP TABLE IF EXISTS `cours`;
CREATE TABLE IF NOT EXISTS `cours` (
  `code_cours` char(7) NOT NULL,
  `intitule` varchar(40) NOT NULL,
  `coefficient` int DEFAULT NULL,
  `code_ens` char(7) DEFAULT NULL,
  PRIMARY KEY (`code_cours`),
  KEY `fk_cours_enseignant` (`code_ens`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

DROP TABLE IF EXISTS `enseignant`;
CREATE TABLE IF NOT EXISTS `enseignant` (
  `code_ens` char(7) NOT NULL,
  `nom_ens` varchar(20) NOT NULL,
  `prenom_ens` varchar(50) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `mdp_ens` varchar(255) NOT NULL,
  `est_admin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`code_ens`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `enseignant`
--

INSERT INTO `enseignant` (`code_ens`, `nom_ens`, `prenom_ens`, `contact`, `mdp_ens`, `est_admin`) VALUES
('00EPT00', 'ASSALE', 'Adjé Louis', '0709670859', '$2y$10$Nq6SNfkimTAE9txPs2siKOmtfRyNdiw8aUBjrPjSfyZ6a7MUVU0YW', 1),
('00EPT32', 'IRIE', 'FOUA', '0777886730', '$2y$10$WkHIn5K0b8nfct6izqL86OG0HMRBflUBqdfv/m17h.QRQjRGdX4fy', 0);

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `matricule` char(10) NOT NULL,
  `nom_etu` varchar(20) NOT NULL,
  `prenom_etu` varchar(50) DEFAULT NULL,
  `sexe` varchar(1) DEFAULT NULL,
  `date_naiss` date DEFAULT NULL,
  `mdp_etu` varchar(255) NOT NULL,
  `code_cl` char(7) DEFAULT NULL,
  PRIMARY KEY (`matricule`),
  KEY `fk_etudiant_classe` (`code_cl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`matricule`, `nom_etu`, `prenom_etu`, `sexe`, `date_naiss`, `mdp_etu`, `code_cl`) VALUES
('19EPT01', 'Kouadjani', ' Kouakou Boris', 'M', '2003-02-25', '$2y$10$S4SorH7xSPXB54nMMLpFa.1Ln.pDaR03eJRru.icL94/LY6Xu4smS', 'ISTIC1'),
('19EPT04', 'Gnakale', 'Junior', 'M', '2004-01-16', '$2y$10$9AAQc3XHmegOC1hx7UroFuVCPhlYyDWUennySZx2j3sqn0GmIpKCS', 'ISTIC1'),
('19EPT11', 'Gnimassou', 'Othniel', 'M', '2004-05-14', '$2y$10$PbCLcP2d56lzg8uj6j0LQ.z2.9KyAUPobBMMda8hPSgtPwpr8BcwS', 'ISTIC1'),
('19EPT54', 'Nianzou', 'Boa Joseph', 'M', '2004-01-01', '$2y$10$hGqYlq5GbMBgyDG8lv942eLPne/cw1r357NFguszjDjg1uNDCjepq', 'TSSTIC1');

-- --------------------------------------------------------

--
-- Structure de la table `filiere`
--

DROP TABLE IF EXISTS `filiere`;
CREATE TABLE IF NOT EXISTS `filiere` (
  `code_fil` varchar(5) NOT NULL,
  `libelle_fil` varchar(30) NOT NULL,
  PRIMARY KEY (`code_fil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `filiere`
--

INSERT INTO `filiere` (`code_fil`, `libelle_fil`) VALUES
('EAI', 'IA'),
('STGI', 'Atelier Genie logiciel '),
('STGP', 'Sécurité Informatique'),
('STIC', 'Réseaux mobile');

-- --------------------------------------------------------

--
-- Structure de la table `suivre`
--

DROP TABLE IF EXISTS `suivre`;
CREATE TABLE IF NOT EXISTS `suivre` (
  `matricule` char(10) NOT NULL,
  `code_cours` char(7) NOT NULL,
  `note` float DEFAULT NULL,
  `date_ob` datetime NOT NULL,
  PRIMARY KEY (`matricule`,`code_cours`,`date_ob`),
  KEY `fk_suivre_cours` (`code_cours`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `classe`
--
ALTER TABLE `classe`
  ADD CONSTRAINT `classe_ibfk_1` FOREIGN KEY (`code_fil`) REFERENCES `filiere` (`code_fil`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `cours_ibfk_1` FOREIGN KEY (`code_ens`) REFERENCES `enseignant` (`code_ens`) ON DELETE CASCADE;

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `etudiant_ibfk_1` FOREIGN KEY (`code_cl`) REFERENCES `classe` (`code_cl`);

--
-- Contraintes pour la table `suivre`
--
ALTER TABLE `suivre`
  ADD CONSTRAINT `suivre_ibfk_1` FOREIGN KEY (`matricule`) REFERENCES `etudiant` (`matricule`) ON DELETE CASCADE,
  ADD CONSTRAINT `suivre_ibfk_2` FOREIGN KEY (`code_cours`) REFERENCES `cours` (`code_cours`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
