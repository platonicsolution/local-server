<?php
 
class CreteSol_Crm_Model_Crmorders extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('crm/crmorders');
    }
   
}