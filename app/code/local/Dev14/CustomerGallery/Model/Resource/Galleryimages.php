<?php

class Dev14_CustomerGallery_Model_Resource_Galleryimages extends Mage_Core_Model_Resource_Db_Abstract{
    protected function _construct()
    {
        $this->_init('customergallery/galleryimages', 'images_id');
    }


    
}
?>