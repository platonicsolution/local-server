<?php
class CreteSol_Crm_Block_Adminhtml_Template_Grid_Renderer_Isfullsize extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
       $value = $row->getData('sample_size');
       //echo $value;exit;
        if($value)
        {
            
            //$array = preg_match('/\bFull Size\b/i',$value,$output);
            
            if(preg_match('/\bFull Size\b/i',$value))
           return 'Yes';
           else
            return 'No';
        }            
        else{
           return '-'; 
        }
            
    }
}