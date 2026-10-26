/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.4.8-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: jp
-- ------------------------------------------------------
-- Server version	11.4.8-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `joueurs`
--

DROP TABLE IF EXISTS `joueurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `joueurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_inscription` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `pseudo` (`pseudo`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `joueurs`
--

LOCK TABLES `joueurs` WRITE;
/*!40000 ALTER TABLE `joueurs` DISABLE KEYS */;
INSERT INTO `joueurs` VALUES
(1,'ddf','truc','$2y$12$84K6HuwxJhw234fpa4leOuqt8e9gn/LvBBr37fZM3bywKaLaVTBlm','2026-10-26 11:11:58'),
(2,'admin','admin','$2y$12$ANVWPNXJtgGIY6JYTIWgEeZWaxb3uai9HmU4UYggoFJOeYHlgq716','2026-10-26 11:13:18'),
(3,'truc','truc@truc.com','$2y$12$yyh98.Nx3jwAi2.djtl4p.Q2ww0dzxVeFehjpUTM/nqcTznlfKrRO','2026-10-26 14:31:07');
/*!40000 ALTER TABLE `joueurs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `niveaus`
--

DROP TABLE IF EXISTS `niveaus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `niveaus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `min_nombre` int(11) NOT NULL,
  `max_nombre` int(11) NOT NULL,
  `nb_essais` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `niveaus`
--

LOCK TABLES `niveaus` WRITE;
/*!40000 ALTER TABLE `niveaus` DISABLE KEYS */;
INSERT INTO `niveaus` VALUES
(1,'Facile',1,50,10),
(2,'Normal',1,100,8),
(3,'Difficile',1,500,10),
(4,'Expert',1,1000,10);
/*!40000 ALTER TABLE `niveaus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parties`
--

DROP TABLE IF EXISTS `parties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `parties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `joueur_id` int(11) NOT NULL,
  `niveau_id` int(11) NOT NULL,
  `nombre_secret` int(11) NOT NULL,
  `essais_utilises` int(11) NOT NULL,
  `resultat` enum('gagnee','perdue') NOT NULL,
  `debut_partie` datetime DEFAULT current_timestamp(),
  `fin_partie` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `joueur_id` (`joueur_id`),
  KEY `niveau_id` (`niveau_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parties`
--

LOCK TABLES `parties` WRITE;
/*!40000 ALTER TABLE `parties` DISABLE KEYS */;
INSERT INTO `parties` VALUES
(1,2,2,56,14,'perdue',NULL,NULL),
(2,2,2,19,0,'perdue',NULL,NULL),
(3,2,2,42,0,'perdue',NULL,NULL),
(4,2,2,61,0,'perdue',NULL,NULL),
(5,2,2,15,0,'perdue',NULL,NULL),
(6,2,2,48,0,'perdue',NULL,NULL),
(7,2,2,43,0,'perdue',NULL,NULL),
(8,2,2,50,0,'perdue',NULL,NULL),
(9,2,2,55,0,'perdue',NULL,NULL),
(10,2,2,25,0,'perdue',NULL,NULL),
(11,2,2,39,0,'perdue',NULL,NULL),
(12,2,1,15,0,'perdue',NULL,NULL),
(13,2,1,1,6,'perdue','2026-10-26 14:23:38',NULL),
(14,2,1,31,11,'perdue','2026-10-26 14:25:34','2026-10-26 14:26:22'),
(15,2,2,60,5,'gagnee','2026-10-26 14:26:28','2026-10-26 14:26:41'),
(16,2,1,25,1,'gagnee','2026-10-26 14:28:57','2026-10-26 14:29:05'),
(17,2,1,49,6,'gagnee','2026-10-26 14:29:10','2026-10-26 14:29:24'),
(18,2,1,32,2,'perdue','2026-10-26 14:30:40',NULL),
(19,2,1,9,6,'gagnee','2026-10-26 14:31:13','2026-10-26 14:31:22'),
(20,2,1,9,6,'gagnee','2026-10-26 14:36:14','2026-10-26 14:36:24'),
(21,2,2,18,3,'gagnee','2026-10-26 14:37:48','2026-10-26 14:37:56'),
(22,3,1,40,3,'gagnee','2026-10-26 14:38:32','2026-10-26 14:38:37');
/*!40000 ALTER TABLE `parties` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-10-26 14:45:42
