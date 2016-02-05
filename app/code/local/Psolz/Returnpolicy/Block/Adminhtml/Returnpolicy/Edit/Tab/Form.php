<?php
 
class Psolz_Returnpolicy_Block_Adminhtml_Returnpolicy_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('returnpolicy_form', array('legend'=>Mage::helper('returnpolicy')->__('Return Policy information')));
       
        $fieldset->addField('policy_name', 'text', array(
            'label'     => $this->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'policy_name',
        ));
 $fieldset->addField('policy_period', 'text', array(
            'label'     => $this->__('Period'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'policy_period',
        ));
        $fieldset->addField('status', 'select', array(
            'label'     => $this->__('Status'),
            'name'      => 'status',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => $this->__('Active'),
                ),
 
                array(
                    'value'     => 0,
                    'label'     => $this->__('Inactive'),
                ),
            ),
        ));
       
        $fieldset->addField('policy_description', 'editor', array(
            'name'      => 'policy_description',
            'label'     => $this->__('Description'),
            'title'     => $this->__('Description'),
            'style'     => 'width:98%; height:400px;',
            'wysiwyg'   => false,
            'required'  => true,
        ));
      
        if ( Mage::getSingleton('adminhtml/session')->getReturnpolicyData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getReturnpolicyData());
            Mage::getSingleton('adminhtml/session')->setReturnpolicyData(null);
        } elseif ( Mage::registry('returnpolicy_data') ) {
            $form->setValues(Mage::registry('returnpolicy_data')->getData());
        }
        return parent::_prepareForm();
    }
}