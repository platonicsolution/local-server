<?php
class CreteSol_Survey_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
     $this->_controller = 'adminhtml_survey';
     $this->_blockGroup = 'survey';
     $this->_headerText = 'Survey Detail';
     //$this->_addButtonLabel = 'Add a contact';
     parent::__construct();
     $this->_removeButton('add');
     }
}