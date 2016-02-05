<?php
 
class Psolz_Returnpolicy_Model_Mysql4_Returnpolicy extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        //$this->_init('sales/order', 'entity_id');
        $this->_init('returnpolicy/returnpolicy', 'id');//<module>_id
    }
}