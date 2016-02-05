<?php
class Extendware_EWPageCache_Block_Adminhtml_Config_Form_Field_Cookiedisqualifiers extends Extendware_EWCore_Block_Mage_Adminhtml_System_Config_Form_Field_Array_Abstract
{
	protected $_addAfter = false;
    public function __construct()
    {
        $this->addColumn('cookie', array(
            'label' => $this->__('Cookie'),
        	'class' => 'required-entry',
        	'style' => 'width: 99%',
        ));
        
        $this->_addButtonLabel = $this->__('Add Disqualifer');
        parent::__construct();
    }
}