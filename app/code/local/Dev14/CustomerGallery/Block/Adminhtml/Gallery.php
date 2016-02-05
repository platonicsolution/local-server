<?php
class Dev14_CustomerGallery_Block_Adminhtml_Gallery extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
     //where is the controller
     $this->_controller = 'adminhtml_gallery';
     $this->_blockGroup = 'customergallery';
     //text in the admin header
     $this->_headerText = 'Customer Gallery';
     //value of the add button
      $this->_addButtonLabel = 'Restore';
     $this->_addButtonLabel = 'Add Gallery';

     parent::__construct();
     }


     //protected function _prepareLayout()
//{
//    $this->_addButton('add_new', array(
//        'label'   => Mage::helper('catalog')->__('Restore'),
//        'onclick' => "setLocation('{$this->getUrl('*/*/restore')}')",
//        'class'   => 'add'
//    ));
//
//
// 
//    $this->setChild('grid', $this->getLayout()->createBlock('weblog/adminhtml_gallery_grid', 'gallery.grid'));
//    return parent::_prepareLayout();
//}

}