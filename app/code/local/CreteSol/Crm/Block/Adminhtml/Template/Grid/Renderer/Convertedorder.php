<?php
class CreteSol_Crm_Block_Adminhtml_Template_Grid_Renderer_Convertedorder extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
       $value = $row->getData('converted_order');
       
        if(!empty($value) && $value > 0)
            return "<a href='".$this->getUrl('adminhtml/sales_order/view',array('order_id'=>$row->getData('converted_order')))."'>".$value."</a>";
        else
            return 0;
    }
}