-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 01, 2018 at 01:38 
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
-- Table structure for table `jonction_profil_dispo`
--

CREATE TABLE `jonction_profil_dispo` (
  `id_jonction_pd` int(11) NOT NULL,
  `id_profil` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `nb_jours` tinyint(2) UNSIGNED NOT NULL,
  `nb_places` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jonction_profil_dispo`
--

INSERT INTO `jonction_profil_dispo` (`id_jonction_pd`, `id_profil`, `date_debut`, `nb_jours`, `nb_places`) VALUES
(7, 1, '2018-08-04', 1, 1),
(8, 2, '2018-07-30', 6, 2),
(9, 3, '2018-08-01', 7, 1),
(10, 2, '2018-08-02', 3, 2),
(11, 6, '2018-08-01', 7, 1),
(12, 5, '2018-08-02', 3, 2),
(13, 5, '2018-07-31', 2, 1),
(14, 4, '2018-07-29', 5, 2),
(16, 45, '2018-07-31', 4, 2),
(17, 1, '2018-08-30', 2, 2);

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
(72, 45, 34),
(73, 1, 1),
(74, 45, 1),
(75, 4, 1),
(76, 1, 5),
(77, 45, 5),
(78, 2, 4),
(79, 5, 5);

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
(33, 'Jordi'),
(34, 'JIVEP');

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
-- Indexes for table `jonction_profil_dispo`
--
ALTER TABLE `jonction_profil_dispo`
  ADD PRIMARY KEY (`id_jonction_pd`);

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
-- AUTO_INCREMENT for table `jonction_profil_dispo`
--
ALTER TABLE `jonction_profil_dispo`
  MODIFY `id_jonction_pd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `jonction_profil_organisation`
--
ALTER TABLE `jonction_profil_organisation`
  MODIFY `id_jonction_po` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `jonction_profil_reseau`
--
ALTER TABLE `jonction_profil_reseau`
  MODIFY `id_jonction_pr` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `organisation`
--
ALTER TABLE `organisation`
  MODIFY `id_orga` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
