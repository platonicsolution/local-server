<?php
 
class CreteSol_Crm_Block_Adminhtml_Crm_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('crm_form', array('legend'=>Mage::helper('crm')->__('CRM information')));
       
        
      
        if ( Mage::getSingleton('adminhtml/session')->getCrmData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getCrmData());
            Mage::getSingleton('adminhtml/session')->setCrmData(null);
        } elseif ( Mage::registry('crm_data') ) {
            $form->setValues(Mage::registry('crm_data')->getData());
        }
        return parent::_prepareForm();
    }
}