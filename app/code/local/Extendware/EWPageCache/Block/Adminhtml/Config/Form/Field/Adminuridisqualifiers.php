<?php
class Extendware_EWPageCache_Block_Adminhtml_Config_Form_Field_Adminuridisqualifiers extends Extendware_EWCore_Block_Mage_Adminhtml_System_Config_Form_Field_Array_Abstract
{
	protected $_addAfter = false;
    public function __construct()
    {
        $this->addColumn('string', array(
            'label' => $this->__('Disqualifying String'),
        	'class' => 'required-entry',
        	'style' => 'width: 99%',
        ));
        
        $this->_addButtonLabel = $this->__('Add Disqualifer');
        parent::__construct();
    }
}