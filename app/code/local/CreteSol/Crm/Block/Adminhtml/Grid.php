<?php
class CreteSol_Crm_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
     $this->_controller = 'adminhtml_crm';
     $this->_blockGroup = 'crm';
     $this->_headerText = 'CRM Detail';
     //$this->_addButtonLabel = 'Add a contact';
     parent::__construct();
     $this->_removeButton('add');
     }
}