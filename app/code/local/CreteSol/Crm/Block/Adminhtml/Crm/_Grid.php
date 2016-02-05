<?php
 
class CreteSol_Crm_Block_Adminhtml_Crm_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('crmGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
 protected function _getCollectionClass()
    {
        return 'sales/order_collection';
    }

    protected function _prepareCollection()
    {
        $collection =Mage::getModel('sales/order')->getCollection()
        ->addAttributeToFilter('state', array('in' => array(Mage_Sales_Model_Order::
                    STATE_COMPLETE)))->addAttributeToFilter('status', array('in' => array('complete_samples','complete')))
                    ->addFieldToFilter('lead_status',array(
                      array(CreteSol_Crm_Helper_Data::STATE_UNCONVERTED),
                      array( 'eq'=>CreteSol_Crm_Helper_Data::STATE_CONVERTED)
                    )
                  );
        //$collection->addAttributeToFilter(array(
//				array(
//					'attribute' => 'lead_status',
//					'eq' => CreteSol_Crm_Helper_Data::STATE_UNCONVERTED),
//				array(
//					'attribute' => 'lead_status',
//					'eq' => CreteSol_Crm_Helper_Data::STATE_CONVERTED)
//			)); 
//            $collection->printLogQuery(true);
        //$collection =Mage::getResourceModel($this->_getCollectionClass());
       //echo $collection->getSelect()->__toString();exit;
       
        $this->setCollection($collection);
        //Zend_Debug::dump($collection->setPageSize(1,2)->getData());exit;
        return parent::_prepareCollection();
    }

 
 
  
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'=> $this->__('Entity ID #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'entity_id',
        ));
        $this->addColumn('real_order_id', array(
            'header'=> Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
        ));
        //if (!Mage::app()->isSingleStoreMode()) {
//            $this->addColumn('store_id', array(
//                'header'    => $this->__('Purchased From (Store)'),
//                'index'     => 'store_id',
//                'type'      => 'store',
//                'store_view'=> true,
//                'display_deleted' => true,
//            ));
//        }

        $this->addColumn('created_at', array(
            'header' => $this->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'format' => Mage::app()->getLocale()
                ->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM),
            'width' => '100px',
        ));

        $this->addColumn('customer_firstname', array(
            'header' => $this->__('First Name'),
            'index' => 'customer_firstname',
        ));

        //$this->addColumn('customer_lastname', array(
//            'header' => $this->__('Last Name'),
//            'index' => 'customer_lastname',
//        ));

        //$this->addColumn('base_grand_total', array(
//            'header' => $this->__('G.T. (Base)'),
//            'index' => 'base_grand_total',
//            'type'  => 'currency',
//            'currency' => 'base_currency_code',
//        ));

        $this->addColumn('grand_total', array(
            'header' => $this->__('Price'),
            'index' => 'grand_total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));

        $this->addColumn('status', array(
            'header' => $this->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));
         $this->addColumn('sample_size', array(
            'header' => $this->__('Sample Size'),
            'index' => 'sample_size',
            'type'  => 'text'
            //'options' => array(' Full Size ',' Cut Size ')
        ));
        $this->addColumn('sku_weighting', array(
            'header' => $this->__('SKU Weighting'),
            'index' => 'sku_weighting',
            'type'  => 'range'
        ));
        $this->addColumn('post_weighting', array(
            'header' => $this->__('PostCode Weighting'),
            'index' => 'post_weighting',
            'type'  => 'range',
        ));
         //$this->addColumn('paid', array(
//            'header' => $this->__('Paid'),
//            'index' => 'paid',
//            'type'  => 'options',
//            'options' => array('Paid','Free')
//        ));
         $this->addColumn('product_sku', array(
            'header' => $this->__('Product Sku'),
            'index' => 'product_sku',
            'type'  => 'text',
        ));
        $this->addColumn('product_name', array(
            'header' => $this->__('Product Name'),
            'index' => 'product_name',
            'type'  => 'text',
        ));
        $this->addColumn('call_status', array(
            'header' => $this->__('Call Status'),
            'index' => 'call_status',
            'type'  => 'options',
            //'options' => array('answered','unanswered','send_email','call_back')
            'options' =>array('answered'=>'Answered','unanswered'=>'Unanswered','send_email'=>'Send Email','call_back'=>'Call Back')
       
        ));
       //
        $this->addColumn('number_of_orders', array(
            'header' => $this->__('# Orders'),
            'index' => 'number_of_orders',
            'type'  => 'int',
            'filter'    => false,
        ));
        $this->addColumn('converted_order', array(
            'header' => $this->__('Converted Order'),
            'index' => 'converted_orders',
            'type'      => 'action',
            'getter'     => 'getConvertedOrder',
            //'actions'   => array(
//                        array(
//                            'caption' => $this->__('View'),
//                            'url'     => array('base'=>'adminhtml/sales_order/view'),
//                            'field'   => 'order_id',
//                            'data-column' => 'action',
//                        )
//                    ),
                    'filter'    => false,
                    'renderer'  => 'CreteSol_Crm_Block_Adminhtml_Template_Grid_Renderer_Convertedorder',
                    'sortable'  => false,
                    'index'     => 'stores',
                    'is_system' => true,
        ));
        $this->addColumn('lead_status', array(
            'header' => $this->__('Lead Status'),
            'index' => 'lead_status',
            'type'  => 'options',
            'options' => array(CreteSol_Crm_Helper_Data::STATE_CONVERTED=>'Converted',CreteSol_Crm_Helper_Data::STATE_UNCONVERTED=>'Unconverted')));
              //CreteSol_Crm_Helper_Data::STATE_PROCESSING=>'Processing',,CreteSol_Crm_Helper_Data::STATE_ABUSE=>'Abuse'),
        
            $this->addColumn('action',
                array(
                    'header'    => $this->__('Action'),
                    'width'     => '50px',
                    'type'      => 'action',
                    'getter'     => 'getId',
                    'actions'   => array(
                        array(
                            'caption' => $this->__('View'),
                            'url'     => array('base'=>'*/*/edit'),
                            'field'   => 'order_id',
                            'data-column' => 'action',
                        )
                    ),
                    'filter'    => false,
                    'sortable'  => false,
                    'index'     => 'stores',
                    'is_system' => true,
            ));
        
       
 
        return parent::_prepareColumns();
    }
 
 
    public function getRowUrl($row)
    {
        //return $this->getUrl('*/*/edit', array('entity_id' => $row->getId()));
    }
 
    public function getGridUrl()
    {
      return $this->getUrl('*/*/grid', array('_current'=>true));
    }
 
 
}