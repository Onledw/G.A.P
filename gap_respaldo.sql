-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: gap
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

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
-- Table structure for table `ausencias`
--

DROP TABLE IF EXISTS `ausencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ausencias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `docente_id` bigint(20) unsigned NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `todoeldia` tinyint(1) NOT NULL DEFAULT 1,
  `justificada` tinyint(1) NOT NULL DEFAULT 0,
  `motivo` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ausencias_docente_id_foreign` (`docente_id`),
  CONSTRAINT `ausencias_docente_id_foreign` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ausencias`
--

LOCK TABLES `ausencias` WRITE;
/*!40000 ALTER TABLE `ausencias` DISABLE KEYS */;
INSERT INTO `ausencias` VALUES (7,1,'2025-05-26 08:00:00','2025-05-26 15:00:00',0,1,'S','2025-05-26 11:23:48','2025-05-29 18:07:03'),(9,1,'2025-05-26 10:00:00','2025-05-26 11:00:00',0,0,NULL,'2025-05-26 13:37:18','2025-05-26 13:37:18'),(10,2,'2025-05-26 11:00:00','2025-05-26 12:00:00',0,0,NULL,'2025-05-26 13:37:41','2025-05-26 13:37:41'),(11,3,'2025-05-26 09:00:00','2025-05-26 10:00:00',0,1,NULL,'2025-05-26 13:37:58','2025-05-30 22:40:12'),(12,1,'2025-05-27 00:00:00','2025-05-27 00:00:00',1,1,'A','2025-05-27 11:43:35','2025-05-27 11:43:35'),(13,1,'2025-05-28 00:00:00','2025-05-28 00:00:00',1,1,'Ausencia','2025-05-28 10:43:22','2025-05-28 10:43:22'),(14,3,'2025-05-29 00:00:00','2025-05-29 00:00:00',1,0,'t453r3w3','2025-05-29 21:49:23','2025-05-29 21:49:23'),(15,2,'2025-05-30 00:00:00','2025-05-30 00:00:00',1,1,'preuba','2025-05-30 12:20:47','2025-05-30 22:40:15'),(16,3,'2025-05-31 00:00:00','2025-05-31 00:00:00',1,0,'yrt','2025-05-31 16:34:53','2025-05-31 16:34:53'),(17,2,'2025-06-01 00:00:00','2025-06-01 00:00:00',1,1,'yukt','2025-05-31 22:20:06','2025-05-31 22:20:15'),(18,3,'2025-06-01 00:00:00','2025-06-01 00:00:00',1,0,'bdgtrhe','2025-06-01 12:43:20','2025-06-01 12:43:20');
/*!40000 ALTER TABLE `ausencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docentes`
--

DROP TABLE IF EXISTS `docentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `docentes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dni` varchar(45) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido1` varchar(100) NOT NULL,
  `apellido2` varchar(100) DEFAULT NULL,
  `clave` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_ingreso` date NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sexo` enum('H','M','O') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `docentes_dni_unique` (`dni`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docentes`
--

LOCK TABLES `docentes` WRITE;
/*!40000 ALTER TABLE `docentes` DISABLE KEYS */;
INSERT INTO `docentes` VALUES (1,'12345678A','Ana','García','López','$2y$12$uc1ibpJi1IQvSK/wAPXQxesk9./91VpoVjxx3nnVvx5SfppDqE.Xy',0,'2022-09-01','1990-05-15','M','2025-05-25 18:10:27','2025-05-25 18:10:27'),(2,'87654321B','Carlos','Martínez','Ruiz','$2y$12$zU/moxu7CJBwYitAovOohOBQiT/LNNJUISzFdU7GjLARxs4gwXkli',1,'2020-01-10','1985-08-20','H','2025-05-25 18:10:27','2025-05-25 18:10:27'),(3,'47937310C','Cris','Cardona','C','$2y$12$35jsEJ0Vopz7HzoEK99rvePxrfutbZ/KNpZiFRyfEYOjhFl/iHRwK',1,'2020-10-26','2003-10-16','H','2025-05-25 18:31:31','2025-05-25 18:31:31'),(4,'23456789C','Marta','López','Sánchez','7764bf5ce91755dfa168cef81d61c9c7b38cba092aa75f09259e1017955886c7',0,'2023-02-10','1992-07-05','M','2025-05-27 13:34:56','2025-05-27 13:34:56'),(5,'34567890D','José','Martínez',NULL,'e640d5d250b61ef7c3e774237f5f7e82c854155d91047e518a9105f1340aa81e',0,'2020-05-20','1980-12-30','H','2025-05-27 13:34:56','2025-05-27 13:34:56'),(6,'45678901E','Andrea','Torres','Delgado','d4eaa1d3615e13c4d3cd3b47e90033eabe60b520bf8b241f09f780ffb3802e40',1,'2024-03-18','1995-09-11','M','2025-05-27 13:34:56','2025-05-27 13:34:56');
/*!40000 ALTER TABLE `docentes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guardias`
--

DROP TABLE IF EXISTS `guardias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guardias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `aula` varchar(50) NOT NULL,
  `docente_id` bigint(20) unsigned NOT NULL,
  `docente_id_temp` bigint(20) unsigned DEFAULT NULL,
  `ausencia_id` bigint(20) unsigned NOT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `guardias_ausencia_id_foreign` (`ausencia_id`),
  KEY `guardias_docente_id_foreign` (`docente_id`),
  CONSTRAINT `guardias_ausencia_id_foreign` FOREIGN KEY (`ausencia_id`) REFERENCES `ausencias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `guardias_docente_id_foreign` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guardias`
--

LOCK TABLES `guardias` WRITE;
/*!40000 ALTER TABLE `guardias` DISABLE KEYS */;
INSERT INTO `guardias` VALUES (8,'2025-05-28','12:00:00','Aula de Música',3,NULL,13,'cubierta','2025-05-28 19:14:09','2025-05-28 19:14:09');
/*!40000 ALTER TABLE `guardias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025_05_17_164107_create_docentes_table',1),(2,'2025_05_17_165704_create_ausencias_table',1),(3,'2025_05_17_165740_create_guardias_table',1),(4,'2025_05_17_165806_create_jornadas_table',1),(5,'2025_05_17_165826_create_horarios_table',1),(6,'2025_05_17_183257_rename_jornadas_and_horarios_tables',1),(7,'2025_05_17_192730_create_sessions_table',1),(8,'2025_05_24_170136_create_personal_access_tokens_table',1),(9,'2025_05_25_113602_change_fecha_fields_to_datetime_in_ausencias',1),(10,'2025_05_25_190832_create_personal_access_tokens_table',2),(11,'2025_05_25_191832_create_personal_access_tokens_table',3),(12,'2025_05_25_195610_create_cache_table',4),(13,'2025_05_25_212917_add_estado_to_guardias_table',5),(14,'2025_05_25_220801_add_todoeldia_to_ausencias_table',6),(15,'2025_05_26_143201_change_docente_id_type_in_guardias_table',7),(16,'2025_05_28_200111_change_aula_length_in_guardias_table',8),(17,'2025_05_28_200828_fix_guardias_foreign_key',9),(18,'2025_05_28_202344_replace_docente_id_in_guardias',10);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro_jornada`
--

DROP TABLE IF EXISTS `registro_jornada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registro_jornada` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `docente_id` bigint(20) unsigned NOT NULL,
  `inicio` datetime NOT NULL,
  `fin` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jornadas_docente_id_foreign` (`docente_id`),
  CONSTRAINT `jornadas_docente_id_foreign` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro_jornada`
--

LOCK TABLES `registro_jornada` WRITE;
/*!40000 ALTER TABLE `registro_jornada` DISABLE KEYS */;
INSERT INTO `registro_jornada` VALUES (1,1,'2025-05-25 22:20:28','2025-05-25 22:20:32','2025-05-25 20:20:28','2025-05-25 20:20:32'),(2,2,'2025-05-25 22:35:57','2025-05-25 22:35:59','2025-05-25 20:35:57','2025-05-25 20:35:59'),(3,3,'2025-05-25 22:37:20','2025-05-25 22:37:23','2025-05-25 20:37:20','2025-05-25 20:37:23'),(4,1,'2025-05-26 13:23:53','2025-05-26 13:23:59','2025-05-26 11:23:53','2025-05-26 11:23:59'),(5,1,'2025-05-26 13:35:59','2025-05-26 23:59:59','2025-05-26 11:35:59','2025-05-28 17:45:22'),(6,1,'2025-05-28 19:45:22','2025-05-28 19:45:24','2025-05-28 17:45:22','2025-05-28 17:45:24'),(7,3,'2025-05-28 22:03:46','2025-05-28 22:03:50','2025-05-28 20:03:46','2025-05-28 20:03:50'),(8,1,'2025-05-29 20:34:33','2025-05-29 20:34:36','2025-05-29 18:34:33','2025-05-29 18:34:36'),(9,2,'2025-05-30 14:15:06','2025-05-30 14:15:08','2025-05-30 12:15:06','2025-05-30 12:15:08'),(10,3,'2025-05-31 00:47:31','2025-05-31 00:47:36','2025-05-30 22:47:31','2025-05-30 22:47:36'),(15,3,'2025-05-31 17:41:10','2025-05-31 17:51:35','2025-05-31 15:41:10','2025-05-31 15:51:35'),(16,3,'2025-05-31 23:18:04','2025-05-31 23:18:05','2025-05-31 21:18:04','2025-05-31 21:18:05'),(17,1,'2025-05-31 23:47:09','2025-05-31 23:47:11','2025-05-31 21:47:09','2025-05-31 21:47:11'),(18,2,'2025-06-01 00:19:56','2025-06-01 00:19:58','2025-05-31 22:19:56','2025-05-31 22:19:58'),(19,2,'2025-06-01 02:18:42','2025-06-01 02:18:46','2025-06-01 00:18:42','2025-06-01 00:18:46'),(20,3,'2025-06-01 12:28:58','2025-06-01 12:28:59','2025-06-01 10:28:58','2025-06-01 10:28:59'),(21,3,'2025-06-01 18:12:54','2025-06-01 18:12:55','2025-06-01 16:12:54','2025-06-01 16:12:55'),(22,3,'2025-06-01 18:33:58','2025-06-01 18:34:00','2025-06-01 16:33:58','2025-06-01 16:34:00'),(23,3,'2025-06-01 18:35:27','2025-06-01 18:35:30','2025-06-01 16:35:27','2025-06-01 16:35:30');
/*!40000 ALTER TABLE `registro_jornada` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sesiones_lectivas`
--

DROP TABLE IF EXISTS `sesiones_lectivas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sesiones_lectivas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `docente_id` bigint(20) unsigned NOT NULL,
  `dia_semana` enum('L','M','X','J','V') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `aula` varchar(45) NOT NULL,
  `materia` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `horarios_docente_id_foreign` (`docente_id`),
  CONSTRAINT `horarios_docente_id_foreign` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=293 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sesiones_lectivas`
--

LOCK TABLES `sesiones_lectivas` WRITE;
/*!40000 ALTER TABLE `sesiones_lectivas` DISABLE KEYS */;
INSERT INTO `sesiones_lectivas` VALUES (199,1,'L','08:00:00','09:00:00','A01','Matemáticas','2025-06-01 16:20:07','2025-06-01 16:20:07'),(200,1,'L','09:00:00','10:00:00','A12','Lengua','2025-06-01 16:20:07','2025-06-01 16:20:07'),(201,1,'L','10:00:00','11:00:00','A03','Ciencias Naturales','2025-06-01 16:20:07','2025-06-01 16:20:07'),(202,1,'L','11:00:00','12:00:00','Sala de profesores',' Guardia','2025-06-01 16:20:07','2025-06-01 16:20:07'),(203,1,'L','12:00:00','13:00:00','A05','Inglés','2025-06-01 16:20:07','2025-06-01 16:20:07'),(204,1,'L','13:00:00','14:00:00','A08','Educación Física','2025-06-01 16:20:07','2025-06-01 16:20:07'),(205,1,'M','08:00:00','09:00:00','A04','Matemáticas','2025-06-01 16:20:07','2025-06-01 16:20:07'),(206,1,'M','09:00:00','10:00:00','A02','Lengua','2025-06-01 16:20:07','2025-06-01 16:20:07'),(207,1,'M','10:00:00','11:00:00','A06','Historia','2025-06-01 16:20:07','2025-06-01 16:20:07'),(208,1,'L','11:00:00','12:00:00','Sala de profesores',' Guardia','2025-06-01 16:20:07','2025-06-01 16:20:07'),(209,1,'M','12:00:00','13:00:00','A10','Francés','2025-06-01 16:20:07','2025-06-01 16:20:07'),(210,1,'M','13:00:00','14:00:00','A11','Educación Plástica','2025-06-01 16:20:07','2025-06-01 16:20:07'),(211,1,'X','08:00:00','09:00:00','A09','Matemáticas','2025-06-01 16:20:07','2025-06-01 16:20:07'),(212,1,'X','09:00:00','10:00:00','A01','Lengua','2025-06-01 16:20:07','2025-06-01 16:20:07'),(213,1,'X','10:00:00','11:00:00','A04','Ciencias Sociales','2025-06-01 16:20:07','2025-06-01 16:20:07'),(214,1,'L','11:00:00','12:00:00','Sala de profesores',' Guardia','2025-06-01 16:20:07','2025-06-01 16:20:07'),(215,1,'X','12:00:00','13:00:00','A03','Música','2025-06-01 16:20:07','2025-06-01 16:20:07'),(216,1,'X','13:00:00','14:00:00','A07','Religión','2025-06-01 16:20:07','2025-06-01 16:20:07'),(217,1,'J','08:00:00','09:00:00','A10','Filosofía','2025-06-01 16:20:07','2025-06-01 16:20:07'),(218,1,'J','09:00:00','10:00:00','A05','Lengua','2025-06-01 16:20:07','2025-06-01 16:20:07'),(219,1,'J','10:00:00','11:00:00','A02','Matemáticas','2025-06-01 16:20:07','2025-06-01 16:20:07'),(220,1,'L','11:00:00','12:00:00','Sala de profesores',' Guardia','2025-06-01 16:20:07','2025-06-01 16:20:07'),(221,1,'J','12:00:00','13:00:00','A06','Tecnología','2025-06-01 16:20:07','2025-06-01 16:20:07'),(222,1,'J','13:00:00','14:00:00','A11','Educación Física','2025-06-01 16:20:07','2025-06-01 16:20:07'),(223,1,'V','08:00:00','09:00:00','A12','Lengua','2025-06-01 16:20:07','2025-06-01 16:20:07'),(224,1,'V','09:00:00','10:00:00','A03','Matemáticas','2025-06-01 16:20:07','2025-06-01 16:20:07'),(225,1,'V','10:00:00','11:00:00','A07','Ciencias Naturales','2025-06-01 16:20:07','2025-06-01 16:20:07'),(226,1,'L','11:00:00','12:00:00','Sala de profesores',' Guardia','2025-06-01 16:20:07','2025-06-01 16:20:07'),(227,1,'V','12:00:00','13:00:00','A08','Educación Plástica','2025-06-01 16:20:07','2025-06-01 16:20:07'),(228,1,'V','13:00:00','14:00:00','A09','Inglés','2025-06-01 16:20:07','2025-06-01 16:20:07'),(229,1,'M','11:00:00','12:00:00','Sala de profesores',' Guardia','2025-06-01 16:22:08','2025-06-01 16:22:08'),(230,1,'X','11:00:00','12:00:00','Sala de profesores',' Guardia','2025-06-01 16:22:08','2025-06-01 16:22:08'),(231,1,'J','11:00:00','12:00:00','Sala de profesores',' Guardia','2025-06-01 16:22:08','2025-06-01 16:22:08'),(232,1,'V','11:00:00','12:00:00','Sala de profesores',' Guardia','2025-06-01 16:23:22','2025-06-01 16:23:22'),(233,2,'L','08:00:00','09:00:00','A01','Historia','2025-06-01 16:27:53','2025-06-01 16:27:53'),(234,2,'L','09:00:00','10:00:00','A02','Matemáticas','2025-06-01 16:27:53','2025-06-01 16:27:53'),(235,2,'L','10:00:00','11:00:00','Sala de profesores','Guardia','2025-06-01 16:27:53','2025-06-01 16:27:53'),(236,2,'L','11:00:00','12:00:00','A03','Lengua','2025-06-01 16:27:53','2025-06-01 16:27:53'),(237,2,'L','12:00:00','13:00:00','A04','Ciencias','2025-06-01 16:27:53','2025-06-01 16:27:53'),(238,2,'L','13:00:00','14:00:00','A05','Educación Física','2025-06-01 16:27:53','2025-06-01 16:27:53'),(239,2,'M','08:00:00','09:00:00','A06','Geografía','2025-06-01 16:27:53','2025-06-01 16:27:53'),(240,2,'M','09:00:00','10:00:00','A07','Matemáticas','2025-06-01 16:27:53','2025-06-01 16:27:53'),(241,2,'M','10:00:00','11:00:00','Sala de profesores','Guardia','2025-06-01 16:27:53','2025-06-01 16:27:53'),(242,2,'M','11:00:00','12:00:00','A08','Lengua','2025-06-01 16:27:53','2025-06-01 16:27:53'),(243,2,'M','12:00:00','13:00:00','A09','Biología','2025-06-01 16:27:53','2025-06-01 16:27:53'),(244,2,'M','13:00:00','14:00:00','A10','Plástica','2025-06-01 16:27:53','2025-06-01 16:27:53'),(245,2,'X','08:00:00','09:00:00','A11','Lengua','2025-06-01 16:27:53','2025-06-01 16:27:53'),(246,2,'X','09:00:00','10:00:00','A12','Historia','2025-06-01 16:27:53','2025-06-01 16:27:53'),(247,2,'X','10:00:00','11:00:00','Sala de profesores','Guardia','2025-06-01 16:27:53','2025-06-01 16:27:53'),(248,2,'X','11:00:00','12:00:00','A01','Ciencias','2025-06-01 16:27:53','2025-06-01 16:27:53'),(249,2,'X','12:00:00','13:00:00','A02','Matemáticas','2025-06-01 16:27:53','2025-06-01 16:27:53'),(250,2,'X','13:00:00','14:00:00','A03','Música','2025-06-01 16:27:53','2025-06-01 16:27:53'),(251,2,'J','08:00:00','09:00:00','A04','Biología','2025-06-01 16:27:53','2025-06-01 16:27:53'),(252,2,'J','09:00:00','10:00:00','A05','Lengua','2025-06-01 16:27:53','2025-06-01 16:27:53'),(253,2,'J','10:00:00','11:00:00','Sala de profesores','Guardia','2025-06-01 16:27:53','2025-06-01 16:27:53'),(254,2,'J','11:00:00','12:00:00','A06','Historia','2025-06-01 16:27:53','2025-06-01 16:27:53'),(255,2,'J','12:00:00','13:00:00','A07','Ciencias','2025-06-01 16:27:53','2025-06-01 16:27:53'),(256,2,'J','13:00:00','14:00:00','A08','Matemáticas','2025-06-01 16:27:53','2025-06-01 16:27:53'),(257,2,'V','08:00:00','09:00:00','A09','Educación Física','2025-06-01 16:27:53','2025-06-01 16:27:53'),(258,2,'V','09:00:00','10:00:00','A10','Lengua','2025-06-01 16:27:53','2025-06-01 16:27:53'),(259,2,'V','10:00:00','11:00:00','Sala de profesores','Guardia','2025-06-01 16:27:53','2025-06-01 16:27:53'),(260,2,'V','11:00:00','12:00:00','A11','Geografía','2025-06-01 16:27:53','2025-06-01 16:27:53'),(261,2,'V','12:00:00','13:00:00','A12','Historia','2025-06-01 16:27:53','2025-06-01 16:27:53'),(262,2,'V','13:00:00','14:00:00','A01','Plástica','2025-06-01 16:27:53','2025-06-01 16:27:53'),(263,3,'L','08:00:00','09:00:00','A02','Matemáticas','2025-06-01 16:30:07','2025-06-01 16:30:07'),(264,3,'L','09:00:00','10:00:00','A03','Lengua','2025-06-01 16:30:07','2025-06-01 16:30:07'),(265,3,'L','10:00:00','11:00:00','A04','Historia','2025-06-01 16:30:07','2025-06-01 16:30:07'),(266,3,'L','11:00:00','12:00:00','A05','Ciencias','2025-06-01 16:30:07','2025-06-01 16:30:07'),(267,3,'L','13:00:00','14:00:00','A06','Música','2025-06-01 16:30:07','2025-06-01 16:30:07'),(268,3,'M','08:00:00','09:00:00','A07','Lengua','2025-06-01 16:30:07','2025-06-01 16:30:07'),(269,3,'M','09:00:00','10:00:00','A08','Geografía','2025-06-01 16:30:07','2025-06-01 16:30:07'),(270,3,'M','10:00:00','11:00:00','A09','Matemáticas','2025-06-01 16:30:07','2025-06-01 16:30:07'),(271,3,'M','11:00:00','12:00:00','A10','Educación Física','2025-06-01 16:30:07','2025-06-01 16:30:07'),(272,3,'M','13:00:00','14:00:00','A11','Ciencias','2025-06-01 16:30:07','2025-06-01 16:30:07'),(273,3,'X','08:00:00','09:00:00','A12','Historia','2025-06-01 16:30:07','2025-06-01 16:30:07'),(274,3,'X','09:00:00','10:00:00','A01','Plástica','2025-06-01 16:30:07','2025-06-01 16:30:07'),(275,3,'X','10:00:00','11:00:00','A02','Matemáticas','2025-06-01 16:30:07','2025-06-01 16:30:07'),(276,3,'X','11:00:00','12:00:00','A03','Lengua','2025-06-01 16:30:07','2025-06-01 16:30:07'),(277,3,'X','13:00:00','14:00:00','A04','Tecnología','2025-06-01 16:30:07','2025-06-01 16:30:07'),(278,3,'J','08:00:00','09:00:00','A05','Música','2025-06-01 16:30:07','2025-06-01 16:30:07'),(279,3,'J','09:00:00','10:00:00','A06','Historia','2025-06-01 16:30:07','2025-06-01 16:30:07'),(280,3,'J','10:00:00','11:00:00','A07','Lengua','2025-06-01 16:30:07','2025-06-01 16:30:07'),(281,3,'J','11:00:00','12:00:00','A08','Ciencias','2025-06-01 16:30:07','2025-06-01 16:30:07'),(282,3,'J','13:00:00','14:00:00','A09','Educación Física','2025-06-01 16:30:07','2025-06-01 16:30:07'),(283,3,'V','08:00:00','09:00:00','A10','Matemáticas','2025-06-01 16:30:07','2025-06-01 16:30:07'),(284,3,'V','09:00:00','10:00:00','A11','Lengua','2025-06-01 16:30:07','2025-06-01 16:30:07'),(285,3,'V','10:00:00','11:00:00','A12','Ciencias','2025-06-01 16:30:07','2025-06-01 16:30:07'),(286,3,'V','11:00:00','12:00:00','A01','Historia','2025-06-01 16:30:07','2025-06-01 16:30:07'),(287,3,'L','12:00:00','13:00:00','Sala de profesores','Guardia','2025-06-01 16:30:07','2025-06-01 16:30:07'),(288,3,'M','12:00:00','13:00:00','Sala de profesores','Guardia','2025-06-01 16:30:07','2025-06-01 16:30:07'),(289,3,'X','12:00:00','13:00:00','Sala de profesores','Guardia','2025-06-01 16:30:07','2025-06-01 16:30:07'),(290,3,'J','12:00:00','13:00:00','Sala de profesores','Guardia','2025-06-01 16:30:07','2025-06-01 16:30:07'),(291,3,'V','12:00:00','13:00:00','Sala de profesores','Guardia','2025-06-01 16:30:07','2025-06-01 16:30:07'),(292,3,'V','13:00:00','14:00:00','A02','Tecnología','2025-06-01 16:30:07','2025-06-01 16:30:07');
/*!40000 ALTER TABLE `sesiones_lectivas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-01 20:59:29
