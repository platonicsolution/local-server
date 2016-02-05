<?php
 
class Psolz_Returnpolicy_Block_Adminhtml_Returnpolicy_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('returnpolicy_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('News Information'));
    }
 
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => $this->__('Item Information'),
            'title'     => $this->__('Item Information'),
            'content'   => $this->getLayout()->createBlock('returnpolicy/adminhtml_returnpolicy_edit_tab_form')->toHtml(),
        ));
       
        return parent::_beforeToHtml();
    }
}