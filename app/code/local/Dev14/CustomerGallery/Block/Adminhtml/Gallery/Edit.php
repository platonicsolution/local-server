<?php
class Dev14_CustomerGallery_Block_Adminhtml_Gallery_Edit extends  Mage_Adminhtml_Block_Widget_Form_Container{
   public function __construct()
   {
    
        parent::__construct();
        $this->_objectId = 'id';
        //vwe assign the same blockGroup as the Grid Container
        $this->_blockGroup = 'customergallery';
        //and the same controller
        $this->_controller = 'adminhtml_gallery';
        //define the label for the save and delete button
        // $this->_updateButton('add', 'label','Add New Image');
        $this->_updateButton('save', 'label','Save Gallery');
        $this->_updateButton('delete', 'label', 'Delete Gallery');
        
    }
       /* Here, we're looking if we have transmitted a form object,
          to update the good text in the header of the page (edit or add) */
    public function getHeaderText()
    {

    //      $this->_addButton('add_new', array(
    //     'label'   => 'Add New Image',
    //     'onclick' => "setLocation('{$this->getUrl('*/*/newimage',array("id"=>$this->getRequest()->getParam("id")))}')",
    //     'class'   => 'add'
    // ));

        if( Mage::registry('gallery_data'))
         {
              return 'Edit Gallery'.$this->htmlEscape().'<br />';
         }
         else
         {
             return 'Add a Gallery';
         }
    }
}