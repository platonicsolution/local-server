<?php
class Extendware_EWPageCache_Block_Adminhtml_Config_Form_Field_Ignoredparameters extends Extendware_EWCore_Block_Mage_Adminhtml_System_Config_Form_Field_Array_Abstract
{
	protected $_addAfter = false;
    public function __construct()
    {
        $this->addColumn('param', array(
            'label' => $this->__('Parameter'),
        	'class' => 'required-entry',
        	'style' => 'width: 99%',
        ));
        
        $this->_addButtonLabel = $this->__('Add Param');
        parent::__construct();
    }
}