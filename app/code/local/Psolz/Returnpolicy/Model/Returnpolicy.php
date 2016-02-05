<?php
 
class Psolz_Returnpolicy_Model_Returnpolicy extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        //$this->_init('sales/order');
        $this->_init('returnpolicy/returnpolicy');
    }
}