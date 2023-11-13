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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discounts`
--

LOCK TABLES `discounts` WRITE;
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
INSERT INTO `discounts` VALUES (1,1,10,'2023-09-21','2023-09-21'),(2,1,34,'2023-09-06','2023-09-09'),(7,1,21,'2023-09-29','2023-09-30'),(21,4,20,'2023-10-10','2023-10-21'),(22,4,10,'2023-10-22','2023-10-28');
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
INSERT INTO `genre_product` VALUES (1,1),(2,1),(7,1),(1,2),(30,4);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'TOP 5 great features of PUBG Mobile Dinoground in version 2.6','In the PUBG Mobile Dinoground 2.6 update, there will be a lot of great features revealed that players may not know.\r\nIn version 2.6 of PUBG Mobile Dinoground will reveal many great features, let\'s MGN.vn see what features they are!','In the PUBG Mobile Dinoground 2.6 update, there will be a lot of great features revealed that players may not know.\r\nFollow GGS news so you can stay up to date with the latest news','2023-11-30',6);
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
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `news_comments_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`),
  CONSTRAINT `news_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_comments`
--

LOCK TABLES `news_comments` WRITE;
/*!40000 ALTER TABLE `news_comments` DISABLE KEYS */;
INSERT INTO `news_comments` VALUES (3,'nice game!!!','2023-11-13 02:43:00',1,33),(4,'nice','2023-11-13 03:20:00',1,35);
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
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_content`
--

LOCK TABLES `news_content` WRITE;
/*!40000 ALTER TABLE `news_content` DISABLE KEYS */;
INSERT INTO `news_content` VALUES (76,'TOP 1: PUBGM GOES! ','PUBGM GOES! aka PUBGM Go Ahead! is known to be a new, parkour-inspired minigame in World of Wonder mode.\r\nAccordingly, you will have to overcome many traps and obstacles to be able to reach the finish line before the enemy and this is also considered an addictive game mode in the PUBG Mobile Dinoground 2.6 update.','news1.jpg','',1),(77,'TOP 2: Companions','Companion is considered a new feature in Battle Royale mode, making PUBG Mobile more exciting than ever.\r\nAccordingly, after being eliminated from the match, you can completely follow the surviving teammates for the rest of the game in the form of a companion.\r\nAlong with that, you can also perform your companion\'s adorable expressions and actions such as jumping, running, and swimming. This will make the battlefield more fun and lively.','news2.png','',1),(78,'TOP 3: Dinosaur mini games','In Dinoground mode, there are a lot of great mini-games. Here, players can completely tame and ride small dinosaurs on the ground or flying dinosaurs.\r\nIn addition, players can also rescue and tame a giant T-Rex in random Dino settlements on the map, where an entire team can be carried.\r\n\r\nThere are quite a few interesting minigames in Primal Zones such as:\r\n\r\n- Velociraptor Jumping: Players will participate in Velociraptor rides and jump on higher platforms.\r\n\r\n- Pterosaur Hoops: Participants will also ride Pterosaurs to collect rewards from treasures boxed in minigame areas for a certain amount of time.\r\n\r\nAfter completing these minigames, you will also receive many items.','news3.png','',1),(79,'TOP 4: Convertible sports cars','This is not a tactical update, but it is still a factor that makes players extremely excited.\r\nYou can completely open or close the hood as you like when moving on the map.\r\nThis is also an interesting way for you to experience the game and can use this mode for the most popular Mirado sport today.','news4.png','',1),(80,'TOP 5: Fully automatic mode','The M16A4 and MK47 Mutant are considered two powerful semi-automatic rifles that can be used to fire at medium ranges.\r\nThe new attachment Full-Auto Mod also easily turns them into a fully automatic bullet spray gun.\r\nEven so, for beginners, it may be difficult to control the recoil of the gun in fully automatic mode.','news5.png','',1);
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
INSERT INTO `news_type` VALUES (1,'Game Development'),(2,'Technology'),(3,'Events and Tournaments'),(4,'Release'),(5,'Feedbacks and Reviews'),(6,'eSports');
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
INSERT INTO `order_details` VALUES (13,5,4,2),(19,3,1,1),(20,3,2,1);
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
INSERT INTO `product_images` VALUES (1,'g1_1.jpg'),(1,'g1_2.jpg'),(1,'g1_3.jpg'),(1,'g1_4.jpg'),(2,'g2_1.jpg'),(2,'g2_2.jpg'),(2,'g2_3.jpg'),(2,'g2_4.jpg'),(4,'ATAS_A1_1.jpg'),(4,'ATAS_A1_2.jpg');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,24,'Trailtankers','g1.jpg','Build the ultimate vehicle and explore a vast open world filled with challenges and adventure. Play solo or team up with friends in multiplayer to conquer the toughest obstacles. With a thrilling campaign mode and endless creative possibilities, Trailmakers is the ultimate sandbox for adventurers.','g1.mp4','GAME.zip',220000,'2019-05-11',0,'game'),(2,24,'Black Mesa','g2.jpg','Relive Half Life. It is a brilliant tribute to one of the greatest videogames ever made, and it is also a good game in its own right.','g2.mp4','GAME.zip',188000,'2020-01-05',0,'game'),(4,24,'Chair Gaming ATAS A1','Chair_gaming_ATAS_A1.jpg','Chair gaming ATAS A1','trailer.mp4','website.zip',1560000,'2023-10-10',0,'gear');
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
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `status` enum('trading','not trading') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `exchanges_ibfk_1` (`customer_id`),
  KEY `exchanges_ibfk_2` (`product_id`),
  CONSTRAINT `purchased_products_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchased_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchased_products`
--

LOCK TABLES `purchased_products` WRITE;
/*!40000 ALTER TABLE `purchased_products` DISABLE KEYS */;
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
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `transaction_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tradings_history_ibfk_1` (`seller_id`),
  KEY `tradings_history_ibfk_2` (`buyer_id`),
  KEY `tradings_history_ibfk_3` (`product_id`),
  CONSTRAINT `tradings_history_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tradings_history_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tradings_history_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `purchased_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tradings_history`
--

LOCK TABLES `tradings_history` WRITE;
/*!40000 ALTER TABLE `tradings_history` DISABLE KEYS */;
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
INSERT INTO `user_voucher` VALUES (33,46,2),(35,47,1);
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
  `loyalty_points` int(11) NOT NULL DEFAULT 0,
  `Gcoin` int(11) NOT NULL DEFAULT 0,
  `login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ggs_administrator_01','$2y$10$7poHRO8H3.BvTvs2DQT4ju3JfRyuHrEmn.UYpIGq8eAi8l8ukV0K2','developer','Trương Minh Trí','0938441905','truongminhtri1905@gmail.com',0,0,0,NULL),(24,'vng_official','$2y$10$cbpLLzJMxr1zDU/nqqZJPeQj18rwxYsdQNJJPDg7yK54aKSRkjUYq','manufacturer','VNG Game','0225982431','vng_official@gmail.com',0,0,0,NULL),(33,'lvluyen902','$2y$10$vt2NoRBx31nEAB0GWPTEkeU5utBltx8FqxffI5yQDQZYAZua21kdK','user','Lê Văn Luyện','0938665889','lvluyen902@gmail.com',0,0,0,NULL),(35,'thesi2000pl','$2y$10$N7NlgsnqPn63uEKIIsVm9OkmsHVQDvy8z8oAXtSbmocQ4xro0qPEG','user','Lê Lợi','0123954632','lloi112_game@gmail.com',0,0,0,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vouchers`
--

LOCK TABLES `vouchers` WRITE;
/*!40000 ALTER TABLE `vouchers` DISABLE KEYS */;
INSERT INTO `vouchers` VALUES (46,5,'gcoin',50,20,'2023-10-24'),(47,10,'percent',0,NULL,NULL);
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

-- Dump completed on 2023-11-13 14:00:55
