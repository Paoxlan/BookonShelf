-- MariaDB dump 10.19  Distrib 10.4.27-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: bookonshelf
-- ------------------------------------------------------
-- Server version	10.4.27-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `boek_boetes`
--

DROP TABLE IF EXISTS `boek_boetes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boek_boetes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gebruiker_id` int(11) NOT NULL,
  `boek_id` int(11) NOT NULL,
  `boete` double NOT NULL,
  `afbetaald` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `boek_boetes_boeken_null_fk` (`boek_id`),
  KEY `boek_boetes_gebruikers_null_fk` (`gebruiker_id`),
  CONSTRAINT `boek_boetes_boeken_null_fk` FOREIGN KEY (`boek_id`) REFERENCES `boeken` (`id`),
  CONSTRAINT `boek_boetes_gebruikers_null_fk` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruikers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boek_boetes`
--

LOCK TABLES `boek_boetes` WRITE;
/*!40000 ALTER TABLE `boek_boetes` DISABLE KEYS */;
INSERT INTO `boek_boetes` (`id`, `gebruiker_id`, `boek_id`, `boete`, `afbetaald`) VALUES (16,14,46,9.5,0);
/*!40000 ALTER TABLE `boek_boetes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boek_schrijvers`
--

DROP TABLE IF EXISTS `boek_schrijvers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boek_schrijvers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boek_id` int(11) NOT NULL,
  `schrijver_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `boek_schrijvers_pk` (`boek_id`,`schrijver_id`),
  KEY `boek_schrijvers_schrijvers_null_fk` (`schrijver_id`),
  CONSTRAINT `boek_schrijvers_boeken_null_fk` FOREIGN KEY (`boek_id`) REFERENCES `boeken` (`id`),
  CONSTRAINT `boek_schrijvers_schrijvers_null_fk` FOREIGN KEY (`schrijver_id`) REFERENCES `schrijvers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boek_schrijvers`
--

LOCK TABLES `boek_schrijvers` WRITE;
/*!40000 ALTER TABLE `boek_schrijvers` DISABLE KEYS */;
INSERT INTO `boek_schrijvers` (`id`, `boek_id`, `schrijver_id`) VALUES (19,36,5),(21,48,5),(27,57,2),(26,57,14);
/*!40000 ALTER TABLE `boek_schrijvers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boeken`
--

DROP TABLE IF EXISTS `boeken`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boeken` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(25) NOT NULL,
  `schrijver_id` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `isbn-nummer` varchar(13) NOT NULL,
  `taal_id` int(11) DEFAULT NULL,
  `pagina's` varchar(10) NOT NULL,
  `exemplaren` int(11) NOT NULL,
  `aantal_exemplaren` int(11) NOT NULL,
  `afbeelding` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `boeken_naam_uk` (`naam`),
  UNIQUE KEY `boeken_afbeelding_uk` (`afbeelding`),
  KEY `boeken_talen_null_fk` (`taal_id`),
  KEY `boeken_genres_null_fk` (`genre_id`),
  KEY `boeken_schrijvers_null_fk` (`schrijver_id`),
  CONSTRAINT `boeken_genres_null_fk` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`),
  CONSTRAINT `boeken_schrijvers_null_fk` FOREIGN KEY (`schrijver_id`) REFERENCES `schrijvers` (`id`),
  CONSTRAINT `boeken_talen_null_fk` FOREIGN KEY (`taal_id`) REFERENCES `talen` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boeken`
--

LOCK TABLES `boeken` WRITE;
/*!40000 ALTER TABLE `boeken` DISABLE KEYS */;
INSERT INTO `boeken` (`id`, `naam`, `schrijver_id`, `genre_id`, `isbn-nummer`, `taal_id`, `pagina's`, `exemplaren`, `aantal_exemplaren`, `afbeelding`) VALUES (33,'Carve the mark',3,1,'978025489122',2,'251',3,3,'images/boekimages/Niks.png'),(36,'Tour De France',2,3,'978248619476',3,'178',2,1,'images/boekimages/Niks.png'),(46,'The Window',1,1,'0000000000001',2,'174',4,3,'images/boekimages/Niks.jpg'),(48,'The Forest',14,6,'0000000000002',2,'127',3,2,'images/boekimages/Niks.png'),(57,'Niks',14,6,'0000000000007',1,'101',1,1,'images/boekimages/Niks.png');
/*!40000 ALTER TABLE `boeken` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gebruikers`
--

DROP TABLE IF EXISTS `gebruikers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gebruikers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `wachtwoord` varchar(60) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `voornaam` varchar(25) NOT NULL,
  `tussenvoegsel` varchar(25) DEFAULT NULL,
  `achternaam` varchar(25) NOT NULL,
  `woonplaats_id` int(11) DEFAULT NULL,
  `straat` varchar(30) NOT NULL,
  `huisnummer` varchar(6) NOT NULL,
  `postcode` varchar(7) NOT NULL,
  `geboortedatum` varchar(10) NOT NULL,
  `aantal_boetes` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gebruikers_email_uk` (`email`),
  KEY `gebruikers_rollen_null_fk` (`rol_id`),
  KEY `gebruikers_woonplaats_null_fk` (`woonplaats_id`),
  CONSTRAINT `gebruikers_rollen_null_fk` FOREIGN KEY (`rol_id`) REFERENCES `rollen` (`id`),
  CONSTRAINT `gebruikers_woonplaats_null_fk` FOREIGN KEY (`woonplaats_id`) REFERENCES `woonplaats` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gebruikers`
--

LOCK TABLES `gebruikers` WRITE;
/*!40000 ALTER TABLE `gebruikers` DISABLE KEYS */;
INSERT INTO `gebruikers` (`id`, `email`, `wachtwoord`, `rol_id`, `voornaam`, `tussenvoegsel`, `achternaam`, `woonplaats_id`, `straat`, `huisnummer`, `postcode`, `geboortedatum`, `aantal_boetes`) VALUES (12,'klant@outlook.com','$2y$10$TO21M2qLHJeWhMyAmaNSXezUeQ2BfccmoVgLf6PZprw0d2g4HlWgq',2,'kla','','nt',2,'Pietersenstraat','8B','2946 DP','2003-06-16',0),(14,'1@1','$2y$10$SpGIqhZgrtuR6O5PPx5VR.tWtsXSLGgwXmNR7OEJO1ivtg.WFGalG',2,'1','','2',5,'Zevenstraat','7','5927 PO','2001-06-29',1),(15,'2@2','$2y$10$KWEkh0L1vamIg6Dy1C05QeRpw5zIulFLuESITvAFxQamxYkm/cR5O',2,'2','@','2',4,'weet','25','3682 JX','2022-12-23',0),(19,'admin@outlook.com','$2y$10$lwZmKwde4VSa44S4f6rGF.rDdWxRZRydgs9GoOcMwrpHWLT1JgFPS',1,'admin','Is','Private',4,'Zevendestraat','34','8761 GF','1997-02-18',0),(21,'patrik.capcuch@hotmail.com','$2y$10$h1/Hxy4AWS4bdOpvwSmEwu68p7iRTqm069zxienkhd0eZDuP3vk02',2,'Patrik','','Capcuch',1,'zessenstraat','3A','6822 BX','2005-08-24',0);
/*!40000 ALTER TABLE `gebruikers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `geleende_boeken`
--

DROP TABLE IF EXISTS `geleende_boeken`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geleende_boeken` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gebruiker_id` int(11) NOT NULL,
  `boek_id` int(11) NOT NULL,
  `begindatum` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gebruiker_boeken_uk` (`boek_id`,`gebruiker_id`),
  KEY `gebruiker_boeken_gebruikers_null_fk` (`gebruiker_id`),
  CONSTRAINT `gebruiker_boeken_boeken_null_fk` FOREIGN KEY (`boek_id`) REFERENCES `boeken` (`id`),
  CONSTRAINT `gebruiker_boeken_gebruikers_null_fk` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruikers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `geleende_boeken`
--

LOCK TABLES `geleende_boeken` WRITE;
/*!40000 ALTER TABLE `geleende_boeken` DISABLE KEYS */;
INSERT INTO `geleende_boeken` (`id`, `gebruiker_id`, `boek_id`, `begindatum`) VALUES (83,14,46,'23-01-19');
/*!40000 ALTER TABLE `geleende_boeken` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genres`
--

DROP TABLE IF EXISTS `genres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genre` varchar(40) NOT NULL,
  `registreerd` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `genres_uk` (`genre`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genres`
--

LOCK TABLES `genres` WRITE;
/*!40000 ALTER TABLE `genres` DISABLE KEYS */;
INSERT INTO `genres` (`id`, `genre`, `registreerd`) VALUES (1,'Thriller',1),(3,'Spanning',1),(6,'Adventuur',1);
/*!40000 ALTER TABLE `genres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gereserveerde_boeken`
--

DROP TABLE IF EXISTS `gereserveerde_boeken`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gereserveerde_boeken` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gebruiker_id` int(11) NOT NULL,
  `boek_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gereserveerde_boeken_pk` (`boek_id`,`gebruiker_id`),
  KEY `gereserveerde_boeken_gebruikers_null_fk` (`gebruiker_id`),
  CONSTRAINT `gereserveerde_boeken_boeken_null_fk` FOREIGN KEY (`boek_id`) REFERENCES `boeken` (`id`),
  CONSTRAINT `gereserveerde_boeken_gebruikers_null_fk` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruikers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gereserveerde_boeken`
--

LOCK TABLES `gereserveerde_boeken` WRITE;
/*!40000 ALTER TABLE `gereserveerde_boeken` DISABLE KEYS */;
/*!40000 ALTER TABLE `gereserveerde_boeken` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rollen`
--

DROP TABLE IF EXISTS `rollen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rollen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rol` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rollen_uk` (`rol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rollen`
--

LOCK TABLES `rollen` WRITE;
/*!40000 ALTER TABLE `rollen` DISABLE KEYS */;
INSERT INTO `rollen` (`id`, `rol`) VALUES (1,'admin'),(2,'klant');
/*!40000 ALTER TABLE `rollen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schrijvers`
--

DROP TABLE IF EXISTS `schrijvers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schrijvers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voornaam` varchar(25) NOT NULL,
  `tussenvoegsel` varchar(15) DEFAULT NULL,
  `achternaam` varchar(25) NOT NULL,
  `registreerd` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schrijvers`
--

LOCK TABLES `schrijvers` WRITE;
/*!40000 ALTER TABLE `schrijvers` DISABLE KEYS */;
INSERT INTO `schrijvers` (`id`, `voornaam`, `tussenvoegsel`, `achternaam`, `registreerd`) VALUES (1,'Pietje',NULL,'Puk',1),(2,'Joi',NULL,'Nikolas',1),(3,'Miles',NULL,'Jones',1),(5,'Xandra',NULL,'Nikolas',0),(14,'Patrik',NULL,'Capcuch',0),(23,'Pietje','De','Pukje',1);
/*!40000 ALTER TABLE `schrijvers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `talen`
--

DROP TABLE IF EXISTS `talen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `talen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taal` varchar(25) NOT NULL,
  `registreerd` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `talen_uk` (`taal`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `talen`
--

LOCK TABLES `talen` WRITE;
/*!40000 ALTER TABLE `talen` DISABLE KEYS */;
INSERT INTO `talen` (`id`, `taal`, `registreerd`) VALUES (1,'Nederlands',1),(2,'Engels',1),(3,'Frans',1);
/*!40000 ALTER TABLE `talen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `woonplaats`
--

DROP TABLE IF EXISTS `woonplaats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `woonplaats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `woonplaats` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `woonplaats_uk` (`woonplaats`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `woonplaats`
--

LOCK TABLES `woonplaats` WRITE;
/*!40000 ALTER TABLE `woonplaats` DISABLE KEYS */;
INSERT INTO `woonplaats` (`id`, `woonplaats`) VALUES (1,'Arnhem'),(3,'Bemmel'),(5,'Ede'),(2,'Nijmegen'),(4,'Utrecht');
/*!40000 ALTER TABLE `woonplaats` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-01-19 10:58:13
