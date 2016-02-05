<?php
//$installer = $this;
// 
//$installer->startSetup();
// 
//$installer->run("
// 
//-- DROP TABLE IF EXISTS {$this->getTable('crm')};
//CREATE TABLE {$this->getTable('crm')} (
//  `id` int(11) unsigned NOT NULL auto_increment COMMENT 'Auto Increment Id',
//  `order_id` int(11) NOT NULL default 0 COMMENT 'Order Id',
//  `call_status` varchar(255)  NULL COMMENT 'Call Status A,U,V,P',
//  `phone_no` varchar(255)     NULL COMMENT 'Optional phone no',
//  `note` text  NULL COMMENT 'Call notes ',
//  `order_options` text  NULL COMMENT 'Order Extra Options',
//  `ref_order_id` int(11) NOT NULL default 0 COMMENT 'Order Reference', 
//  `created_time` timestamp NULL COMMENT 'Data and Time',
//  `update_time` timestamp NULL COMMENT 'Data and Time',
//  PRIMARY KEY (`id`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8;
// 
//    ");
//$installer->endSetup();