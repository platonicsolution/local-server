<?php
class Psolz_Returnpolicy_Model_Mysql4_Returnpolicy_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('returnpolicy/returnpolicy');
        //$this->_init('sales/order');
    }
}