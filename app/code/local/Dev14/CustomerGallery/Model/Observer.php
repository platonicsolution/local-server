<?php
class Dev14_CustomerGallery_Model_Observer
{
    public function addMassAction($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if(get_class($block) =='Mage_Adminhtml_Block_Widget_Grid_Massaction'
            && $block->getRequest()->getControllerName() == 'sales_order')
        {
            $block->addItem('newmodule', array(
                'label' => 'New Mass Action Title',
                'url' => Mage::app()->getStore()->getUrl('customergallery/controller/action'),
            ));
        }
    }
}