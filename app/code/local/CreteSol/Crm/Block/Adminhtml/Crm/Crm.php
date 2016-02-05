<?php 
class CreteSol_Crm_Block_Adminhtml_Crm extends Mage_Core_Block_Template//Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
       
        $this->_controller = 'adminhtml_crm';
         $this->_blockGroup = 'crm';
        $this->_headerText = $this->__('CreteSol CRM');
        $this->_saveButtonLabel = $this->__('Save');
        
        
          parent::__construct();
        
        
   }
  
     public function getCheckData()
   {
    return "Yes I return to you";
   }
    
    
}