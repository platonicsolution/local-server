<?php
class CreteSol_Survey_Model_Dateoptions
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'manual', 'label'=>Mage::helper('adminhtml')->__('Manual Date')),
            array('value' => 'current', 'label'=>Mage::helper('adminhtml')->__('Current Date'))
           
        );
    }

}
?>