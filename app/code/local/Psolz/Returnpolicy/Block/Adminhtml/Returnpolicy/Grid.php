<?php
 
class Psolz_Returnpolicy_Block_Adminhtml_Returnpolicy_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('returnpolicyGrid');
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
        $collection =Mage::getModel('sales/order')->getCollection();
        //$collection->addAttributeToFilter('lead_status', '0'); 
        //$collection =Mage::getResourceModel($this->_getCollectionClass());
       //echo $collection->getSelect()->__toString();exit;
        $this->setCollection($collection);
        //Zend_Debug::dump($collection->getData());exit;
        return parent::_prepareCollection();
    }

 
 
    //protected function _prepareCollection()
//    {
//        //$collection = Mage::getModel('returnpolicy/returnpolicy')->getCollection();
//        $collection = Mage::getModel('sales/order')->getCollection();
//        //Zend_Debug::dump($collection->getData())->debug();
//        $this->setCollection($collection);
//        return parent::_prepareCollection();
//    }
    protected function _prepareColumns()
    {
        $this->addColumn('real_order_id', array(
            'header'=> Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('sales')->__('Purchased From (Store)'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view'=> true,
                'display_deleted' => true,
            ));
        }

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        $this->addColumn('customer_firstname', array(
            'header' => Mage::helper('sales')->__('First Name'),
            'index' => 'customer_firstname',
        ));

        $this->addColumn('customer_lastname', array(
            'header' => Mage::helper('sales')->__('Last Name'),
            'index' => 'customer_lastname',
        ));

        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));

        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));
        $this->addColumn('lead_status', array(
            'header' => $this->__('Lead Status'),
            'index' => 'lead_status',
            'type'  => 'options',
            'width' => '70px',
            'options' => array('0'=>'Processing','1'=>'Converted','2'=>'Convert','3'=>'Abuse'),
        ));

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
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
        }
       
 
        return parent::_prepareColumns();
    }
 
   // protected function _prepareColumns()
//    {
//        $this->addColumn('id', array(
//            'header'    => $this->__('ID'),
//            'align'     =>'right',
//            'width'     => '50px',
//            'index'     => 'id',
//        ));
// 
//        $this->addColumn('policy_name', array(
//            'header'    => $this->__('Policy Name'),
//            'align'     =>'left',
//            'index'     => 'policy_name',
//        ));
// 
//        
//        $this->addColumn('policy_period', array(
//            'header'    =>$this->__('Policy Period'),
//            'width'     => '150px',
//            'index'     => 'policy_period',
//        ));
//        
// 
//        $this->addColumn('created_time', array(
//            'header'    => $this->__('Creation Time'),
//            'align'     => 'left',
//            'width'     => '120px',
//            'type'      => 'date',
//            'default'   => '--',
//            'index'     => 'created_time',
//        ));
// 
//        $this->addColumn('update_time', array(
//            'header'    => $this->__('Update Time'),
//            'align'     => 'left',
//            'width'     => '120px',
//            'type'      => 'date',
//            'default'   => '--',
//            'index'     => 'update_time',
//        ));   
// 
// 
//        $this->addColumn('status', array(
// 
//            'header'    => $this->__('Status'),
//            'align'     => 'left',
//            'width'     => '80px',
//            'index'     => 'status',
//            'type'      => 'options',
//            'options'   => array(
//                1 => 'Active',
//                0 => 'Inactive',
//            ),
//        ));
// 
//        return parent::_prepareColumns();
//    }
 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('entity_id' => $row->getId()));
    }
 
    public function getGridUrl()
    {
      return $this->getUrl('*/*/grid', array('_current'=>true));
    }
 
 
}