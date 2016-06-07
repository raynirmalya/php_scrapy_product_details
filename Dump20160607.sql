-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: localhost    Database: virgindb
-- ------------------------------------------------------
-- Server version	5.7.9-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `affiliate_category_mapping_tbl`
--

DROP TABLE IF EXISTS `affiliate_category_mapping_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `affiliate_category_mapping_tbl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) NOT NULL,
  `affiliate_site_id` int(10) NOT NULL,
  `affiliate_category_name` text NOT NULL,
  `affiliate_category_key` text NOT NULL,
  `title_specs` text NOT NULL,
  `url_part` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `affiliate_category_temp_mapping_tbl`
--

DROP TABLE IF EXISTS `affiliate_category_temp_mapping_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `affiliate_category_temp_mapping_tbl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) NOT NULL,
  `affiliate_site_id` int(10) NOT NULL,
  `affiliate_category_name` text NOT NULL,
  `affiliate_category_key` text NOT NULL,
  `title_specs` text NOT NULL,
  `url_part` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8259 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `affiliate_product_mapping_tbl`
--

DROP TABLE IF EXISTS `affiliate_product_mapping_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `affiliate_product_mapping_tbl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `affiliate_product_id` text NOT NULL,
  `attribute1` varchar(200) NOT NULL,
  `attribute2` varchar(200) NOT NULL,
  `attribute3` varchar(200) NOT NULL,
  `attribute4` varchar(200) NOT NULL,
  `product_link` text NOT NULL,
  `delivery_charge` int(10) NOT NULL,
  `cash_on_delivery` tinyint(1) NOT NULL,
  `one_day_delivery` tinyint(1) NOT NULL,
  `probable_delivery_date` text NOT NULL,
  `replacement_in_days` int(10) NOT NULL,
  `selling_price` int(10) NOT NULL,
  `original_price` int(10) NOT NULL,
  `discount_in_percentage` int(10) NOT NULL,
  `no_of_reviews` bigint(20) NOT NULL,
  `rating_out_of_five` bigint(20) NOT NULL,
  `no_of_users` bigint(20) NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17149 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `affiliate_sites`
--

DROP TABLE IF EXISTS `affiliate_sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `affiliate_sites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `affiliate_site_name` varchar(100) NOT NULL,
  `api_key` text NOT NULL,
  `base_url` varchar(50) NOT NULL,
  `product_base_url` text NOT NULL,
  `new` text NOT NULL,
  `popular` text NOT NULL,
  `high` text NOT NULL,
  `low` text NOT NULL,
  `relevance` text NOT NULL,
  `discount` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `emi_tbl`
--

DROP TABLE IF EXISTS `emi_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emi_tbl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bank` int(11) NOT NULL,
  `rate` int(20) NOT NULL,
  `site_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `offers_tbl`
--

DROP TABLE IF EXISTS `offers_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offers_tbl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `offer_details` text NOT NULL,
  `site_id` int(10) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=640 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page_links_tbl`
--

DROP TABLE IF EXISTS `page_links_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_links_tbl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `product_title` varchar(200) NOT NULL,
  `product_link` text NOT NULL,
  `main_link` text NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `visited_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17310 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_categories_tbl`
--

DROP TABLE IF EXISTS `product_categories_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_categories_tbl` (
  `category_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(500) NOT NULL,
  `parent_id` bigint(20) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_categories_temp_tbl`
--

DROP TABLE IF EXISTS `product_categories_temp_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_categories_temp_tbl` (
  `category_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(500) NOT NULL,
  `parent_id` bigint(20) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8259 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_image_tbl`
--

DROP TABLE IF EXISTS `product_image_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_image_tbl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `image_link` text NOT NULL,
  `dimension` varchar(20) NOT NULL,
  `images_saved_in` text NOT NULL,
  `site_id` int(10) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75121 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_old_price_tbl`
--

DROP TABLE IF EXISTS `product_old_price_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_old_price_tbl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `selling_price` bigint(20) NOT NULL,
  `original_price` bigint(20) NOT NULL,
  `discount` int(10) NOT NULL,
  `site_id` int(10) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17310 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_tbl`
--

DROP TABLE IF EXISTS `product_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_tbl` (
  `product_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_title` text NOT NULL,
  `our_product_title` text NOT NULL,
  `brand` text NOT NULL,
  `model` text NOT NULL,
  `related_to` varchar(70) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14275 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `specification_category_tbl`
--

DROP TABLE IF EXISTS `specification_category_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specification_category_tbl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `spec_category_name` varchar(200) NOT NULL,
  `site_id` int(10) NOT NULL,
  `product_category_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23033 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `specification_tbl`
--

DROP TABLE IF EXISTS `specification_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specification_tbl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `spec_category_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `site_id` int(10) NOT NULL,
  `spec_key` text NOT NULL,
  `spec_value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=552518 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-07  6:29:24
