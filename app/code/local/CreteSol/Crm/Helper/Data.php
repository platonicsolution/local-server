<?php
class CreteSol_Crm_Helper_Data extends Mage_Core_Helper_Abstract
{
    const STATE_PROCESSING = 'processing';
    const STATE_CONVERTED = 'converted';
    const STATE_UNCONVERTED = 'unconverted';
    const STATE_CONVERTED_FROM = 'converted_from';
    const STATE_FULL_ORDER ='full_order';
    const STATE_ABUSE = 'abuse';  
    
    public function getCrmCsvFile($file){
        
        if(file_exists(Mage::getBaseDir().DS."crm".DS."admin".DS.$file)){
            
       
            return Mage::getBaseDir().DS."crm".DS."admin".DS.$file;
             }
            else{
             Mage::getSingleton('adminhtml/session')->addError('File not found.. '.$file);
             
             }
    }
   
    
}