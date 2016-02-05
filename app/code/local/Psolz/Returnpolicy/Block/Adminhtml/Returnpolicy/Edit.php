<?php
 
class Psolz_Returnpolicy_Block_Adminhtml_Returnpolicy_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();       
        $this->_objectId = 'id';
        $this->_blockGroup = 'returnpolicy';
        $this->_controller = 'adminhtml_returnpolicy';
 
        $this->_updateButton('save', 'label','Save Policy');
        $this->_updateButton('delete', 'label', 'Delete Policy');
        $this->_addButton('returnpolicy_returnpolicy', array(
        'label' => $this->__('Add New Policy'),
        'onclick' => "setLocation('{$this->getUrl('*/*/new')}')",
    ));
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('returnpolicy_data') && Mage::registry('returnpolicy_data')->getId() ) {
            return $this->__("Edit Policy %s", $this->htmlEscape(Mage::registry('returnpolicy_data')->getTitle()));
        } else {
            return $this->__('Add Policy');
        }
    }
}