<?php
class Dev14_CustomerGallery_Block_Adminhtml_Gallery_Edit_Tab_Form extends  Mage_Adminhtml_Block_Widget_Form
{
      protected function _prepareForm()
    {


         $form = new Varien_Data_Form();
      
      
       
       $this->setForm($form);
       $fieldset = $form->addFieldset('gallery_form',
                                       array('legend'=>'Gallery Information'));
        $fieldset->addField('name', 'text',
                       array(
                          'label' => 'name',
                          'class' => 'required-entry',
                          'required' => true,
                           'name' => 'name',
                    ));
$fieldset->addField('fileinputname', 'image', array(
          'label'     => 'Thumbnail',
          'required'  => false,
          'name'      => 'fileinputname',
)); 

$fieldset->addField('category', 'select', array(
          'label'     => 'category',
          'required'  => false,
          'name'      => 'category',
          'options' => array(
            '0' => 'Customer Gallery',
            '1' => 'Photo Gallery',
        ),
           // 'values'    => array( array('value'=>'0','label'=>'Customer Gallery'),
           //                  array('value'=>'1','label'=>'Photo Gallery'),
           //             ),
)); 
      



 if ( Mage::registry('gallery_data') )
 {
    $a = Mage::registry('gallery_data');

    $form->setValues($arrayName = array('name' => $a[0]['name'],'fileinputname' => $a[0]['thumbnail'],'category' => $a[0]['category']));
   
  }

      return parent::_prepareForm();
   }


    public function filter($value)
    {
        return number_format($value, 3);
    }
}