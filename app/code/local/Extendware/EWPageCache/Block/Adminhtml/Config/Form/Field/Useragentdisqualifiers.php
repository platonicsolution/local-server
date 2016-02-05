<?php
class Extendware_EWPageCache_Block_Adminhtml_Config_Form_Field_Useragentdisqualifiers extends Extendware_EWCore_Block_Mage_Adminhtml_System_Config_Form_Field_Array_Abstract
{
	protected $_addAfter = false;
    public function __construct()
    {
        $this->addColumn('regexp', array(
            'label' => $this->__('User Agent RegExp'),
        	'class' => 'required-entry',
        	'style' => 'width: 99%',
        	'ewhelp' => $this->__('This must be a valid regular expression with the beginning and ending "/". For example, /mobile/. You can read the PHP preg documentation for more information'),
        ));
        
        $this->_addButtonLabel = $this->__('Add Disqualifer');
        parent::__construct();
    }
}