<?php
 
class CreteSol_Crm_Block_Adminhtml_Crm_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('crm_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('CRM Information'));
    }
 
    protected function _beforeToHtml()
    {
         $this->addTab('call_detail', array(
            'label'     => $this->__('CRM Detail'),
            'title'     => $this->__('CRM Detail'),
            'content'   => $this->getLayout()->createBlock('crm/adminhtml_crm')->setTemplate('crm/customtab.phtml')->toHtml(),
        ));
        
        $this->addTab('call_history', array(
            'label'     => $this->__('CRM Call History'),
            'title'     => $this->__('Call History'),
            'content'   => $this->getLayout()->createBlock('crm/adminhtml_crm')->setTemplate('crm/history.phtml')->toHtml(),
        ));
       
        return parent::_beforeToHtml();
    }
}