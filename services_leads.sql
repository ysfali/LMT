-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: 182.50.133.172
-- Generation Time: May 31, 2016 at 10:34 PM
-- Server version: 5.5.43
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `motherbeelanding`
--

-- --------------------------------------------------------

--
-- Table structure for table `services_leads`
--

CREATE TABLE `services_leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) NOT NULL,
  `email` mediumtext NOT NULL,
  `phone` varchar(1000) NOT NULL,
  `registered_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ref` varchar(10000) NOT NULL,
  `weeks` date NOT NULL,
  `comment` varchar(1000) DEFAULT 'No Comments',
  `status` varchar(100) DEFAULT 'unchecked',
  `qualified` varchar(100) NOT NULL,
  `sale` double NOT NULL,
  `utm_source` varchar(10) DEFAULT NULL,
  `utm_medium` varchar(10) DEFAULT NULL,
  `utm_campaign` varchar(10) DEFAULT NULL,
  `workshop` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=487 ;

--
-- Dumping data for table `services_leads`
--

INSERT INTO `services_leads` VALUES(480, 'Priyanka Sunchit Sharma', 'sharmapriyanka.1103@gmail.com', '9999032188', '2016-05-30 09:50:00', 'www.facebook.com/motherbee.in', '2015-06-04', '31/5,   She does not required anything right now.......Mintu', 'not qualified', '', 0, 'LeadAd', 'Facebook', 'Diet', '');
INSERT INTO `services_leads` VALUES(481, 'Nishi Gupta', 'nish_16in@yahoo.com', '9871644466', '2016-05-30 09:53:00', 'www.facebook.com/motherbee.in', '2016-10-11', '31/5, ,She inquired about ANC, but she is comfortable to come at Green Park center.......Mintu', 'qualified', '', 0, 'LeadAd', 'Facebook', 'Diet', 'yes');
INSERT INTO `services_leads` VALUES(482, 'Aayushi Gagan Bhatia', 'Ayushibhasin09@gmail.com', '8800848424', '2016-05-30 09:58:00', 'www.facebook.com/motherbee.in', '2016-01-01', '31/5, She inquired about ANC, but she is comfortable to come at Green Park center.,,,,,Mintu', 'qualified', '', 0, 'LeadAd', 'Facebook', 'Diet', 'yes');

