<?php
$installer = $this;
$installer->startSetup();
$this->addAttribute('order','lead_status1',array(
'type'=>'varchar',
'label'=>'lead status 1',
'visible'=>true,
'visible_on_front'=>false,
'required'=>false,
'default'=>'0',
'user_define'=>true
));
$installer->endSetup();