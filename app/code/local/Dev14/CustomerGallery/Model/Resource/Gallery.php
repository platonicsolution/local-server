<?php

class Dev14_CustomerGallery_Model_Resource_Gallery extends Mage_Core_Model_Resource_Db_Abstract{
    protected function _construct()
    {
        $this->_init('customergallery/gallery', 'gallery_id');
    }


    
}
?>