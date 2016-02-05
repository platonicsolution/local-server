<?php 
class Dev14_CustomerGallery_Block_Adminhtml_Gallery_Helper_Image extends Varien_Data_Form_Element_Image{
    //make your renderer allow "multiple" attribute
    public function getHtmlAttributes(){
        return array_merge(parent::getHtmlAttributes(), array('multiple'));
    }
}