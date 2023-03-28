/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.4.24-MariaDB : Database - vuelalejos
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vuelalejos` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `vuelalejos`;

/*Table structure for table `clients` */

DROP TABLE IF EXISTS `clients`;

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL AUTO_INCREMENT,
  `code_client` varchar(250) DEFAULT NULL,
  `name_client` varchar(250) DEFAULT NULL,
  `phone_client` varchar(250) DEFAULT NULL,
  `email_client` varchar(250) DEFAULT NULL,
  `pcreg_client` varchar(250) DEFAULT NULL,
  `usreg_client` varchar(250) DEFAULT NULL,
  `pcmod_client` varchar(250) DEFAULT NULL,
  `usmod_client` varchar(250) DEFAULT NULL,
  `date_created_client` date DEFAULT NULL,
  `date_updated_client` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;

/*Data for the table `clients` */

insert  into `clients`(`id_client`,`code_client`,`name_client`,`phone_client`,`email_client`,`pcreg_client`,`usreg_client`,`pcmod_client`,`usmod_client`,`date_created_client`,`date_updated_client`) values (1,'00001','JOEL MEDRANO GÜERE','982009013','jvmedranog@gmail.com',NULL,NULL,NULL,NULL,'2023-03-21','2023-03-21 17:11:57'),(21,'00002','CARLOS MEDRANO','12345678',NULL,'Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 21:56:36'),(22,'00003','TIFFANY CUNZA','12334456',NULL,'Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:41:50'),(23,'00004','SAORY CHURAMPI','',NULL,'Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:41:53'),(24,'00005','EDWIN MEDRANO','2314345345',NULL,'Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:42:09'),(25,'00006','CRISBEL GARCIA MATAMOROS','1231231',NULL,'Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:42:56');

/*Table structure for table `companies` */

DROP TABLE IF EXISTS `companies`;

CREATE TABLE `companies` (
  `id_company` int(11) NOT NULL AUTO_INCREMENT,
  `ruc_company` varchar(250) DEFAULT NULL,
  `name_company` varchar(250) DEFAULT NULL,
  `address_company` varchar(250) DEFAULT NULL,
  `city_company` varchar(250) DEFAULT NULL,
  `phone_company` varchar(250) DEFAULT NULL,
  `date_created_company` date DEFAULT NULL,
  `date_updated_company` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_company`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `companies` */

insert  into `companies`(`id_company`,`ruc_company`,`name_company`,`address_company`,`city_company`,`phone_company`,`date_created_company`,`date_updated_company`) values (1,'10472810371','LA MOLINA','GREGORIO SISA 133','CARABAYLLO','982009013','2022-10-26','2022-11-18 23:02:44'),(2,'10743183440','MARCAVALLE','AV. HUANDOY 7509','LOS OLIVOS','983263762','2022-10-26','2022-11-18 23:02:47'),(3,'10474909102','OROYA ANTIGUA','MERURIO ALTO','LOS OLIVOS',NULL,'2022-10-26','2022-11-18 23:02:50');

/*Table structure for table `correlatives` */

DROP TABLE IF EXISTS `correlatives`;

CREATE TABLE `correlatives` (
  `id_correlative` int(11) NOT NULL AUTO_INCREMENT,
  `code_correlative` varchar(250) DEFAULT NULL,
  `name_correlative` varchar(250) DEFAULT NULL,
  `initial_correlative` int(11) DEFAULT 1,
  `actual_correlative` int(11) DEFAULT 1,
  `final_correlative` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_correlative`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `correlatives` */

insert  into `correlatives`(`id_correlative`,`code_correlative`,`name_correlative`,`initial_correlative`,`actual_correlative`,`final_correlative`) values (1,'cl','clients',1,7,NULL),(2,'pr','preventives',1,22,NULL);

/*Table structure for table `layovers` */

DROP TABLE IF EXISTS `layovers`;

CREATE TABLE `layovers` (
  `id_layover` int(11) NOT NULL AUTO_INCREMENT,
  `id_preventive_layover` int(11) DEFAULT NULL,
  `type_layover` varchar(250) DEFAULT NULL,
  `airline_layover` int(11) DEFAULT NULL,
  `airport_departure_layover` varchar(250) DEFAULT NULL,
  `date_departure_layover` datetime DEFAULT NULL,
  `airport_arrival_layover` varchar(250) DEFAULT NULL,
  `date_arrival_layover` datetime DEFAULT NULL,
  `pcreg_layover` varchar(250) DEFAULT NULL,
  `usreg_layover` varchar(250) DEFAULT NULL,
  `pcmod_layover` varchar(250) DEFAULT NULL,
  `usmod_layover` varchar(250) DEFAULT NULL,
  `date_created_layover` date DEFAULT NULL,
  `date_updated_layover` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_layover`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

/*Data for the table `layovers` */

insert  into `layovers`(`id_layover`,`id_preventive_layover`,`type_layover`,`airline_layover`,`airport_departure_layover`,`date_departure_layover`,`airport_arrival_layover`,`date_arrival_layover`,`pcreg_layover`,`usreg_layover`,`pcmod_layover`,`usmod_layover`,`date_created_layover`,`date_updated_layover`) values (5,22,'IDA',2,'GKA','2023-03-21 21:55:00','HGU','2023-03-31 21:55:00','Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 21:56:36'),(6,22,'RETORNO',8,'AEY','2023-06-02 21:56:00','YBL','2023-03-29 21:56:00','Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 21:56:36'),(7,23,'IDA',3200,'LIM','2023-03-21 22:34:00','MXP','2023-03-22 22:34:00','Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:34:59'),(8,23,'RETORNO',2822,'MXP','2023-03-31 22:34:00','LIM','2023-04-01 22:34:00','Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:34:59'),(9,24,'IDA',2822,'LIM','2023-04-01 22:38:00','YAZ','2023-04-02 22:38:00','Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:38:18'),(10,25,'IDA',2,'HGU','2023-03-22 22:41:00','MAG','2023-03-23 22:41:00','Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:41:57'),(11,26,'IDA',2,'MAG','2023-04-06 22:42:00','MAG','2023-03-23 22:42:00','Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:42:56');

/*Table structure for table `preventives` */

DROP TABLE IF EXISTS `preventives`;

CREATE TABLE `preventives` (
  `id_preventive` int(11) NOT NULL AUTO_INCREMENT,
  `code_preventive` varchar(50) DEFAULT NULL,
  `id_user_preventive` int(11) DEFAULT NULL,
  `id_client_preventive` int(11) DEFAULT NULL,
  `type_preventive` varchar(50) DEFAULT NULL,
  `origin_preventive` varchar(50) DEFAULT NULL,
  `destination_preventive` varchar(50) DEFAULT NULL,
  `adult_preventive` int(11) DEFAULT 0,
  `child_preventive` int(11) DEFAULT 0,
  `baby_preventive` int(11) DEFAULT 0,
  `hand_luggage_preventive` int(11) DEFAULT 0,
  `hold_luggage_preventive` int(11) DEFAULT 0,
  `price_preventive` double DEFAULT 0,
  `services_preventive` varchar(250) DEFAULT NULL,
  `state_preventive` int(11) DEFAULT 1,
  `pcreg_preventive` varchar(250) DEFAULT NULL,
  `usreg_preventive` varchar(250) DEFAULT NULL,
  `pcmod_preventive` varchar(250) DEFAULT NULL,
  `usmod_preventive` varchar(250) DEFAULT NULL,
  `date_created_preventive` date DEFAULT NULL,
  `date_updated_preventive` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_preventive`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;

/*Data for the table `preventives` */

insert  into `preventives`(`id_preventive`,`code_preventive`,`id_user_preventive`,`id_client_preventive`,`type_preventive`,`origin_preventive`,`destination_preventive`,`adult_preventive`,`child_preventive`,`baby_preventive`,`hand_luggage_preventive`,`hold_luggage_preventive`,`price_preventive`,`services_preventive`,`state_preventive`,`pcreg_preventive`,`usreg_preventive`,`pcmod_preventive`,`usmod_preventive`,`date_created_preventive`,`date_updated_preventive`) values (22,'00016',1,21,NULL,'GKA','POM',2,2,1,1,1,231.34,'PRUEBAS',1,'Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 21:56:36'),(23,'00017',1,22,NULL,'LIM','MXP',2,0,0,1,2,1400,'',1,'Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:34:59'),(24,'00018',1,23,NULL,'LIM','YAZ',1,0,0,1,1,1203.56,'',1,'Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:38:18'),(25,'00019',1,24,NULL,'GKA','MAG',1,0,0,1,1,213.453,'',1,'Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:41:57'),(26,'00020',1,25,NULL,'MAG','LAE',1,0,0,0,0,0,'',1,'Joel-PC','jmedrano',NULL,NULL,'2023-03-21','2023-03-21 22:42:56');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `rol_user` varchar(20) DEFAULT NULL,
  `picture_user` varchar(250) DEFAULT NULL,
  `displayname_user` varchar(250) DEFAULT NULL,
  `username_user` varchar(250) DEFAULT NULL,
  `password_user` varchar(250) DEFAULT NULL,
  `email_user` varchar(250) DEFAULT NULL,
  `phone_user` varchar(250) DEFAULT NULL,
  `address_user` varchar(250) DEFAULT NULL,
  `country_user` varchar(250) DEFAULT NULL,
  `city_user` varchar(250) DEFAULT NULL,
  `state_user` int(11) DEFAULT 1,
  `id_company_user` int(11) DEFAULT NULL,
  `last_login_user` datetime DEFAULT NULL,
  `pcreg_user` varchar(250) DEFAULT NULL,
  `usreg_user` varchar(250) DEFAULT NULL,
  `pcmod_user` varchar(250) DEFAULT NULL,
  `usmod_user` varchar(250) DEFAULT NULL,
  `token_user` text DEFAULT NULL,
  `token_exp_user` text DEFAULT NULL,
  `verification_user` int(11) NOT NULL DEFAULT 0,
  `date_created_user` date DEFAULT NULL,
  `date_updated_user` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id_user`,`rol_user`,`picture_user`,`displayname_user`,`username_user`,`password_user`,`email_user`,`phone_user`,`address_user`,`country_user`,`city_user`,`state_user`,`id_company_user`,`last_login_user`,`pcreg_user`,`usreg_user`,`pcmod_user`,`usmod_user`,`token_user`,`token_exp_user`,`verification_user`,`date_created_user`,`date_updated_user`) values (1,'administrador','joel.jpg','Joel Medrano','jmedrano','$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq','jvmedranog@gmail.com',NULL,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2Nzk1MDg0NjksImV4cCI6MTY3OTU5NDg2OSwiZGF0YSI6eyJpZCI6IjEiLCJlbWFpbCI6Imp2bWVkcmFub2dAZ21haWwuY29tIn19.jKh4YkmQMLWBXj1ZUhK1L6SK7anL8AHVvjsWrNgsh0s','1679594869',0,'2022-10-25','2023-03-22 13:07:49'),(75,'vendedor',NULL,'Tiffany Cunza Castillo','tcunza','$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq','cunza.dg@gmail.com',NULL,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2Nzg5MTc4OTMsImV4cCI6MTY3OTAwNDI5MywiZGF0YSI6eyJpZCI6Ijc1IiwiZW1haWwiOiJjdW56YS5kZ0BnbWFpbC5jb20ifX0.UYzJCEl7Vu8AekM3lJNUrWQ-ynwJtuK386sDThtnQTE','1679004293',0,'2022-03-15','2023-03-15 17:04:53');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
