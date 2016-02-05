<?php
class Extendware_EWPageCache_Block_Adminhtml_Config_Form_Field_Segmentable_Useragents extends Extendware_EWCore_Block_Mage_Adminhtml_System_Config_Form_Field_Array_Abstract
{
	protected $_addAfter = false;
    public function __construct()
    {
        $this->addColumn('group', array(
            'label' => $this->__('Group'),
        	'class' => 'required-entry',
        	'style' => 'width: 99%',
        ));
        
        $this->addColumn('regexp', array(
            'label' => $this->__('User Agent RegExp'),
        	'class' => 'required-entry',
        	'style' => 'width: 99%',
        ));
        
        $this->_addButtonLabel = $this->__('Add User Agent');
        parent::__construct();
    }
}