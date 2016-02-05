<?php
 
class Psolz_Returnpolicy_Adminhtml_ReturnpolicyController extends Mage_Adminhtml_Controller_Action
{
 
    public function indexAction() {
        
        $this->loadLayout()->_setActiveMenu('returnpolicy')->_title($this->__('Return Policy'));      
        $this->_addContent($this->getLayout()->createBlock('returnpolicy/adminhtml_returnpolicy_grid'));
        $this->renderLayout();
       
    }
 
    public function editAction()
    {
        $id     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('returnpolicy/returnpolicy')->load($id);
         
        if ($model->getId() || $id == 0) {
 
            Mage::register('returnpolicy_data', $model);
            
            $this->loadLayout();
            $this->_setActiveMenu('returnpolicy');
            $this->_title($this->__('Edit Action'));
            //$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            //$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
           
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
           
            $this->_addContent($this->getLayout()->createBlock('returnpolicy/adminhtml_returnpolicy_edit'))
                 ->_addLeft($this->getLayout()->createBlock('returnpolicy/adminhtml_returnpolicy_edit_tabs'));
               
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Return Policy does not exist'));
            $this->_redirect('*/*/');
        }
         // Zend_Debug::dump($model->debug());
    }
   
    public function newAction()
    {
        $this->loadLayout();
            $this->_setActiveMenu('returnpolicy');
            $this->_title($this->__('Add Action'));
            
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
           
            $this->_addContent($this->getLayout()->createBlock('returnpolicy/adminhtml_returnpolicy_edit'))
                 ->_addLeft($this->getLayout()->createBlock('returnpolicy/adminhtml_returnpolicy_edit_tabs'));
               
            $this->renderLayout();
        
    }
   
    public function saveAction()
    {
        if ( $this->getRequest()->getPost() ) {
            try {
                $postData = $this->getRequest()->getPost();
                $model = Mage::getModel('returnpolicy/returnpolicy');
               
                $model->setId($this->getRequest()->getParam('id'))
                    ->setPolicyName($postData['policy_name'])
                    ->setPolicyDescription($postData['policy_description'])
                    ->setPolicyPeriod($postData['policy_period'])
                    ->setStatus($postData['status'])
                    ->setCreatedTime(Mage::getSingleton('core/date')->gmtDate())
                    ->setUpdateTime(Mage::getSingleton('core/date')->gmtDate())
                    ->save();
               
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Return Policy saved successfully '));
                Mage::getSingleton('adminhtml/session')->setReturnpolicyData(false);
 
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setReturnpolicyData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }
   
    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('returnpolicy/returnpolicy');
               
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                   
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Retun Policy deleted successfully'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
    /**
     * Product grid for AJAX request.
     * Sort and filter result for example.
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('returnpolicy/adminhtml_returnpolicy_grid')->toHtml()
        );
    }
}