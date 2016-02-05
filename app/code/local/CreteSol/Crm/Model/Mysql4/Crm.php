<?php
 
class CreteSol_Crm_Model_Mysql4_crm extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('crm/crm', 'id');
    }
}