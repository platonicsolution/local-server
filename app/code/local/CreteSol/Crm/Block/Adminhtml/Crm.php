<?php 

class CreteSol_Crm_Block_Adminhtml_Crm extends Mage_Core_Block_Template//Mage_Adminhtml_Block_Widget_Grid_Container

{

    public $_convertUnconvertOrders,$_numberOfSampleCustomer,$_numberOfConvertedOrders,$_numberOfConvertedCustomer;

    public function __construct()

    {

       

        $this->_controller = 'adminhtml_crm';

         $this->_blockGroup = 'crm';

        $this->_headerText = $this->__('CreteSol CRM');

        $this->_saveButtonLabel = $this->__('Save');

        

        

          parent::__construct();

        

        

   }

  

    public function getCrmDetail(){

       

        return $result = Mage::getModel('sales/order')->load($this->getRequest()->getParam('order_id'));

         //Zend_Debug::dump($result);

    }

    public function getCrmOrderItems($salesOrderid=0){

        $result = Mage::getModel('sales/order_item')->getCollection()->addAttributeToSelect('name')

        ->addAttributeToSelect('sku')

        ->addAttributeToSelect('qty_ordered')

        ->addAttributeToSelect('item_id')

        ->addFieldToFilter('order_id',$salesOrderid);

       // echo $salesOrderid;

        //zend_debug::dump($result->getData());

        return $result;

    }

     public function getHistory(){

        return Mage::getModel('crm/crm')->getCollection()->addFieldToFilter('order_id',$this->getRequest()->getParam('order_id'));

        //return $this;

    }

    public function getAdminUserName($userId=0)

    {

            

        if($userId > 0){

            return Mage::getModel('admin/user')->load($userId)->getUsername();

        }else{

            return NULL;

        }

    }

    public function getSurveyDetail($incrementedId){

       

        try{

         return $survey = Mage::getModel('survey/survey')->getCollection()->addFieldToFilter('token',$incrementedId)

         ->getData();

         

         }catch(exception $e){

            echo $e->getMessage();

         }

    }

    public function getSurveyQuestions(){

        try{

            

         $questions = Mage::getModel('survey/survey')->getCollection()->addFieldToSelect(array(

        'q1','q2','q3','q4','q5','q6','q7','q8','q9'

        ))->addFieldToFilter('id',1);

        return $questions->getData();

        }catch(exception $e){

            echo $e->getMessage();

        }

    }

    public function getUserByRole($roleId)

    {

        $collection = Mage::getResourceModel('admin/user_collection');

        $collection->addFieldToSelect(array('user_id', 'username'))->addFieldToFilter('admrole.parent_id',

            $roleId);

        $collection->getSelect()->join(array('admrole' => Mage::getSingleton('core/resource')->

                getTableName('admin_role')), 'main_table.user_id = admrole.user_id');

        return $collection->getData();

        // $roles = Mage::getModel( 'admin/user' )->getCollection();



    }
    public function getDupCustomerByEmailTelephone($tele,$email,$exOrdId,$offset){
      // echo $tele .'---'.$email;
       if($tele && $email){
            $filteredCollection = Mage::getModel('sales/order')->getCollection();
             $filteredCollection->
            addFieldToSelect('entity_id')
            ->addAttributeToFilter('entity_id',array('nin'=>array($exOrdId)))
            ->addAttributeToFilter('lead_status', array('in' => array(CreteSol_Crm_Helper_Data::STATE_UNCONVERTED)))
            ->addFieldToFilter(array('main_table.customer_email', 'oadr.telephone'), array(array
                ('eq' => trim($email)), array(array('eq' => trim($tele)))));
              $filteredCollection->getSelect()->join(array('oadr' => Mage::getSingleton('core/resource')->
                getTableName('sales_flat_order_address')),
            'main_table.entity_id = oadr.parent_id AND oadr.address_type="billing"', array('oadr.postcode',
                'oadr.telephone'))->order('main_table.created_at DESC');
                if($offset > 0){
                    //$filteredCollection->limit(1,$offset);
                }
                return $filteredCollection;
       }
    }
    public function getCrmReport()

    {

        if ($this->getRequest()->getPost())

        {

            zend_debug::dump($this->getRequest()->getPost());

            //$this->getConvertedOrders();

            $this->getSampleOrders();

            $this->getOrderCustomers();

            $this->getMaxConvertingProduct();

            return $this;



        }

    }

    // public function getConvertedOrders(){

    //            $this->getSearchOrderByFilter('converted');

    //        }

   // public function getSampleOrders()

//    {

//        $this->getSearchOrderByFilter('sample');

//        return $this;

//    }

    /**

     * search order total on a given filter we will get converted and unconverted order from this one function.

     * @return type object $this we have set the class varible.

     * */



    public function getSampleOrders()

    {

                $objOrderCollection = Mage::getResourceModel('sales/order_collection')

                ->addFieldToSelect('entity_id')

                ->addExpressionFieldToSelect('total', 'COUNT(*)', 'total');

            

                $objOrderCollection->addFieldToFilter('lead_status', array('in' => array('converted',

                            'unconverted')));

            



            if ($this->getRequest()->getPost('date_range'))

            {

                $dateRangeArrayOrstring = $this->getDateRangeOrCutomRange();

                if (is_array($dateRangeArrayOrstring))

                {

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array('from' => $dateRangeArrayOrstring['from_date'],

                            'to' => $dateRangeArrayOrstring['to_date']));

                } else

                {

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array('eq' => $dateRangeArrayOrstring));

                }



            } else

                if ($this->getRequest()->getPost('from_date') !='' && $this->getRequest()->getPost('to_date') !='')

                {

                  

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array(

                    'from' =>  Mage::getModel('core/date')->date('Y-m-d', strtotime($this->getRequest()->getPost('from_date'))),

                     'to' => Mage::getModel('core/date')->date('Y-m-d', strtotime($this->getRequest()->getPost('to_date')))));

                }

                if($this->getRequest()->getPost('sample_type') !=''){

                    if($this->getRequest()->getPost('sample_type') == 'paid'){

                        $objOrderCollection->addFieldToFilter('base_grand_total',array('gt'=>0));

                    }else{

                        $objOrderCollection->addFieldToFilter('base_grand_total',array('eq'=>0));

                    }

                }

                

                    if($this->getRequest()->getPost('user')){

                        $objOrderCollection->addFieldToFilter('advisor',trim($this->getRequest()->getPost('user')));

                    }

                if($this->getRequest()->getPost('call_status')){

                        $objOrderCollection->addFieldToFilter('call_status',trim($this->getRequest()->getPost('call_status')));

                    }





            $objOrderCollection->getSelect()->group('lead_status');//->__toString(); exit;

            return  $objOrderCollection->getData();

            

        

    }

    

    public function getOrderCustomers($param){

        $objOrderCollection = Mage::getModel('sales/order')->getCollection()

            ->addFieldToSelect('entity_id')

            ->addExpressionFieldToSelect('total', 'COUNT(customer_email)', 'total');

            

               if($param == 'unconverted')

                        $objOrderCollection->addFieldToFilter('lead_status', array('in' => array('unconverted')));

                else

                        $objOrderCollection->addFieldToFilter('lead_status', array('in' => array('converted')));

               



            if ($this->getRequest()->getPost('date_range'))

            {

                $dateRangeArrayOrstring = $this->getDateRangeOrCutomRange();

                if (is_array($dateRangeArrayOrstring))

                {

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array('from' => $dateRangeArrayOrstring['from_date'],

                            'to' => $dateRangeArrayOrstring['to_date']));

                } else

                {

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array('eq' => $dateRangeArrayOrstring));

                }



            } else

                if ($this->getRequest()->getPost('from_date') != '' && $this->getRequest()->getPost('to_date') != '')

                {

                    

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array(

                    'from' =>  Mage::getModel('core/date')->date('Y-m-d', strtotime($this->getRequest()->getPost('from_date'))),

                     'to' => Mage::getModel('core/date')->date('Y-m-d', strtotime($this->getRequest()->getPost('to_date')))));

                }

                if($this->getRequest()->getPost('sample_type') !=''){

                    if($this->getRequest()->getPost('sample_type') == 'paid'){

                        $objOrderCollection->addFieldToFilter('base_grand_total',array('gt'=>0));

                    }else{

                        $objOrderCollection->addFieldToFilter('base_grand_total',array('eq'=>0));

                    }

                }

                if($this->getRequest()->getPost('user')){

                        $objOrderCollection->addFieldToFilter('advisor',trim($this->getRequest()->getPost('user')));

                    }

                if($this->getRequest()->getPost('call_status')){

                        $objOrderCollection->addFieldToFilter('call_status',trim($this->getRequest()->getPost('call_status')));

                    }

                                

            //join table with sales_flat_order_address

            

            

              $objOrderCollection->addFieldToFilter(array('main_table.customer_email','oadr.email'),array(array('notnull'=>true)

              ,array(array('notnull'=>true))));

          $objOrderCollection->getSelect()->joinLeft(array('oadr' =>

                Mage::getSingleton('core/resource')->getTableName('sales_flat_order_address')),

            'main_table.entity_id = oadr.parent_id AND oadr.address_type="billing"',array(''))->group('main_table.customer_email');//->__toString();

              $a = array();

              foreach($objOrderCollection->getData()as $d):

                        $a[] = $d['total'];

                        endforeach;

              return array_sum($a);

                        //Zend_Debug::dump(array_sum($a));exit;

 

             //return $objOrderCollection->getData();

           

            

    }

    

    function getMaxOrMinConvertingProduct($orderBy){

        

        $objOrderCollection = Mage::getModel('sales/order')->getCollection()

            ->addFieldToSelect('entity_id');

            //->addFieldToSelect('subtotal');

            

            $objOrderCollection->addExpressionFieldToSelect('totalc', 'count(items.sku)', 'totalc');

            if ($this->getRequest()->getPost('date_range'))

            {

                $dateRangeArrayOrstring = $this->getDateRangeOrCutomRange();

                if (is_array($dateRangeArrayOrstring))

                {

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array('from' => $dateRangeArrayOrstring['from_date'],

                            'to' => $dateRangeArrayOrstring['to_date']));

                } else

                {

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array('eq' => $dateRangeArrayOrstring));

                }



            } else

                if ($this->getRequest()->getPost('from_date') && $this->getRequest()->getPost('to_date'))

                {

                    

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array(

                    'from' =>  Mage::getModel('core/date')->date('Y-m-d', strtotime($this->getRequest()->getPost('from_date'))),

                     'to' => Mage::getModel('core/date')->date('Y-m-d', strtotime($this->getRequest()->getPost('to_date')))));

                }

                    $objOrderCollection->addFieldToFilter('lead_status', array('in' => array('converted_from')));

                    $objOrderCollection->addFieldToFilter('state',array('in'=>array('complete')));

                    

                    $objOrderCollection->getSelect()

                    ->join(array('items' => Mage::getSingleton('core/resource')->

                getTableName('sales_flat_order_item')),

            'main_table.entity_id = items.order_id', array('items.sku','items.name'))              

                    ->group('items.sku')

                    ->order("totalc {$orderBy}")

                        

                     ->limit(($this->getRequest()->getPost('product_limit')) ?$this->getRequest()->getPost('product_limit') : 10);

                    //->__toString();

                   //->getSelect()

                    return $objOrderCollection->getData();

                    //zend_debug::dump($objOrderCollection->getData());

        

    }

    

    public function getMaxOrMinSentSample($sku){

        $objOrderCollection = Mage::getModel('sales/order')->getCollection()

            ->addFieldToSelect('entity_id')

            //->addFieldToSelect('subtotal');

            ;

            $objOrderCollection->addExpressionFieldToSelect('totalc', 'count(entity_id)', 'totalc');

            if ($this->getRequest()->getPost('date_range'))

            {

                $dateRangeArrayOrstring = $this->getDateRangeOrCutomRange();

                if (is_array($dateRangeArrayOrstring))

                {

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array('from' => $dateRangeArrayOrstring['from_date'],

                            'to' => $dateRangeArrayOrstring['to_date']));

                } else

                {

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array('eq' => $dateRangeArrayOrstring));

                }



            } else

                if ($this->getRequest()->getPost('from_date') && $this->getRequest()->getPost('to_date'))

                {

                    

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array(

                    'from' =>  Mage::getModel('core/date')->date('Y-m-d', strtotime($this->getRequest()->getPost('from_date'))),

                     'to' => Mage::getModel('core/date')->date('Y-m-d', strtotime($this->getRequest()->getPost('to_date')))));

                }

                    $objOrderCollection->addFieldToFilter('lead_status', array('in' => array('converted','unconverted')));

                    $objOrderCollection->addFieldToFilter('state',array('in'=>array('complete')));

                    

                    $objOrderCollection->addFieldToFilter('items.sku', $sku);

                    $objOrderCollection->getSelect()

                    ->join(array('items' => Mage::getSingleton('core/resource')->

                getTableName('sales_flat_order_item')),

            'main_table.entity_id = items.order_id', array('items.sku','items.name'))

                    

                    ->group('items.sku');

                    //->order('totalc DESC');

                     //->limit(10);

                    //->__toString();

                   //->getSelect()

                   //echo 'yes';exit;

                   //zend_debug::dump($objOrderCollection->getData());

                    return $objOrderCollection->getData();

                    

    }

    

    function getRevenuPerSample(){

        $objOrderCollection = Mage::getModel('sales/order')->getCollection()

            ->addFieldToSelect('entity_id');

            //->addFieldToSelect('subtotal');

            

            $objOrderCollection->addExpressionFieldToSelect('rowtotal', 'SUM(items.row_total)', 'rowtotal');

            $objOrderCollection->addExpressionFieldToSelect('refunded', 'SUM(items.amount_refunded)', 'refunded');

             $objOrderCollection->addExpressionFieldToSelect('taxrefunded', 'SUM(items.base_tax_refunded)', 'taxrefunded');

            $objOrderCollection->addExpressionFieldToSelect('totalc', 'count( `items`.`sku`)', 'totalc');

             

            if ($this->getRequest()->getPost('date_range'))

            {

                $dateRangeArrayOrstring = $this->getDateRangeOrCutomRange();

                if (is_array($dateRangeArrayOrstring))

                {

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array('from' => $dateRangeArrayOrstring['from_date'],

                            'to' => $dateRangeArrayOrstring['to_date']));

                } else

                {

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array('eq' => $dateRangeArrayOrstring));

                }



            } else

                if ($this->getRequest()->getPost('from_date') && $this->getRequest()->getPost('to_date'))

                {

                    

                    $objOrderCollection->addAttributeToFilter('main_table.created_at', array(

                    'from' =>  Mage::getModel('core/date')->date('Y-m-d', strtotime($this->getRequest()->getPost('from_date'))),

                     'to' => Mage::getModel('core/date')->date('Y-m-d', strtotime($this->getRequest()->getPost('to_date')))));

                }

                    $objOrderCollection->addFieldToFilter('lead_status', array('in' => array('converted_from')));

                    $objOrderCollection->addFieldToFilter('state',array('in'=>array('complete')));

                    

                  $objOrderCollection->getSelect()

                    ->join(array('items' => Mage::getSingleton('core/resource')->

                getTableName('sales_flat_order_item')),

            'main_table.entity_id = items.order_id', array('items.sku','items.name'))              

                    ->group('items.sku')

                    ->order("rowtotal DESC")

                        

                     ->limit(($this->getRequest()->getPost('product_limit')) ?$this->getRequest()->getPost('product_limit') : 10 );

                    //->__toString();

                   //->getSelect()

                    return $objOrderCollection->getData();

                    //zend_debug::dump($objOrderCollection->getData());

    }

    

    /**

     * Set Date Range Function for dashboard

     *@return type string or array depend on date.

     * */

    public function getDateRangeOrCutomRange()

    {

        if ($this->getRequest()->getPost('date_range'))

        {

            $dateRageArray = array();

            switch ($this->getRequest()->getPost('date_range'))

            {



                case 'yesterday':

                    return date('Y-m-d', strtotime('-1 day'));

                    break;

                case 'current_week':

                    return array('from_date' => Mage::getModel('core/date')->date('Y-m-d', strtotime('this week')), '

                    to_date' => date('Y-m-d', Mage::getModel('core/date')->date

                            ('Y-m-d')));

                    break;

                case 'last_week':

                    return array('from_date' => Mage::getModel('core/date')->date('Y-m-d', strtotime('last week')), 'to_date' =>

                            Mage::getModel('core/date')->date('Y-m-d', strtotime('this week -1 day')));



                    break;

                case 'current_month':

                    return array('from_date' => Mage::getModel('core/date')->date('Y-m-d', strtotime('first day of this month')),

                            'to_date' => date('Y-m-d', Mage::getModel('core/date')->date('Y-m-d')));

                    break;

                case 'last_month':

                    return array('from_date' => Mage::getModel('core/date')->date('Y-m-d', strtotime('first day of -1 month')),

                            'to_date' => Mage::getModel('core/date')->date('Y-m-d', strtotime('last day of -1 month')));

                    break;

                default: //today

                    return Mage::getModel('core/date')->date('Y-m-d');

                    break;



            }

        }

    }
    
    
    /**
     *  CRM REPORTS 
     * 
     **/
     
     public function getSampleRptDetail(){
         if($this->getRequest()->getPost()){
        
       
        $objOrderCollection = Mage::getResourceModel('sales/order_collection')->
            addFieldToSelect('*');


        $objOrderCollection->addFieldToFilter('lead_status', array('in' => array('converted',
                    'unconverted')));


        if ($this->getRequest()->getPost('date_range'))
        {

            $dateRangeArrayOrstring = $this->getDateRangeOrCutomRange();

            if (is_array($dateRangeArrayOrstring))
            {

                $objOrderCollection->addAttributeToFilter('main_table.created_at', array('from' =>
                        $dateRangeArrayOrstring['from_date'], 'to' => $dateRangeArrayOrstring['to_date']));

            } else
            {

                $objOrderCollection->addAttributeToFilter('main_table.created_at', array('eq' =>
                        $dateRangeArrayOrstring));

            }


        } else
            if ($this->getRequest()->getPost('from_date') != '' && $this->getRequest()->
                getPost('to_date') != '')
            {


                $objOrderCollection->addAttributeToFilter('main_table.created_at', array('from' =>
                        Mage::getModel('core/date')->date('Y-m-d', strtotime($this->getRequest()->
                        getPost('from_date'))), 'to' => Mage::getModel('core/date')->date('Y-m-d',
                        strtotime($this->getRequest()->getPost('to_date')))));

            }

        if ($this->getRequest()->getPost('sample_type') != '')
        {

            if ($this->getRequest()->getPost('sample_type') == 'paid')
            {

                $objOrderCollection->addFieldToFilter('base_grand_total', array('gt' => 0));

            } else
            {

                $objOrderCollection->addFieldToFilter('base_grand_total', array('eq' => 0));

            }

        }


        if ($this->getRequest()->getPost('user'))
        {

            $objOrderCollection->addFieldToFilter('advisor', trim($this->getRequest()->
                getPost('user')));

        }

        if ($this->getRequest()->getPost('call_status'))
        {

            $objOrderCollection->addFieldToFilter('call_status', trim($this->getRequest()->
                getPost('call_status')));

        }

            zend_debug::dump($objOrderCollection->getdata());
         return $objOrderCollection;//->getData();
      }
     }
    
        /**
      * get product stock level
      * @param $_productId product id
      * @return int available qty
      * */
     public function getStockLevel($_productId){
            if($_productId){
                
                return (int)Mage::getModel('catalog/product')->load($_productId)->getStockItem()->getQty();
            }else{
                return 0;
            }
     }
     /**
      * get product attribute Height and width
      * @param $_productId product id
      * @return array height and width
      * */
     public function getProductSizeAttribute($_productId){
        if($_productId){
            
            $_product = Mage::getModel('catalog/product')->load($_productId);
            if($_product){
               return array(
                    'height'=>$_product->getHeight(),
                    'width'=>$_product->getWidth(),
                    'price'=>$_product->getPrice() );
                 
            }else{
                return null;
            }
           
        }
        else{
            return null;
        }
     }
      /**
     * Notifiy sales person when call back date is current date
     * @return int
     * 
     * */
     public function callBackNotification(){
        $collection = Mage::getModel('sales/order')->getCollection();
        $collection->addFieldToSelect('entity_id');
        $collection->addFieldToSelect('call_back_date');
        $collection->addFieldToFilter('call_back_date',date('Y-m-d'));
        return $collection->count();
     }
}