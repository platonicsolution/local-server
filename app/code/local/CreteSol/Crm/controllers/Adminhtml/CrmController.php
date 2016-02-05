<?php

class CreteSol_Crm_Adminhtml_CrmController extends
    Mage_Adminhtml_Controller_Action
{


    public $salesObj;

    public $postCodeArray = array();

    public $skuCodeArray = array();
    
    
    protected function _isAllowed(){
    return Mage::getSingleton('admin/session')->isAllowed('crm/crmdashboard');
}


    public function indexAction()
    {


        $this->loadLayout();//->_setActiveMenu('crm')->_title($this->__('CRM'));
       // zend_debug::dump($a);
        //$this->_addContent($this->getLayout()->createBlock('crm/adminhtml_crm_grid'));

        $this->renderLayout();


    }


    public function editAction()
    {
        if($this->getRequest()->getParam('isAjax')){
            
           $id = $this->getRequest()->getParam('order_id');
            $model = Mage::getModel('sales/order')->load($id);
            Mage::register('crm_data', $model);
            //$this->loadLayout();
                //$this->_setActiveMenu('crm');
                //$this->_title($this->__('Edit Action'));
                //$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            //$blocks['left'] = $this->getLayout()->createBlock('crm/adminhtml_crm_edit_tabs');
                   // $this->renderLayout();
                  // $blocks['right'] = $this->getLayout()->createBlock('crm/adminhtml_crm_edit_tab_form')->toHtml();
                   //$blocks['form'] = $this->getLayout()->createBlock('crm/adminhtml_crm_edit_form')->toHtml();
                   $_blocks['custom'] = $this->getLayout()->createBlock('crm/adminhtml_crm')->setTemplate('crm/customtab.phtml')->toHtml();
                   $_blocks['history'] = $this->getLayout()->createBlock('crm/adminhtml_crm')->setTemplate('crm/history.phtml')->toHtml();
                   
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($_blocks));
                    
                    return;
        }

        $id = $this->getRequest()->getParam('order_id');

        $model = Mage::getModel('sales/order')->load($id);

        $userId = Mage::getSingleton('admin/session')->getUser()->getId();

        if ($model->getId() || $id == 0)
        {

            //if ($model->getAdvisor() == $userId || $model->getAdvisor() == NULL)

            //{

            Mage::register('crm_data', $model);


            $this->loadLayout();

            $this->_setActiveMenu('crm');

            $this->_title($this->__('Edit Action'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('crm/adminhtml_crm_edit'))->
                _addLeft($this->getLayout()->createBlock('crm/adminhtml_crm_edit_tabs'));


            $this->renderLayout();

            //} else

            //{

            // Mage::getSingleton('adminhtml/session')->addError($this->__('This order has been processed by an other advisor..'));

            //  $this->_redirect('*/*/');

            //}


        } else
        {

            Mage::getSingleton('adminhtml/session')->addError($this->__('CRM Order doesn\'t exists'));

            $this->_redirect('*/*/');

        }


    }


    public function newAction()
    {

        $this->loadLayout();

        $this->_setActiveMenu('crm');

        $this->_title($this->__('Add Action'));


        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);


        $this->_addContent($this->getLayout()->createBlock('crm/adminhtml_crm_edit'))->
            _addLeft($this->getLayout()->createBlock('crm/adminhtml_crm_edit_tabs'));


        $this->renderLayout();


    }


    public function saveAction()
    {

        $userId = Mage::getSingleton('admin/session')->getUser()->getId();

        //Zend_Debug::dump($this->getRequest()->getParams());exit;

        if ($this->getRequest()->getPost())
        {


            try
            {

                if ($this->getRequest()->getPost('deleteid'))
                {

                    $this->deleteAction();


                } else
                {

                    $postData = $this->getRequest()->getPost();

                    //if ($postData['call_status_value'])
                    //{


                        $model = Mage::getModel('crm/crm');


                        $model->setOrderId($this->getRequest()->getParam('order_id'))->setCallStatus($postData['call_status_value'])->
                            setPhoneNo($postData['phone_no'])->setNote($postData['comment'])->
                            setOrderOptions((!empty($postData['date'])) ? $postData['date'] : null)->
                            setRefOrderId($userId)->setCreatedTime(Mage::getSingleton('core/date')->gmtDate
                            ())->setUpdateTime(Mage::getSingleton('core/date')->gmtDate())->save();

                        $salesObj = Mage::getModel('sales/order')->load($this->getRequest()->getParam('order_id'));

                        $leadStatus = $salesObj->getLeadStatus();

                        $salesObj->setData('call_status', $postData['call_status_value']);

                        $salesObj->getResource()->saveAttribute($salesObj, 'call_status');

                        $salesObj->setData('advisor', $userId);

                        $salesObj->getResource()->saveAttribute($salesObj, 'advisor');
                        if (!empty($postData['date']))
                        {
                            $salesObj->setData('call_back_date', date('Y-m-d', strtotime($postData['date'])));

                            $salesObj->getResource()->saveAttribute($salesObj, 'call_back_date');
                        } else
                        {
                            $salesObj->setData('call_back_date', null);

                            $salesObj->getResource()->saveAttribute($salesObj, 'call_back_date');
                        }

                        if ($leadStatus != $postData['lead_status'])
                        {

                            $salesObj->setData('lead_status', $postData['lead_status']);

                            $salesObj->getResource()->saveAttribute($salesObj, 'lead_status');

                        }


                        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('CRM Call Record Saved Successfully '));

                        Mage::getSingleton('adminhtml/session')->setCrmData(false);


                        $this->_redirect('*/*/');

                        return;

                    //} else
                    //{
//
//                        Mage::getSingleton('adminhtml/session')->addError('There is some error ');
//
//                        Mage::getSingleton('adminhtml/session')->setCrmData($this->getRequest()->
//                            getPost());
//
//                        $this->_redirect('*/*/edit', array('order_id' => $this->getRequest()->getParam('order_id')));
//
//                        return;
//
//                    }

                }

            }

            catch (exception $e)
            {

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

                Mage::getSingleton('adminhtml/session')->setCrmData($this->getRequest()->
                    getPost());

                $this->_redirect('*/*/edit', array('order_id' => $this->getRequest()->getParam('order_id')));

                return;

            }

        }

        $this->_redirect('*/*/');

    }


    public function deleteAction()
    {

        if ($this->getRequest()->getPost('deleteid'))
        {

            try
            {

                $model = Mage::getModel('crm/crm');

                foreach ($this->getRequest()->getPost('deleteid') as $deleteId):

                    try
                    {

                        $r = $model->setId($deleteId)->delete();


                    }

                    catch (exception $e)
                    {

                        echo $e->getMessage();

                    }


                endforeach;


                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('History deleted successfully'));

                $this->_redirect('*/*/edit', array('order_id' => $this->getRequest()->getParam('order_id')));

            }

            catch (exception $e)
            {

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

                $this->_redirect('*/*/edit', array('order_id' => $this->getRequest()->getParam('order_id')));

            }

        } else
        {

            Mage::getSingleton('adminhtml/session')->addError('Unable to delete history there is some error..');

            $this->_redirect('*/*/edit', array('order_id' => $this->getRequest()->getParam('order_id')));


        }

    }

    /**

     * Product grid for AJAX request.

     * Sort and filter result for example.

     */

    public function gridAction()
    {

        $this->loadLayout();

        $this->getResponse()->setBody($this->getLayout()->createBlock('crm/adminhtml_crm_grid')->
            toHtml());

    }


    public function dashboardAction()
    {

        $this->loadLayout();

        $this->renderLayout();

    }

    public function getCrmReportAction()
    {

        //zend_debug::dump($this->getRequest()->getParams());

        $this->loadLayout();

        $this->_setActiveMenu('crm');

        $this->_title($this->__('Dashboard'));

        //$this->_addContent($this->getLayout()->createBlock('crm/adminhtml_dashboard'));

        $this->renderLayout();

        $this->_redirect('*/*/dashboard');

    }

    public function sampleDetailAction()
    {
       ini_set('memory_limit', '5556M');
        ini_set('max_execution_time',0);
        
        $block = $this->getLayout()->createBlock('crm/adminhtml_crm')->setTemplate('crm/reports.phtml')->toHtml();
          $html = $block;
         $this->getResponse()->setHeader('Content-Type', 'text/html')->setBody($html);
         return;
        
      
    }
    
    public function DupCustomerOrderIdAction(){
        $tele = $this->getRequest()->getParam('telephone');
        $email = $this->getRequest()->getParam('email');
        $offset = $this->getRequest()->getParam('offset');
        if($tele && $email){
            $filteredCollection = Mage::getModel('sales/order')->getCollection();
             $filteredCollection->
            addFieldToSelect('entity_id')
            ->addAttributeToFilter('lead_status', array('in' => array(CreteSol_Crm_Helper_Data::STATE_UNCONVERTED)))
            ->addFieldToFilter(array('main_table.customer_email', 'oadr.telephone'), array(array
                ('eq' => trim($email)), array(array('eq' => trim($tele)))));
               $filteredCollection->getSelect()->join(array('oadr' => Mage::getSingleton('core/resource')->
                getTableName('sales_flat_order_address')),
            'main_table.entity_id = oadr.parent_id AND oadr.address_type="billing"', array('oadr.postcode',
                'oadr.telephone'))->order('main_table.created_at DESC');
               
                    $filteredCollection->limit(1,$offset);
               $this->getResponse()->setBody($filteredCollection->getId());
                return ;
       }
    }
}
