<?php

$installer = $this;
 
$installer->startSetup();
    $this->addAttribute('order', 'lead_status', array(
    'type'            => 'varchar',
    'label'           => 'Lead Status',
    'visible'         => true,
    'required'         => false,
    'visible_on_front' => false,
    'default'           => '0',
    'user_defined'      =>  true
));
$this->addAttribute('quote', 'lead_status', array(
    'type'          => 'varchar',
    'label'         => 'Lead Status',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => false,
    'default'           => '0',
        'user_defined'  =>  true
));
 
$installer->endSetup();
 
//$installer = $this;
// 
//$installer->startSetup();
// 
//$installer->run("
// 
//-- DROP TABLE IF EXISTS {$this->getTable('returnpolicy')};
//CREATE TABLE {$this->getTable('returnpolicy')} (
//  `id` int(11) unsigned NOT NULL auto_increment,
//  `policy_name` varchar(255) NOT NULL default '',
//  `policy_description` text NOT NULL default '',
//  `policy_period` varchar(255) NOT NULL default '',
//  `status` smallint(6) NOT NULL default '0',
//  `created_time` datetime NULL,
//  `update_time` datetime NULL,
//  PRIMARY KEY (`id`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8;
// 
//    ");
//    $installer->addAttribute("order", "my_custom_input_field", array("type"=>"varchar"));
// 
//$installer->endSetup();