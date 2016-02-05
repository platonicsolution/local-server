<?php
class Extendware_EWPageCache_Block_Adminhtml_Config_Form_Field_Servers extends Extendware_EWCore_Block_Mage_Adminhtml_System_Config_Form_Field_Array_Abstract
{
	protected $_addAfter = false;
    public function __construct()
    {
        $this->addColumn('host', array(
            'label' => $this->__('Host'),
        	'style' => 'width:120px',
        	'class' => 'required-entry',
        ));
        
        $this->addColumn('port', array(
            'label' => $this->__('Port'),
            'style' => 'width:30px',
        ));
        $this->_addButtonLabel = $this->__('Add Servers');
        parent::__construct();
    }
}