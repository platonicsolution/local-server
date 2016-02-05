<?php
class CreteSol_Crm_Block_Adminhtml_Crm_Edit_Tab_Customtab extends  Mage_Adminhtml_Block_Abstract{
    
    public function __construct(){
       
		parent::__construct();
		$this->setTemplate('crm/customtab.phtml');
	}
    
}