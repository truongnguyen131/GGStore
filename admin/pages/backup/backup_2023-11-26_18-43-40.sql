-- MySQL dump 10.13  Distrib 8.1.0, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: galaxy_game_store
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.21-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `discounts`
--

DROP TABLE IF EXISTS `discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `discount_amount` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discounts_ibfk_1` (`product_id`),
  CONSTRAINT `discounts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discounts`
--

LOCK TABLES `discounts` WRITE;
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
INSERT INTO `discounts` VALUES (1,29,10,'2023-09-21','2023-09-21'),(2,29,34,'2023-09-06','2023-09-09'),(7,29,21,'2023-09-29','2023-09-30'),(25,11,25,'2023-11-16','2023-12-31'),(26,12,40,'2023-11-16','2023-12-31'),(27,13,60,'2023-11-16','2023-12-31'),(28,14,20,'2023-11-16','2023-12-31'),(29,15,10,'2023-11-16','2023-12-31'),(30,16,35,'2023-11-16','2023-12-31'),(31,17,55,'2023-11-16','2023-12-31'),(32,18,40,'2023-11-16','2023-12-31'),(33,19,45,'2023-11-16','2023-12-31'),(34,20,45,'2023-11-16','2023-12-31'),(35,21,90,'2023-11-16','2023-12-31'),(36,22,80,'2023-11-16','2023-12-31'),(37,23,80,'2023-11-16','2023-12-31'),(38,24,70,'2023-11-16','2023-12-31'),(39,32,30,'2023-11-26','2023-12-30'),(40,33,25,'2023-11-27','2023-12-30'),(41,35,15,'2023-11-27','2023-12-30'),(42,34,10,'2023-11-27','2023-12-30'),(43,36,30,'2023-11-27','2023-12-30'),(44,37,50,'2023-11-27','2023-12-30'),(45,38,10,'2023-11-27','2023-12-30');
/*!40000 ALTER TABLE `discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genre_product`
--

DROP TABLE IF EXISTS `genre_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `genre_product` (
  `genre_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`genre_id`),
  KEY `genre_product_ibfk_1` (`genre_id`),
  CONSTRAINT `genre_product_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `genre_product_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genre_product`
--

LOCK TABLES `genre_product` WRITE;
/*!40000 ALTER TABLE `genre_product` DISABLE KEYS */;
INSERT INTO `genre_product` VALUES (1,5),(3,5),(6,6),(11,6),(13,6),(5,7),(6,7),(9,7),(13,7),(1,8),(3,8),(9,8),(1,9),(3,9),(10,9),(5,10),(11,10),(13,10),(1,11),(2,11),(3,11),(12,11),(2,12),(3,12),(13,12),(3,13),(8,13),(9,13),(5,14),(8,14),(14,14),(1,15),(12,15),(1,16),(2,16),(9,16),(1,17),(5,17),(16,17),(2,18),(5,18),(16,18),(1,19),(3,19),(6,19),(4,20),(7,20),(16,20),(1,21),(10,21),(11,21),(1,22),(10,22),(1,23),(3,23),(11,23),(1,24),(3,24),(11,24),(12,24),(1,29),(2,29),(7,29),(1,30),(30,32),(30,33),(31,34),(31,35),(31,36),(31,37),(30,38);
/*!40000 ALTER TABLE `genre_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genres`
--

DROP TABLE IF EXISTS `genres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genre_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genres`
--

LOCK TABLES `genres` WRITE;
/*!40000 ALTER TABLE `genres` DISABLE KEYS */;
INSERT INTO `genres` VALUES (1,'Action'),(2,'Adventure'),(3,'RPG'),(4,'Sports '),(5,'Simulation'),(6,'Strategy '),(7,'Racing '),(8,'Puzzle '),(9,'Fighting '),(10,'Shooter '),(11,'Survival '),(12,'Horror '),(13,'Open-world'),(14,'Educational '),(15,'Board '),(16,'Casual '),(30,'Chair Gaming'),(31,'Headphone Gaming'),(32,'Keyboard Gaming'),(33,'Mouse Gaming');
/*!40000 ALTER TABLE `genres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `header` text NOT NULL,
  `footer` text NOT NULL,
  `publish_date` date NOT NULL,
  `news_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_type_id` (`news_type_id`),
  CONSTRAINT `fk_type_id` FOREIGN KEY (`news_type_id`) REFERENCES `news_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'TOP 5 great features of PUBG Mobile Dinoground in version 2.6','In the PUBG Mobile Dinoground 2.6 update, there will be a lot of great features revealed that players may not know.\r\nIn version 2.6 of PUBG Mobile Dinoground will reveal many great features, let\'s MGN.vn see what features they are!','In the PUBG Mobile Dinoground 2.6 update, there will be a lot of great features revealed that players may not know.\r\nFollow GGS news so you can stay up to date with the latest news','2023-11-30',1),(2,'Latest Worlds 2023 schedule 19/11: Battle of Gods - T1 vs WBG','The 2023 World Finals (Worlds 2023) is known as the highest esports tournament of League of Legends in 2023. This is considered one of the eSports events that receives great attention from a large number of people when the number of viewers can reach millions of people.\r\nUpdate the most detailed summary of the schedule, results as well as all information related to Worlds 2023','Update the latest schedule on November 11 of the highest tournament of League of Legends - Worlds 2023.','2023-11-17',1);
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_comments`
--

DROP TABLE IF EXISTS `news_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `comment_date` datetime NOT NULL,
  `news_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reply_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  KEY `user_id` (`user_id`),
  KEY `fk_reply_id` (`reply_id`),
  CONSTRAINT `fk_reply_id` FOREIGN KEY (`reply_id`) REFERENCES `news_comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `news_comments_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`),
  CONSTRAINT `news_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_comments`
--

LOCK TABLES `news_comments` WRITE;
/*!40000 ALTER TABLE `news_comments` DISABLE KEYS */;
INSERT INTO `news_comments` VALUES (3,'nice game!!!','2023-11-13 02:43:00',1,33,NULL),(4,'nai xừ','2023-11-19 13:08:16',1,35,NULL),(15,'ok ok','2023-11-19 13:07:55',1,35,3),(16,'à há','2023-11-19 13:09:24',2,35,NULL),(28,'ádsad','2023-11-25 13:11:28',2,35,NULL);
/*!40000 ALTER TABLE `news_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_content`
--

DROP TABLE IF EXISTS `news_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `news_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  CONSTRAINT `news_content_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_content`
--

LOCK TABLES `news_content` WRITE;
/*!40000 ALTER TABLE `news_content` DISABLE KEYS */;
INSERT INTO `news_content` VALUES (91,'TOP 1: PUBGM GOES! ','PUBGM GOES! aka PUBGM Go Ahead! is known to be a new, parkour-inspired minigame in World of Wonder mode.\r\nAccordingly, you will have to overcome many traps and obstacles to be able to reach the finish line before the enemy and this is also considered an addictive game mode in the PUBG Mobile Dinoground 2.6 update.','news_1.jpg','',1),(92,'TOP 2: Companions','Companion is considered a new feature in Battle Royale mode, making PUBG Mobile more exciting than ever.\r\nAccordingly, after being eliminated from the match, you can completely follow the surviving teammates for the rest of the game in the form of a companion.\r\nAlong with that, you can also perform your companion\'s adorable expressions and actions such as jumping, running, and swimming. This will make the battlefield more fun and lively.','news2.png','',1),(93,'TOP 3: Dinosaur mini games','In Dinoground mode, there are a lot of great mini-games. Here, players can completely tame and ride small dinosaurs on the ground or flying dinosaurs.\r\nIn addition, players can also rescue and tame a giant T-Rex in random Dino settlements on the map, where an entire team can be carried.\r\n\r\nThere are quite a few interesting minigames in Primal Zones such as:\r\n\r\n- Velociraptor Jumping: Players will participate in Velociraptor rides and jump on higher platforms.\r\n\r\n- Pterosaur Hoops: Participants will also ride Pterosaurs to collect rewards from treasures boxed in minigame areas for a certain amount of time.\r\n\r\nAfter completing these minigames, you will also receive many items.','news3.png','',1),(94,'TOP 4: Convertible sports cars','This is not a tactical update, but it is still a factor that makes players extremely excited.\r\nYou can completely open or close the hood as you like when moving on the map.\r\nThis is also an interesting way for you to experience the game and can use this mode for the most popular Mirado sport today.','news4.png','',1),(95,'TOP 5: Fully automatic mode','The M16A4 and MK47 Mutant are considered two powerful semi-automatic rifles that can be used to fire at medium ranges.\r\nThe new attachment Full-Auto Mod also easily turns them into a fully automatic bullet spray gun.\r\nEven so, for beginners, it may be difficult to control the recoil of the gun in fully automatic mode.','news5.png','',1),(96,'Latest Worlds 2023 schedule today 19/11','Day: 19/11\r\nEvent time (Vietnam time zone): 15:00\r\nPairings: T1 vs WBG\r\n\r\n\r\n\r\n\r\n\r\n','cktg.png','',2);
/*!40000 ALTER TABLE `news_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_type`
--

DROP TABLE IF EXISTS `news_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_type_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_type`
--

LOCK TABLES `news_type` WRITE;
/*!40000 ALTER TABLE `news_type` DISABLE KEYS */;
INSERT INTO `news_type` VALUES (1,'eSports & Release'),(2,'Technology Gaming'),(3,'Events and Tournaments'),(4,'Feedbacks & Reviews'),(5,'Game Development');
/*!40000 ALTER TABLE `news_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_details_ibfk_1` (`order_id`),
  KEY `order_details_ibfk_2` (`product_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (19,3,29,1),(20,3,30,1);
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `total_amount` int(11) NOT NULL,
  `address` varchar(500) NOT NULL,
  `status` enum('Waiting for confirmation','Waiting for delivery','Paid','Cancelled') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_ibfk_1` (`customer_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (3,33,'2023-10-26 14:50:00',408000,'343/14 khu vực Bình Yên A Phường Long Hòa Quận Bình Thuỷ Thành phố Cần Thơ','Paid'),(5,35,'2023-10-26 14:57:00',2808000,'343/14 khu vực Bình Yên A Phường Long Hòa Quận Bình Thuỷ Thành phố Cần Thơ','Waiting for confirmation');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_comments`
--

DROP TABLE IF EXISTS `product_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `comment_date` date NOT NULL,
  `rating` enum('1','2','3','4','5') DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product` (`product_id`),
  KEY `fk_user` (`user_id`),
  CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_comments`
--

LOCK TABLES `product_comments` WRITE;
/*!40000 ALTER TABLE `product_comments` DISABLE KEYS */;
INSERT INTO `product_comments` VALUES (2,'ok ok','2023-11-01','5',11,35),(3,'OK','2023-11-25','4',19,33),(29,'a','2023-11-25','3',21,33),(30,'ok','2023-11-25','5',21,33),(31,'a','2023-11-25','1',21,33);
/*!40000 ALTER TABLE `product_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `product_id` int(11) NOT NULL,
  `image_url` varchar(100) NOT NULL,
  PRIMARY KEY (`product_id`,`image_url`),
  CONSTRAINT `fk_product_images_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (5,'v_1.jpg'),(5,'v_2.jpg'),(5,'v_3.jpg'),(5,'v_4.jpg'),(5,'v_5.jpg'),(6,'f_1.jpg'),(6,'f_2.jpg'),(6,'f_3.jpg'),(6,'f_4.jpg'),(7,'m_1.jpg'),(7,'m_2.jpg'),(7,'m_3.jpg'),(7,'m_4.jpg'),(8,'w_1.jpg'),(8,'w_2.jpg'),(8,'w_3.jpg'),(8,'w_4.jpg'),(8,'w_5.jpg'),(9,'t_1.jpg'),(9,'t_2.jpg'),(9,'t_3.jpg'),(9,'t_4.jpg'),(10,'n_1.jpg'),(10,'n_2.jpg'),(10,'n_3.jpg'),(10,'n_4.jpg'),(10,'n_5.jpg'),(10,'n_6.jpg'),(11,'top10_1_1.jpg'),(11,'top10_1_2.jpg'),(11,'top10_1_3.jpg'),(11,'top10_1_4.jpg'),(11,'top10_1_5.jpg'),(12,'top_10_2_1.jpg'),(12,'top_10_2_2.jpg'),(12,'top_10_2_3.jpg'),(12,'top_10_2_4.jpg'),(12,'top_10_2_5.jpg'),(12,'top_10_2_6.jpg'),(13,'top_10_3_1.jpg'),(13,'top_10_3_2.jpg'),(13,'top_10_3_3.jpg'),(14,'top10_4_1.jpg'),(14,'top10_4_2.jpg'),(14,'top10_4_3.jpg'),(15,'top10_5_1.jpg'),(15,'top10_5_2.jpg'),(15,'top10_5_3.jpg'),(16,'top10_6_1.jpg'),(16,'top10_6_2.jpg'),(16,'top10_6_3.jpg'),(17,'top10_7_1.jpg'),(17,'top10_7_2.jpg'),(17,'top10_7_3.jpg'),(18,'top10_8_1.jpg'),(18,'top10_8_2.jpg'),(18,'top10_8_3.jpg'),(18,'top10_8_4.jpg'),(18,'top10_8_5.jpg'),(19,'top10_9_1.jpg'),(19,'top10_9_2.jpg'),(19,'top10_9_3.jpg'),(19,'top10_9_4.jpg'),(20,'top10_10_1.jpg'),(20,'top10_10_2.jpg'),(20,'top10_10_3.jpg'),(20,'top10_10_4.jpg'),(21,'offer_1_1.jpg'),(21,'offer_1_2.jpg'),(21,'offer_1_3.jpg'),(21,'offer_1_4.jpg'),(22,'offer_2_1.jpg'),(22,'offer_2_2.jpg'),(22,'offer_2_3.jpg'),(22,'offer_2_4.jpg'),(23,'offer_3_1.jpg'),(23,'offer_3_2.jpg'),(23,'offer_3_3.jpg'),(24,'offer_5_1.jpg'),(24,'offer_5_2.jpg'),(24,'offer_5_3.jpg'),(29,'g1_2.jpg'),(29,'g1_3.jpg'),(29,'g1_4.jpg'),(30,'g2_1.jpg'),(30,'g2_2.jpg'),(30,'g2_3.jpg'),(30,'g2_4.jpg'),(32,'Chair_1_1.jpg'),(32,'chair_1_2.jpg'),(32,'chair_1_Avt.jpg'),(33,'chair_2_1.jpg'),(33,'chair_2_2.jpg'),(33,'chair_2_3.jpg'),(34,'head_1_2.jpg'),(34,'head_1_3.jpg'),(34,'head_1_avt.jpg'),(35,'head_2_1.jpg'),(35,'head_2_2.jpg'),(35,'head_2_3.jpg'),(36,'head_3_1.jpg'),(36,'head_3_2.jpg'),(36,'head_3_avt.jpg'),(37,'head_1_2.jpg'),(37,'head_1_3.jpg'),(37,'head_1_avt.jpg'),(38,'chair_3_1.jpg'),(38,'chair_3_2.jpg'),(38,'chair_3_avt.jpg');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_manufacturer` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `image_avt_url` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `video_trailer_url` varchar(50) NOT NULL,
  `source` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `release_date` date NOT NULL,
  `units_sold` int(11) NOT NULL DEFAULT 0,
  `classify` enum('game','gear') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_manufacturer_id` (`id_manufacturer`),
  CONSTRAINT `fk_manufacturer_id` FOREIGN KEY (`id_manufacturer`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (5,24,'Vampire: The Masquerade® - Bloodlines™ 2','vampire_avt.jpg','Fight your way through a modern-day Seattle on the brink of an open war as an elder Vampire. Meet the power-players, ally yourself and decide who will rule and what the city will become.\r\n                                                ','vampire_video.webm','Source.zip',130,'2024-09-14',0,'game'),(6,24,'Frostpunk 2','f_avt.jpg','Frostpunk 2 is the sequel to the highly acclaimed, BAFTA-nominated society survival game. The age of steam has passed and now, oil leads the way as humanity is newest salvation. However, with new threats on the horizon, the future of the city looks even grimmer than before.','f_video.webm','Source.zip',102,'2024-05-14',0,'game'),(7,24,'Manor Lords','m_avt.jpg','Manor Lords is a medieval strategy game featuring in-depth city building, large-scale tactical battles, and complex economic and social simulations. Rule your lands as a medieval lord -- the seasons pass, the weather changes, and cities rise and fall.\r\n                                                ','m_video.webm','Source.zip',34,'2024-04-26',0,'game'),(8,24,'Black Myth: Wukong','w_avt.jpg','Black Myth Wukong is an action RPG rooted in Chinese mythology. You shall set out as the Destined One to venture into the challenges and marvels ahead, to uncover the obscured truth beneath the veil of a glorious legend from the past.','w_video.webm','Source.zip',45,'2024-01-15',0,'game'),(9,24,'The First Descendant','t_avt.jpg','The First Descendant is a third-person looter shooter powered by Unreal Engine 5. Become a Descendant. Fight for the survival of humanity. Descendants have unique abilities to tackle both solo and co-op missions. Up to 4 players use varied mechanics to defeat giant bosses. BE THE FIRST DESCENDANT!           ','t_video.webm','Source.zip',48,'2024-09-15',0,'game'),(10,24,'Nightingale','n_avt.jpg','Set out on a journey of survival and adventure, into the mysterious and dangerous Fae Realms of Nightingale! Become an intrepid Realmwalker, and venture forth alone or with friends - as you explore, craft, build and fight across a visually stunning Gaslamp Fantasy world.\r\n                                                ','n_video.webm','Source.zip',98,'2024-02-26',0,'game'),(11,24,'Remnant II','top10_1_avt.jpg','Remnant II pits survivors of humanity against new deadly creatures and god-like bosses across terrifying worlds. Play solo or co-op with two other friends to explore the depths of the unknown to stop an evil from destroying reality itself.\r\n                                                ','top10_1_video.webm','Source.zip',60,'2023-07-25',6,'game'),(12,24,'Atelier Ryza 3','top_10_2_avt.jpg','The final summer, the final secret... Ryza\'s final adventure is about to begin!\r\n                                                ','top_10_2_video.webm','Source.zip',83,'2023-03-24',3,'game'),(13,24,'Encased','top_10_3_avt.jpg','A tactical scifi RPG set in an alternative 1970s, where an enormous and inexplicable artifact the Dome is discovered in a remote desert. Fight enemies, explore the anomalous wasteland, level up your character, join one of the forces in the ruined world.','top_10_3_video.webm','Source.zip',8,'2023-01-20',8,'game'),(14,24,'Not For Broadcast','top10_4_avt.jpg','The National Nightly News is live and you are the brains behind the scenes. Beep the swears, keep the cameras on the celebs and keep the audience hooked in this darkly comedic game of televised chaos.\r\n                                                    ','top10_4_video.webm','Source.zip',9,'2023-11-16',9,'game'),(15,24,'Dishonored 2','top10_5_avt.jpg','Dishonored 2 is set 15 years after the Lord Regent has been vanquished and the dreaded Rat Plague has passed into history. An otherworldly usurper has seized Empress Emily Kaldwin’s throne, leaving the fate of the Isles hanging in the balance. As Emily or Corvo, travel beyond the legendary streets of Dunwall to Karnaca, the once-dazzling coastal city that holds the keys to restoring Emily to power. Armed with the Mark of the Outsider and powerful new abilities, track down your enemies and take back what is rightfully yours.','top10_5_video.webm','Source.zip',27,'2023-01-04',2,'game'),(16,24,'ICEY','top10_6_avt.jpg','ICEY is a 2D side-scrolling action game and a META game in disguise. As you follow the narrator is omnipresent voice, you will see through ICEY is eyes and learn the truth about her world. The narrator will constantly urge you in one direction\r\n                                                    ','top10_6_video.webm','Source.zip',5,'2015-01-20',15,'game'),(17,24,'Surviving Deponia','top10_7_avt.jpg','Explore, craft, build and fight your way through the harsh landscape of Deponia. Meet allies and make enemies while surviving the perils of the junk planet. It might be garbage\r\n                                                    ','top10_7_video.webm','Source.zip',7,'2023-11-16',11,'game'),(18,24,'Lake','top10_8_avt.jpg','It is 1986 - Meredith Weiss takes a break from her career in the big city to deliver mail in her hometown. How will she experience two weeks in beautiful Providence Oaks, with its iconic lake and quirky community? And what will she do next? It is up to you.\n                                                    ','top10_8_video.webm','Source.zip',8,'2020-01-18',4,'game'),(19,24,'Sekiro','top10_9_avt.jpg','Game of the Year - The Game Awards 2019 Best Action Game of 2019 - IGN Carve your own clever path to vengeance in the award winning adventure from developer FromSoftware, creators of Bloodborne and the Dark Souls series. Take Revenge. Restore Your Honor. Kill Ingeniously.\n                                                    ','top10_9_video.webm','Source.zip',65,'2019-05-14',13,'game'),(20,24,'F1® 23','top10_10_avt.jpg','Be the last to brake in EA SPORTS™ F1® 23, the official video game of the 2023 FIA Formula One World Championship™. A new chapter in the thrilling Braking Point story mode delivers high-speed drama and heated rivalries.\r\n                                                    ','top10_10_video.webm','Source.zip',43,'2022-01-12',14,'game'),(21,24,'Prey','offer_1_avt.jpg','In Prey, you awaken aboard Talos I, a space station orbiting the moon in the year 2032. You are the key subject of an experiment meant to alter humanity forever – but things have gone terribly wrong. The space station has been overrun by hostile aliens and you are now being hunted.\r\n                                                    ','offer_1_video.webm','Source.zip',7,'2023-05-17',0,'game'),(22,24,'DEATHLOOP','offer_2_avt.jpg','DEATHLOOP is a next-gen FPS from Arkane Lyon, the award-winning studio behind Dishonored. In DEATHLOOP, two rival assassins are trapped in a mysterious timeloop on the island of Blackreef, doomed to repeat the same day for eternity.\r\n                                                    ','offer_2_video.webm','Source.zip',27,'2023-06-06',0,'game'),(23,24,'Dishonored','offer_3_avt.jpg','From the award-winning developers at Arkane® Studios comes Dishonored®: Death of the Outsider, the next standalone adventure in the critically-acclaimed Dishonored® series.\r\n                                                    ','offer_3_video.webm','Source.zip',14,'2022-02-14',0,'game'),(24,24,'Arx Fatalis','offer_5_avt.jpg','This critically acclaimed first-person RPG from Arkane Studios takes the player on an amazing journey into the fantasy world of Arx. Arx is wrought with turmoil, brought to the brink of destruction by a violent war.\r\n                                                    ','offer_5_video.webm','Source.zip',37,'2016-01-03',0,'game'),(29,24,'Trailtankers','g1.jpg','Build the ultimate vehicle and explore a vast open world filled with challenges and adventure. Play solo or team up with friends in multiplayer to conquer the toughest obstacles. With a thrilling campaign mode and endless creative possibilities, Trailmakers is the ultimate sandbox for adventurers.','g1.mp4','Source.zip',21,'2023-11-07',0,'game'),(30,24,'Black Mesa','g2.jpg','Relive Half Life. It is a brilliant tribute to one of the greatest videogames ever made, and it is also a good game in its own right.','g2.mp4','Source.zip',19,'2023-11-01',0,'game'),(32,24,'CYROLA GAMING CHAIR','chair_1_Avt.jpg','The design is different from other e-sports chairs because it has a larger seat, perfect for adults. It can hold up to 400 pounds.The ergonomic body-hugging high back provides lumbar support naturally fits your shoulders, head and neck.And the chair come with footrest,When you don’t want to straighten your back and sit,just take out the footrest. Enjoy the gaming with footrest!\r\n                                                    ','chair_1_video.mp4','Source.zip',206,'2023-11-26',0,'gear'),(33,24,'Gaming Chairs Footrest','chair_2_Avt.jpg','The footrest on a gaming chair is not just an additional luxury. Having your legs lifted up feels relaxing. It also takes less energy for the heart to pump blood down to the legs and back up. Circulation can be exhausting too.\r\n\r\nTranslation: Gaming chairs with footrest can help you preserve energy, especially needed for long-hour gaming. \r\nThey are also a great accessory if you need to take a short nap. Mostly just to regain your focus or wait while a new game is loading too long.\r\n                                                    ','chair_1_video.mp4','Source.zip',200,'2023-11-27',0,'gear'),(34,24,'Fantech HG15','head_1_avt.jpg','Fantech HG15 Surround Sound 7.1 Gaming Headset (Captain 7.1 + RGB LED)\r\nProduct information:\r\n+ Brand: FANTECH - specializes in gaming equipment\r\n+ Model: Gaming headset\r\n+ 7.1 surround sound, clear fidelity, deep and deep bass\r\n+ Comfortable cover, with leather material to cover the ears extremely quietly\r\n+ Modern, luxurious, RGB LED design\r\n+ With volume adjustment, noise cancellation\r\n+ Renewal warranty, genuine 12 months\r\n                                                    ','headphone_video.mp4','Source.zip',54,'2023-11-27',0,'gear'),(35,24,'Headphone G90 A1','head_2_avt.jpg','Fantech HG15 Surround Sound 7.1 Gaming Headset (Captain 7.1 + RGB LED)\r\nProduct information:\r\n+ Brand: FANTECH - specializes in gaming equipment\r\n+ Model: Gaming headset\r\n+ 7.1 surround sound, clear fidelity, deep and deep bass\r\n+ Comfortable cover, with leather material to cover the ears extremely quietly\r\n+ Modern, luxurious, RGB LED design\r\n+ With volume adjustment, noise cancellation\r\n+ Renewal warranty, genuine 12 months\r\n                                                    ','headphone_video.mp4','Source.zip',32,'2023-11-27',0,'gear'),(36,24,'Logitech G289','head_3_avt.jpg','Fantech HG15 Surround Sound 7.1 Gaming Headset (Captain 7.1 + RGB LED)\r\nProduct information:\r\n+ Brand: FANTECH - specializes in gaming equipment\r\n+ Model: Gaming headset\r\n+ 7.1 surround sound, clear fidelity, deep and deep bass\r\n+ Comfortable cover, with leather material to cover the ears extremely quietly\r\n+ Modern, luxurious, RGB LED design\r\n+ With volume adjustment, noise cancellation\r\n+ Renewal warranty, genuine 12 months\r\n                                                    ','headphone_video.mp4','Source.zip',68,'2023-11-27',0,'gear'),(37,24,'Headphone Pana-06','head_1_3.jpg','Fantech HG15 Surround Sound 7.1 Gaming Headset (Captain 7.1 + RGB LED)\r\nProduct information:\r\n+ Brand: FANTECH - specializes in gaming equipment\r\n+ Model: Gaming headset\r\n+ 7.1 surround sound, clear fidelity, deep and deep bass\r\n+ Comfortable cover, with leather material to cover the ears extremely quietly\r\n+ Modern, luxurious, RGB LED design\r\n+ With volume adjustment, noise cancellation\r\n+ Renewal warranty, genuine 12 months\r\n                                                    ','headphone_video.mp4','Source.zip',120,'2023-11-27',0,'gear'),(38,24,'DXX Gamer Version','chair_3_avt.jpg','Fantech HG15 Surround Sound 7.1 Gaming Headset (Captain 7.1 + RGB LED)\r\nProduct information:\r\n+ Brand: FANTECH - specializes in gaming equipment\r\n+ Model: Gaming headset\r\n+ 7.1 surround sound, clear fidelity, deep and deep bass\r\n+ Comfortable cover, with leather material to cover the ears extremely quietly\r\n+ Modern, luxurious, RGB LED design\r\n+ With volume adjustment, noise cancellation\r\n+ Renewal warranty, genuine 12 months\r\n                                                    ','chair_1_video.mp4','Source.zip',250,'2023-11-27',0,'gear');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchased_products`
--

DROP TABLE IF EXISTS `purchased_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchased_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `status` enum('traded','trading','not trading','review') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `exchanges_ibfk_1` (`customer_id`),
  KEY `exchanges_ibfk_2` (`product_id`),
  CONSTRAINT `purchased_products_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchased_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchased_products`
--

LOCK TABLES `purchased_products` WRITE;
/*!40000 ALTER TABLE `purchased_products` DISABLE KEYS */;
INSERT INTO `purchased_products` VALUES (1,33,29,1,7,'trading'),(3,33,11,1,40,'trading'),(4,33,19,1,1,'traded'),(5,35,13,2,5,'trading'),(6,35,18,2,8,'trading');
/*!40000 ALTER TABLE `purchased_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tradings_history`
--

DROP TABLE IF EXISTS `tradings_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tradings_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `transaction_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tradings_history_ibfk_2` (`buyer_id`),
  KEY `tradings_history_ibfk_3` (`product_id`),
  CONSTRAINT `tradings_history_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tradings_history_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `purchased_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tradings_history`
--

LOCK TABLES `tradings_history` WRITE;
/*!40000 ALTER TABLE `tradings_history` DISABLE KEYS */;
INSERT INTO `tradings_history` VALUES (2,35,4,2,'2023-05-12 00:00:00');
/*!40000 ALTER TABLE `tradings_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_voucher`
--

DROP TABLE IF EXISTS `user_voucher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_voucher` (
  `user_id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`voucher_id`),
  KEY `fk_user_voucher_2` (`voucher_id`),
  CONSTRAINT `fk_user_voucher_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_voucher_2` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_voucher`
--

LOCK TABLES `user_voucher` WRITE;
/*!40000 ALTER TABLE `user_voucher` DISABLE KEYS */;
INSERT INTO `user_voucher` VALUES (33,46,1),(35,47,1),(35,55,1);
/*!40000 ALTER TABLE `user_voucher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('user','manufacturer','developer') NOT NULL,
  `full_name` varchar(60) NOT NULL,
  `phone_number` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `Gcoin` int(11) NOT NULL DEFAULT 0,
  `locked_until` date DEFAULT NULL,
  `login_attempts` int(11) NOT NULL,
  `lastest_login` date NOT NULL DEFAULT '1900-01-01',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ggs_administrator_01','$2y$10$7poHRO8H3.BvTvs2DQT4ju3JfRyuHrEmn.UYpIGq8eAi8l8ukV0K2','developer','Trương Minh Trí','0938441905','truongminhtri1905@gmail.com',0,NULL,0,'2023-11-26'),(24,'vng_official','$2y$10$cbpLLzJMxr1zDU/nqqZJPeQj18rwxYsdQNJJPDg7yK54aKSRkjUYq','manufacturer','VNG Game','0225982431','vng_official@gmail.com',0,NULL,0,'0000-00-00'),(33,'lvluyen902','$2y$10$vt2NoRBx31nEAB0GWPTEkeU5utBltx8FqxffI5yQDQZYAZua21kdK','user','Lê Văn Luyện','0938665889','lvluyen902@gmail.com',0,NULL,0,'2023-11-25'),(35,'thesi2000pl','$2y$10$N7NlgsnqPn63uEKIIsVm9OkmsHVQDvy8z8oAXtSbmocQ4xro0qPEG','user','Lê Lợi','0123954632','lloi112_game@gmail.com',0,NULL,0,'2023-11-25');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voucher_usage`
--

DROP TABLE IF EXISTS `voucher_usage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `voucher_usage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `voucher_usage_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher_usage`
--

LOCK TABLES `voucher_usage` WRITE;
/*!40000 ALTER TABLE `voucher_usage` DISABLE KEYS */;
INSERT INTO `voucher_usage` VALUES (2,47,5),(3,47,3);
/*!40000 ALTER TABLE `voucher_usage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` int(11) NOT NULL,
  `type` enum('percent','gcoin','freeship') NOT NULL,
  `minimum_condition` int(11) NOT NULL DEFAULT 0,
  `quantity` int(11) DEFAULT NULL,
  `date_expiry` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vouchers`
--

LOCK TABLES `vouchers` WRITE;
/*!40000 ALTER TABLE `vouchers` DISABLE KEYS */;
INSERT INTO `vouchers` VALUES (46,5,'gcoin',50,20,'2023-12-11'),(47,10,'percent',100,NULL,NULL),(55,20,'freeship',120,NULL,NULL);
/*!40000 ALTER TABLE `vouchers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-27  0:43:41
