<?php
 
class CreteSol_Crm_Block_Adminhtml_Crm_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
             
        parent::__construct();
                  
        $this->_objectId = 'id';
        $this->_blockGroup = 'survey';
        $this->_controller = 'adminhtml_survey';
         
        $this->_updateButton('save', 'label', Mage::helper('crm')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('crm')->__('Delete'));
         
        
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('survey_data') && Mage::registry('survey_data')->getId() ) {
            return $this->__("Survey  %s", $this->htmlEscape(Mage::registry('survey_data')->getTitle()));
        } else {
            return $this->__('Survey Detail');
        }
    }
}