<?php

$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();
$installer->run("

DROP TABLE IF EXISTS ".$installer->getTable('appointments/locations').";
CREATE TABLE ".$installer->getTable('appointments/locations')." (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(50) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `country` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS ".$installer->getTable('appointments/days').";
CREATE TABLE ".$installer->getTable('appointments/days')." (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS ".$installer->getTable('appointments/hours').";
CREATE TABLE ".$installer->getTable('appointments/hours')." (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `day_id` int(11) NOT NULL,
  `time` varchar(20) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `notes` text,
  `reservation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
");

//$installer->endSetup(); 

//DROP TABLE IF EXISTS {$this->getTable('ziente_locations')};
//CREATE TABLE IF NOT EXISTS {$this->getTable('ziente_locations')} (

//DROP TABLE IF EXISTS {$this->getTable('ziente_schedule')};
//CREATE TABLE IF NOT EXISTS {$this->getTable('ziente_schedule')} (