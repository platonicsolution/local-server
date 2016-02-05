<?php 
class CreteSol_Survey_Block_Adminhtml_Survey extends Mage_Core_Block_Template//Mage_Adminhtml_Block_Widget_Grid_Container
{
    public $_callHistory;
    public function __construct()
    {
       
        $this->_controller = 'adminhtml_survey';
         $this->_blockGroup = 'survey';
        $this->_headerText = $this->__('Survey');
        $this->_saveButtonLabel = $this->__('Save');
        
        
          parent::__construct();
        
        
   }
  
    
    public function getCrmDetail(){
       
        return $result = Mage::getModel('sales/order')->load($this->getRequest()->getParam('order_id'));
         //Zend_Debug::dump($result);
    }
    public function getCrmOrderItems($salesOrderid=0){
        $result = Mage::getModel('sales/order_item')->getCollection()->addAttributeToSelect('name')
        ->addAttributeToSelect('sku')
        ->addAttributeToSelect('qty_ordered')
        ->addAttributeToSelect('item_id')
        ->addFieldToFilter('order_id',$salesOrderid);
       // echo $salesOrderid;
        //zend_debug::dump($result->getData());
        return $result;
    }
    public function getHistory(){
        return Mage::getModel('crm/crm')->getCollection()->addFieldToFilter('order_id',$this->getRequest()->getParam('order_id'));
        //return $this;
    }
    public function getAdminUserName($userId=0)
    {
            
        if($userId > 0){
            return Mage::getModel('admin/user')->load($userId)->getUsername();
        }else{
            return NULL;
        }
    }
    
}