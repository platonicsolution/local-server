<?php
class Extendware_EWPageCache_Block_Adminhtml_Config_Form_Field_Injectors extends Extendware_EWCore_Block_Mage_Adminhtml_System_Config_Form_Field_Array_Abstract
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
        
        $this->addColumn('injector_key', array(
            'label' => $this->__('Injector Key'),
        	'class' => 'required-entry',
        	'style' => 'width: 99%',
        	'ewhelp' => $this->__('The injector key takes one of two formats. The first format is without a "/" and an example is <i>checkout_cart_sidebar</i>. When this format is used,
								then an injector located at <i>app/code/local/Extendware/EWPageCache/Model/Injector/Checkout/Cart/Siderbar.php</i> will be used.</p>
        	')
        ));
        
        $this->_addButtonLabel = $this->__('Add Injectors');
        parent::__construct();
    }
}