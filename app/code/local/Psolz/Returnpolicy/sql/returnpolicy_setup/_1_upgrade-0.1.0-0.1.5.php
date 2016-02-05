<?php
 
$installer = $this;
 
$installer->startSetup();
 

    //$installer->addAttribute("order", "my_custom_input_field", array("type"=>"varchar"));
    //$installer->addAttribute("quote", "my_custom_input_field", array("type"=>"varchar"));
    $this->addAttribute('order', 'lead_status', array(
    'type'          => 'varchar',
    'label'         => 'Lead Status',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => false,
        'user_defined'  =>  true
));
$this->addAttribute('quote', 'lead_status', array(
    'type'          => 'varchar',
    'label'         => 'Lead Status',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => false,
        'user_defined'  =>  true
));
 
$installer->endSetup();