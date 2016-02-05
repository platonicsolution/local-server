<?php
class Dev14_CustomerGallery_Block_Adminhtml_Gallery_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
  {
     public function __construct()
     {
          parent::__construct();
          $this->setId('galleryGrid');
          $this->setDestElementId('edit_form');
          $this->setTitle('Edit Information');
      }
      protected function _beforeToHtml()
      {
          $this->addTab('form_section', array(
                   'label' => 'Gallery Information',
                   'title' => 'Gallery Information',
                   'content' => $this->getLayout()
     ->createBlock('customergallery/adminhtml_gallery_edit_tab_form')->toHtml()));

         /* $this->addTab('form_section2', array(
                   'label' => 'Images',
                   'title' => 'Contact Information2',
                   'content' => $this->getLayout()
     ->createBlock('customergallery/adminhtml_gallery_edit_tab_gallery')->toHtml()));*/
      try
      {
        $this->addTab('images_section', array(
              'label'     => Mage::helper('core')->__('Gallery Images'),
              'title'     => Mage::helper('core')->__('Gallery Images'),
              'content'   => $this->getLayout()->createBlock('customergallery/adminhtml_gallery_edit_tab_gallery')->toHtml(),
      ));

      }
      catch(Exception $e)
      {
        echo $e->getMessage();
      }
          
         return parent::_beforeToHtml();
    }
}