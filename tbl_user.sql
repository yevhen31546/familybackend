/*
SQLyog Community v13.1.5  (64 bit)
MySQL - 10.4.6-MariaDB : Database - familymember
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

USE `family_db`;

/*Table structure for table `tbl_users` */

DROP TABLE IF EXISTS `tbl_users`;

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `phone_no` varchar(255) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `user_role` tinyint(1) DEFAULT NULL,
  `series_id` varchar(60) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_users` */

insert  into `tbl_users`(`id`,`user_email`,`user_name`,`password`,`first_name`,`last_name`,`phone_no`,`created_date`,`user_role`,`series_id`,`remember_token`,`expires`) values 
(1,'rwerfdfdwer@ssdf.com','test username','$2y$10$Mj4MXU2HVF/fNY9XUtgK4Odqt4akvXYy8YREyqIjKiDoFGR3aexni','test first name','test last name','123456dddd','2019-10-18',NULL,NULL,NULL,NULL),
(2,'12345@sdf.com','wwwwwwwwwwww','$2y$10$YTZ5dEynpyi4At/xIxQ88e6/0HfiRDFeihQOONPtfcT4wOTuG3svK','test first name','test last name','5555','2019-10-18',NULL,NULL,NULL,NULL),
(3,'sdfsewrwerd@sdf.com','eeeeeee',NULL,'rwerwer','werwerwer','333333','2019-10-18',NULL,NULL,NULL,NULL),
(6,'eww@dsdf.com','555555',NULL,'ddddddd','ssssssssssssssssss','1234234234','2019-10-18',NULL,NULL,NULL,NULL),
(5,'345@sdf.com','werwerwer',NULL,'ffsf','werwer','234234','2019-10-18',NULL,NULL,NULL,NULL),
(7,'abcd@gmail.com','tttttttt','$2y$10$nI.0.UI0zRYD0HUy5up3L.Uk.afzopygy5cV6464aDjfy2.knMgka','ttttttttt','ttttttttttt','456345345','2019-10-18',NULL,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
