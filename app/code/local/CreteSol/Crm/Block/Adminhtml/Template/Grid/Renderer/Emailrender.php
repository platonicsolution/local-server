<?php

class CreteSol_Crm_Block_Adminhtml_Template_Grid_Renderer_Emailrender extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action

{

    public function render(Varien_Object $row)

    {

       $value = $row->getData('customer_email');

       

        if(!empty($value) )
            {
                $strA = explode('@',$value);
                return $strA[0].'@<br/>'.$strA[1]; 

           }
        else {

            return '-';
        }
    }

}