<?php
class Extendware_EWPageCache_Block_Adminhtml_Config_Form_Field_Translatedcustomergroups extends Extendware_EWCore_Block_Mage_Adminhtml_System_Config_Form_Field_Array_Abstract
{
	protected $_addAfter = false;
    public function __construct()
    {
        $this->addColumn('from_id', array(
            'label' => $this->__('From Customer Group ID'),
        	'class' => 'required-entry validate-digits',
        	'style' => 'width: 99%',
        ));
        
        $this->addColumn('to_id', array(
            'label' => $this->__('To Customer Group ID'),
        	'class' => 'required-entry validate-digits',
        	'style' => 'width: 99%',
        ));
        $this->_addButtonLabel = $this->__('Add Translation');
        parent::__construct();
    }
}