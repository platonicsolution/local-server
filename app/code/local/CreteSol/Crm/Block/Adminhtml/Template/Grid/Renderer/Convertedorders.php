<?php

class CreteSol_Crm_Block_Adminhtml_Template_Grid_Renderer_Convertedorders extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action

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
                            return "<a href='".$this->getUrl('adminhtml/sales_order/view',array('order_id'=>$m->getData('converted_order')))."'>".$m->getData('converted_order')."</a>";
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