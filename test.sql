-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 15, 2018 at 02:57 
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
-- Table structure for table `dispos_hebergement`
--

CREATE TABLE `dispos_hebergement` (
  `id_dispos` int(11) NOT NULL,
  `id_profil` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `nb_nuits` tinyint(2) UNSIGNED NOT NULL,
  `nb_places` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dispos_hebergement`
--

INSERT INTO `dispos_hebergement` (`id_dispos`, `id_profil`, `date_debut`, `nb_nuits`, `nb_places`) VALUES
(17, 1, '2018-08-30', 2, 2),
(24, 45, '2018-08-17', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `infos_hebergement`
--

CREATE TABLE `infos_hebergement` (
  `id_infos` smallint(4) UNSIGNED NOT NULL,
  `id_profil` smallint(4) UNSIGNED NOT NULL,
  `preference` varchar(40) NOT NULL,
  `description` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `infos_hebergement`
--

INSERT INTO `infos_hebergement` (`id_infos`, `id_profil`, `preference`, `description`) VALUES
(1, 2, 'mineurs seulement', 'un canapé 2 places'),
(2, 4, 'femmes et familles', 'une chambre à part'),
(3, 45, 'mineurs isolés', 'un canapé'),
(4, 47, 'aucune', 'un matelas posé au sol');

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
(78, 2, 4),
(81, 1, 4),
(82, 47, 1);

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
(1, 'J-P', 0606060607, 'jp@email.fr', '$2y$10$Jx5RdTu1TisIhhjgVZBDt.STxZbSm5ADykMFqbga6B.rc4s7gMqhG'),
(2, 'Emilia J', 0600000002, 'emiliaj@email.com', '$2y$10$nVbpkn8NROgAgn9Ri2EmbuvQojadZmjSZ2e6sSz8kFEz.EtOZM.Na'),
(3, 'Emmanuelle', 0600000003, 'emmanuelle@email.com', '$2y$10$lphTdmLeuodEJ4w9jT0luO4hVHlsV2TUOnIdnw5MlRkNqQcKfwY0e'),
(4, 'Etienne', 0700000003, 'etienne@gmail.com', '$2y$10$snsyPNTWETbhSEBNFD6yLePjEjR4814cKNASoi.zfn9MXjaYDMzea'),
(5, 'Fanny ', 0600000004, 'fanny@hotmail.fr', '$2y$10$TKfHOAFCha132/bzc8nlweqKT7.A3h6R5uaZMNMqexMx6LjsAAcsa'),
(6, 'Francesca', 0600000005, 'francesca@yahoo.fr', '$2y$10$KjjTFiZTFihp7YiB/PpO3.s3LC3A973wnxLYZEJw/jq1tSfLXExRG'),
(45, 'Jonathan', 0600000007, 'jonathan@hotmail.com', '$2y$10$vAvLtqcr0COeTW0MV0PHKe2za0RnQwhlTtvV0SZsDjOHZJd3F2pYa'),
(46, 'Françoise', 0600000003, 'francoise@email.com', '$2y$10$2fp843a.U6b8HCpl1vdi3e2QTAA.5gKCrmM1y5IaotzAP0JRMCVkO'),
(47, 'Coralie', 0600000003, 'coralien@email.fr', '$2y$10$8P1tmvkySfz7D.NM.2AWH..WmyYuKLetfhy5oPsy/c7b9xzYnDOIq'),
(48, 'irene', 0101010101, 'irene@test.fr', '$2y$10$1NzVL5e8iDMHgIh5jb559OwLXocLnA48lQojrGkC45sfbupyOEUP6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dispos_hebergement`
--
ALTER TABLE `dispos_hebergement`
  ADD PRIMARY KEY (`id_dispos`);

--
-- Indexes for table `infos_hebergement`
--
ALTER TABLE `infos_hebergement`
  ADD PRIMARY KEY (`id_infos`);

--
-- Indexes for table `jonction_profil_organisation`
--
ALTER TABLE `jonction_profil_organisation`
  ADD PRIMARY KEY (`id_jonction_po`);

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
-- AUTO_INCREMENT for table `dispos_hebergement`
--
ALTER TABLE `dispos_hebergement`
  MODIFY `id_dispos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `infos_hebergement`
--
ALTER TABLE `infos_hebergement`
  MODIFY `id_infos` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `jonction_profil_organisation`
--
ALTER TABLE `jonction_profil_organisation`
  MODIFY `id_jonction_po` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
--
-- AUTO_INCREMENT for table `organisation`
--
ALTER TABLE `organisation`
  MODIFY `id_orga` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
