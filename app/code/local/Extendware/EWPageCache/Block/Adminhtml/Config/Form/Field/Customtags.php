<?php
class Extendware_EWPageCache_Block_Adminhtml_Config_Form_Field_Customtags extends Extendware_EWCore_Block_Mage_Adminhtml_System_Config_Form_Field_Array_Abstract
{
	protected $_addAfter = false;
    public function __construct()
    {
    	$options = array(
    		array ('label' => ' ---- ', 'value' => ''),
    		array(
    			'label' => $this->__('Product Events'), 
    			'value' => Mage::getSingleton('ewpagecache/adminhtml_config_data_option_autoflushing_productevent')->toFormSelectOptionArray()
    		),
    		array(
    			'label' => $this->__('Category Events'), 
    			'value' => Mage::getSingleton('ewpagecache/adminhtml_config_data_option_autoflushing_categoryevent')->toFormSelectOptionArray()
    		),
    		array(
    			'label' => $this->__('CMS Events'), 
    			'value' => Mage::getSingleton('ewpagecache/adminhtml_config_data_option_autoflushing_cmsevent')->toFormSelectOptionArray()
    		),
    	);

        $this->addColumn('event', array(
        	'type' => 'select',
            'label' => $this->__('Event'),
        	'class' => 'required-entry',
        	'style' => 'width: 99%',
        	'values' => $options,
        ));
        
        $this->addColumn('tags', array(
            'label' => $this->__('Tags (comma separated)'),
        	'class' => 'required-entry',
        	'style' => 'width: 99%',
        	'ewhelp' => $this->__('Comma separated list of tags')
        ));
        
        $this->_addButtonLabel = $this->__('Add Tags');
        parent::__construct();
    }
}