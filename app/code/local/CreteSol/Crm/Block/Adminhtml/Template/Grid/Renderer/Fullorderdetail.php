<?php

class CreteSol_Crm_Block_Adminhtml_Template_Grid_Renderer_Fullorderdetail extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action

{

    public function render(Varien_Object $row)

    {

       $value = $row->getData('entity_id');

       

        if(!empty($value) && $value > 0)
            {
                $m = Mage::getModel('crm/crmorders')->load($value,'crm_order_id');
                //zend_debug::dump($m->getData());
                if($m){
                     if($m->getData('converted_order'))
                            return "<a href='javascript:void(0);' class='crm-full-order-column' data-column='".$m->getData('converted_order')."' onclick='getFullOrder(".$m->getData('converted_order').");'> + </a>
                                <div id='crm-full-order-column-div".$m->getData('converted_order')."' class='cmr-grid-column-popup'></div>
                            ";
                        else
                            return '-';//$value;
                }else{
                    return $value;
                }

           }
        else {

            return '-';
        }
    }

}