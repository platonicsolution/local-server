<?php 
class Psolz_Returnpolicy_Block_Adminhtml_Returnpolicy extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
       
        $this->_controller = 'adminhtml_returnpolicy';
         $this->_blockGroup = 'returnpolicy';
        $this->_headerText = $this->__('Return Policy');
        $this->_addButtonLabel = $this->__('Add Return policy');
        
        
          parent::__construct();
        
        
   }
  
        
    
}