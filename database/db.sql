-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: madifa-world
-- ------------------------------------------------------
-- Server version	8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+07:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `album`
--

DROP TABLE IF EXISTS `album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `album` (
  `album_id` int NOT NULL AUTO_INCREMENT,
  `judul` char(64) NOT NULL,
  `penyanyi` char(128) NOT NULL,
  `total_duration` int NOT NULL,
  `image_path` char(255) NOT NULL,
  `tanggal_terbit` date NOT NULL,
  `genre` char(64) DEFAULT NULL,
  PRIMARY KEY (`album_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album`
--

LOCK TABLES `album` WRITE;
/*!40000 ALTER TABLE `album` DISABLE KEYS */;
INSERT INTO `album` VALUES (1,'Heavy Rotation','JKT48',2518,'albumImage/Album1.jpeg','2013-02-16','J-pop'),(2,'Kereta Kencan','HiVi!',2110,'albumImage/Album2.jpeg','2017-02-23','Pop'),(3,'RIVER','JKT48',1052,'albumImage/Album3.jpg','2013-05-11','J-pop');
/*!40000 ALTER TABLE `album` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `song`
--

DROP TABLE IF EXISTS `song`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `song` (
  `song_id` int NOT NULL AUTO_INCREMENT,
  `judul` char(64) NOT NULL,
  `penyanyi` char(128) DEFAULT NULL,
  `tanggal_terbit` date NOT NULL,
  `genre` char(64) DEFAULT NULL,
  `duration` int NOT NULL,
  `audio_path` char(255) NOT NULL,
  `image_path` char(255) DEFAULT NULL,
  `album_id` int DEFAULT NULL,
  PRIMARY KEY (`song_id`),
  KEY `album_id` (`album_id`),
  CONSTRAINT `song_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `album` (`album_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `song`
--

LOCK TABLES `song` WRITE;
/*!40000 ALTER TABLE `song` DISABLE KEYS */;
INSERT INTO `song` VALUES (5,'Baby Elephant Walk','HiVi!','2013-02-11','Pop',60,'songAudio/BabyElephantWalk60.wav','songImage/image1.jpg',2),(6,'Cantina Band','JKT48','2014-02-11','Rock',60,'songAudio/CantinaBand60.wav','songImage/image2.jpg',1),(7,'Fanfare','JKT48','2016-05-11','J-pop',60,'songAudio/Fanfare60.wav','songImage/image3.jpg',3),(8,'Gettysburg','JKT48','2018-02-12','Rock',10,'songAudio/gettysburg10.wav','songImage/image4.jpg',1),(9,'Groove 500','HiVi!','2012-05-03','Jazz',20,'songAudio/Groove500.mp3','songImage/image5.jpg',2),(10,'Groove 1000','HiVi!','2013-07-08','Jazz',43,'songAudio/Groove1000.mp3','songImage/image6.jpg',2),(11,'Groove 2000','HiVi!','2004-02-06','Jazz',86,'songAudio/Groove2000.mp3','songImage/image7.jpg',2),(12,'Imperial March','JKT48','2010-09-11','Classic',60,'songAudio/ImperialMarch60.wav','songImage/image8.jpg',3),(13,'Pink Panther','JKT48','2015-02-11','Pop',30,'songAudio/PinkPanther30.wav','songImage/image9.jpg',1),(14,'Preamble','HiVi!','2019-02-11','J-pop',19,'songAudio/preamble.wav','songImage/image10.jpg',2),(15,'Star Wars','JKT48','2012-12-09','J-pop',60,'songAudio/StarWars60.wav','songImage/image11.jpg',3),(16,'Taunt','JKT48','2015-02-05','Classic',4,'songAudio/taunt.wav','songImage/image12.jpg',1);
/*!40000 ALTER TABLE `song` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `email` char(255) NOT NULL,
  `password` char(255) NOT NULL,
  `username` char(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin@example.com','$2y$10$gRR/aqMGqZyShnmZQZzEuONwldRKINqVJMGUrG6zrfu1/qLqqK9pu','admin',1),(2,'user@example.com','$2y$10$tjHu3PykRoHvvFaDRmn43e09I.oo/k/kY5EaTSClBNJBCdJ/A5ZiG','user',0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-10  8:45:25
