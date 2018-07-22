-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 22, 2018 at 10:17 
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`` PROCEDURE `AddGeometryColumn` (`catalog` VARCHAR(64), `t_schema` VARCHAR(64), `t_name` VARCHAR(64), `geometry_column` VARCHAR(64), `t_srid` INT)  begin
  set @qwe= concat('ALTER TABLE ', t_schema, '.', t_name, ' ADD ', geometry_column,' GEOMETRY REF_SYSTEM_ID=', t_srid); PREPARE ls from @qwe; execute ls; deallocate prepare ls; end$$

CREATE DEFINER=`` PROCEDURE `DropGeometryColumn` (`catalog` VARCHAR(64), `t_schema` VARCHAR(64), `t_name` VARCHAR(64), `geometry_column` VARCHAR(64))  begin
  set @qwe= concat('ALTER TABLE ', t_schema, '.', t_name, ' DROP ', geometry_column); PREPARE ls from @qwe; execute ls; deallocate prepare ls; end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `disponibilite`
--

CREATE TABLE `disponibilite` (
  `id_dispo` smallint(4) UNSIGNED NOT NULL,
  `id_profil` smallint(4) UNSIGNED NOT NULL,
  `date_choix` date NOT NULL,
  `date_0` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_1` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_2` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_3` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_4` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_5` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_6` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_7` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_8` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_9` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_10` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_11` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_12` enum('0','1','2','3','4','5','6','7 et +') NOT NULL,
  `date_13` enum('0','1','2','3','4','5','6','7 et +') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `disponibilite`
--

INSERT INTO `disponibilite` (`id_dispo`, `id_profil`, `date_choix`, `date_0`, `date_1`, `date_2`, `date_3`, `date_4`, `date_5`, `date_6`, `date_7`, `date_8`, `date_9`, `date_10`, `date_11`, `date_12`, `date_13`) VALUES
(1, 1, '2018-07-20', '0', '0', '0', '2', '1', '1', '3', '3', '3', '3', '', '', '', ''),
(2, 2, '2018-07-20', '2', '2', '2', '2', '1', '1', '1', '1', '1', '1', '2', '2', '2', '2'),
(3, 4, '2018-06-12', '1', '2', '1', '2', '1', '', '', '', '', '', '', '', '', ''),
(4, 5, '2018-06-06', '1', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `jonction_profil_dispo`
--

CREATE TABLE `jonction_profil_dispo` (
  `id_jonction_pd` int(11) NOT NULL,
  `id_profil` int(11) NOT NULL,
  `intervalle_date` tinyint(2) UNSIGNED NOT NULL,
  `place` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jonction_profil_organisation`
--

CREATE TABLE `jonction_profil_organisation` (
  `id_jonction_po` mediumint(6) UNSIGNED NOT NULL,
  `id_profil` smallint(4) UNSIGNED NOT NULL,
  `id_orga` smallint(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jonction_profil_organisation`
--

INSERT INTO `jonction_profil_organisation` (`id_jonction_po`, `id_profil`, `id_orga`) VALUES
(51, 34, 2),
(68, 1, 5),
(69, 1, 1),
(70, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jonction_profil_reseau`
--

CREATE TABLE `jonction_profil_reseau` (
  `id_jonction_pr` mediumint(6) UNSIGNED NOT NULL,
  `id_profil_inf` smallint(4) UNSIGNED NOT NULL,
  `id_profil_sup` smallint(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jonction_profil_reseau`
--

INSERT INTO `jonction_profil_reseau` (`id_jonction_pr`, `id_profil_inf`, `id_profil_sup`) VALUES
(1, 1, 2),
(2, 1, 3),
(3, 1, 4),
(4, 1, 5),
(5, 2, 3),
(6, 2, 4),
(7, 2, 5),
(8, 3, 4),
(9, 3, 5),
(10, 4, 5),
(11, 3, 7),
(12, 5, 7);

-- --------------------------------------------------------

--
-- Table structure for table `organisation`
--

CREATE TABLE `organisation` (
  `id_orga` smallint(4) UNSIGNED NOT NULL,
  `nom_orga` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `organisation`
--

INSERT INTO `organisation` (`id_orga`, `nom_orga`) VALUES
(1, 'Al-Manba'),
(2, 'Les informelles'),
(3, 'RESF'),
(4, 'Paroisse 10e'),
(5, 'Soviet'),
(32, 'Paroisse 10'),
(33, 'Jordi');

-- --------------------------------------------------------

--
-- Table structure for table `profil`
--

CREATE TABLE `profil` (
  `id_profil` smallint(4) UNSIGNED NOT NULL,
  `nom_prenom` varchar(40) DEFAULT NULL,
  `telephone` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `mdp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `profil`
--

INSERT INTO `profil` (`id_profil`, `nom_prenom`, `telephone`, `email`, `mdp`) VALUES
(1, 'Coralie N', 0600000001, 'coralien@email.fr', '$2y$10$vAo2EchWEB3.MsfZ2YMxhOjfvYrl6ZAtVI5gB56nxZWgQlK.youaC'),
(2, 'Emilia J', 0600000002, 'emiliaj@email.com', '$2y$10$nVbpkn8NROgAgn9Ri2EmbuvQojadZmjSZ2e6sSz8kFEz.EtOZM.Na'),
(3, 'Emmanuelle', 0600000003, 'emmanuelle@email.com', '$2y$10$lphTdmLeuodEJ4w9jT0luO4hVHlsV2TUOnIdnw5MlRkNqQcKfwY0e'),
(4, 'Etienne', 0700000003, 'etienne@gmail.com', '$2y$10$snsyPNTWETbhSEBNFD6yLePjEjR4814cKNASoi.zfn9MXjaYDMzea'),
(5, 'Fanny ', 0600000004, 'fanny@hotmail.fr', '$2y$10$TKfHOAFCha132/bzc8nlweqKT7.A3h6R5uaZMNMqexMx6LjsAAcsa'),
(6, 'Francesca', 0600000005, 'francesca@yahoo.fr', '$2y$10$KjjTFiZTFihp7YiB/PpO3.s3LC3A973wnxLYZEJw/jq1tSfLXExRG'),
(45, 'Jonathan', 0600000006, 'jonathan@hotmail.com', '$2y$10$vAvLtqcr0COeTW0MV0PHKe2za0RnQwhlTtvV0SZsDjOHZJd3F2pYa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `disponibilite`
--
ALTER TABLE `disponibilite`
  ADD PRIMARY KEY (`id_dispo`);

--
-- Indexes for table `jonction_profil_organisation`
--
ALTER TABLE `jonction_profil_organisation`
  ADD PRIMARY KEY (`id_jonction_po`);

--
-- Indexes for table `jonction_profil_reseau`
--
ALTER TABLE `jonction_profil_reseau`
  ADD PRIMARY KEY (`id_jonction_pr`);

--
-- Indexes for table `organisation`
--
ALTER TABLE `organisation`
  ADD PRIMARY KEY (`id_orga`);

--
-- Indexes for table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id_profil`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `disponibilite`
--
ALTER TABLE `disponibilite`
  MODIFY `id_dispo` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `jonction_profil_organisation`
--
ALTER TABLE `jonction_profil_organisation`
  MODIFY `id_jonction_po` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `jonction_profil_reseau`
--
ALTER TABLE `jonction_profil_reseau`
  MODIFY `id_jonction_pr` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `organisation`
--
ALTER TABLE `organisation`
  MODIFY `id_orga` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
