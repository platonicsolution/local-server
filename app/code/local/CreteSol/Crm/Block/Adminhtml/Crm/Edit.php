<?php
 
class CreteSol_Crm_Block_Adminhtml_Crm_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();       
        $this->_objectId = 'id';
        $this->_blockGroup = 'crm';
        $this->_controller = 'adminhtml_crm';
 
        $this->_updateButton('save', 'label','Save');
       //$this->_updateButton('delete', 'label', 'Delete');
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Delete History'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('crm_data') && Mage::registry('crm_data')->getId() ) {
            return $this->__("CRM  %s", $this->htmlEscape(Mage::registry('crm_data')->getTitle()));
        } else {
            return $this->__('CRM Detail');
        }
    }
}