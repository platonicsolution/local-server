<?php
class Extendware_EWPageCache_Block_Adminhtml_Config_Form_Field_Blocktags extends Extendware_EWCore_Block_Mage_Adminhtml_System_Config_Form_Field_Array_Abstract
{
	protected $_addAfter = false;
    public function __construct()
    {
        $this->addColumn('block_key', array(
            'label' => $this->__('Block Key'),
        	'class' => 'required-entry',
        	'style' => 'width: 99%',
        	'ewhelp' => $this->__('The block key is the key found in the layout files. For example the underlined layout definition is the block key:<br><br> &lt;block type="<u>checkout/cart_sidebar</u>" name="cart_sidebar" template="checkout/cart/sidebar.phtml" before="-"&gt;')
        ));
        
        $this->addColumn('tags', array(
            'label' => $this->__('Tags (comma separated)'),
        	'class' => 'required-entry',
        	'style' => 'width: 99%',
        	'ewhelp' => $this->__('Comma separated list of tags')
        ));
        
        $this->_addButtonLabel = $this->__('Add Block Tags');
        parent::__construct();
    }
}