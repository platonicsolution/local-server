<?php
class CreteSol_Crm_Model_Dateoptions
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => '-10 days', 'label'=>Mage::helper('adminhtml')->__('Last 10 days to current date')),
            array('value' => '-2 weeks', 'label'=>Mage::helper('adminhtml')->__('Last 2 weeks to current date')),
             array('value' => '-3 weeks', 'label'=>Mage::helper('adminhtml')->__('Last 3 weeks to current date')),
              array('value' => '-4 weeks', 'label'=>Mage::helper('adminhtml')->__('Last 4 weeks to current date')),
            array('value' => '-2 months', 'label'=>Mage::helper('adminhtml')->__('Last 2 month to current date')),
            array('value' => '-4 months', 'label'=>Mage::helper('adminhtml')->__('last 4 months to current date')),
           
        );
    }

}
?>