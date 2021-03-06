<?php
class CreteSol_Crm_Model_Observer{
    public $salesObj;
    public $postCodeArray = array();
    public $skuCodeArray = array();
    public function cronscript()
    {
        Mage::log('cron executed', null, 'crm-cron.log');
        $this->salesObj = Mage::getModel('sales/order');
        
        $this->getPostCodeCsv(Mage::helper('crm/data')->getCrmCsvFile("postcodes.csv"));
        $this->getSkuCsv(Mage::helper('crm/data')->getCrmCsvFile("sku.csv"));
        
        $array = $this->getComplatedOrder();
        
        
    }
    
    /**
     *  get completed - Full order collection 
     * 
     * */

    public function getComplatedOrder()
    {

        $order_collection = Mage::getModel('sales/order')->getCollection();
        $order_collection->addFieldToSelect('entity_id')
        ->addFieldToSelect('created_at')
            
            ->addAttributeToFilter('lead_status', array('in' => array(CreteSol_Crm_Helper_Data::STATE_PROCESSING)))
            ->setOrder('created_at', 'ASC')
                    ->setPageSize(50); //->getSelect()->__toString();
        $orderCount = $order_collection->count();

        if ($orderCount > 0)
        {
            foreach ($order_collection as $orders):
                $orderDetail = array();
                    
                $this->start = microtime(true);
                $ordersDetail[$orders->getId()] = (int)$orders->getId();

                $itemArray = array();
                $hasSample = false;
                foreach ($orders->getAllItems() as $item):

                    $productId = $item->getProductId();


                    $options = $item->getProductOptions();


                    if ($options['additional_options'][0]['label'] != 'Sample')
                    {
                        $orderDetail[$orders->getId()][$productId] = array(
                            'item_id' => (int)$productId,
                            'product_name' => trim($item->getName()),
                            'product_sku' => $item->getSku());
                        //$orderDetail[$orders->getId()][$productId]['option'] = trim($options['additional_options'][0]['label']);

                        $hasSample = false;
                    } else
                        if ($options['additional_options'][0]['label'] == 'Sample')
                        {
                            $orderDetail[$orders->getId()][$productId] = array(
                                'item_id' => (int)$productId,
                                'product_name' => trim($item->getName()),
                                'product_sku' => $item->getSku());
                            $orderDetail[$orders->getId()][$productId]['option'] = trim($options['additional_options'][0]['label']);

                            $hasSample = true;
                        }
                   
                                            if ($options["info_buyRequest"]["sample_options"] != '')
                                            {
                                                $orderDetail[$orders->getId()][$productId]['sample_option'] = trim($options["info_buyRequest"]["sample_options"]);
                                                $hasSample = true;
                    
                                            }

                endforeach;

                $orderDetail[$orders->getId()]['has_sample'] = $hasSample;

                $address = $orders->getBillingAddress();

                $orderDetail[$orders->getId()]['email'] = trim($address->getEmail());
                $orderDetail[$orders->getId()]['postcode'] = trim($address->getPostcode());
                $orderDetail[$orders->getId()]['telephone'] = trim($address->getTelephone());
                $orderDetail[$orders->getId()]['created_at'] = $orders->getCreatedAt();
                if ($hasSample)
                {
                    $this->salesObj->load($orders->getId());
                    $this->salesObj->setData('lead_status', CreteSol_Crm_Helper_Data::STATE_UNCONVERTED);
                    $this->salesObj->getResource()->saveAttribute($this->salesObj, 'lead_status');
                    $this->setOrderAttributes($orderDetail); //if sample than set the attributes
                } else
                {
                    $this->salesObj->load($orders->getId());
                    $this->salesObj->setData('lead_status', CreteSol_Crm_Helper_Data::STATE_FULL_ORDER);
                    $this->salesObj->getResource()->saveAttribute($this->salesObj,'lead_status');
                    $this->setOrderLeadStatus($orderDetail);
                }
            

            endforeach;

        }
    }
    /**
     * To set the customer order attributes 
     * @param array filled by the getComplatedOrder function 
     * */

    public function setOrderAttributes($array = array())
    {
       
        if (is_array($array))
        {
            foreach ($array as $key => $value)
            {
                if ($value['has_sample'])
                {
                    $_sizeArray = array();
                    $_skuArray = array();
                    $_nameArray = array();
                    if(is_array($value)){
                    foreach ($value as $k=> $vv)
                    {
                        if(is_array($vv)){
                            foreach($vv as $j=>$v){
                            if ($j=='sample_option')
                                $_sizeArray[] = trim($v);
                            if ($j=='product_sku')
                                $_skuArray[] = trim($v);
                            if ($j=='product_name')
                                $_nameArray[] = trim($v);
                            
                            }
                        
                         }
                    }
                   }  
                   
                    $_skuWeightingArray = array();
                    foreach ($_skuArray as $sk)
                    {
                        $_skuWeightingArray[] = $this->skuCodeArray[$sk];
                    }
                    $_maxSkuWeighting = max($_skuWeightingArray);
                    $_cntProductName = implode('<br/> ', $_nameArray);
                    $_cntProductSku = implode('<br/> ', $_skuArray);
                    $_cntSampleSize = implode('<br/> ', $_sizeArray);

                    preg_match("/^([A-Z]{1,6})/", $value['postcode'], $prefix); //get the letter of postcode first 2 or 6
                    $prefix = strtoupper($prefix[1]);
                    $_postCodeWeighting = $this->postCodeArray[$prefix];
                    $this->salesObj->load($key);
                    if($this->salesObj->getSkuWeighting() == NULL || $this->salesObj->getPostWeighting() == NULL || $this->salesObj->getProductSku() == NULL){
                    
                    $this->salesObj->setData('sample_size', $_cntSampleSize);
                    $this->salesObj->setData('sku_weighting', $_maxSkuWeighting);
                    $this->salesObj->setData('post_weighting', $_postCodeWeighting);
                    $this->salesObj->setData('product_name', $_cntProductName);
                    $this->salesObj->setData('product_sku', $_cntProductSku);

                    $this->salesObj->getResource()->saveAttribute($this->salesObj, 'sample_size');
                    $this->salesObj->getResource()->saveAttribute($this->salesObj, 'sku_weighting');
                    $this->salesObj->getResource()->saveAttribute($this->salesObj, 'post_weighting');
                    $this->salesObj->getResource()->saveAttribute($this->salesObj, 'product_name');
                    $this->salesObj->getResource()->saveAttribute($this->salesObj, 'product_sku');
                    }
                 
                }

            }


        }

    }

    /**
     *  Set the order lead status according to calculation 
     * @param array $array
     * 
     * 
     * */
    public function setOrderLeadStatus($array = array())
    {

        foreach ($array as $key => $o)
        {

            if (!$o['has_sample'])
            {

                $this->searchOrderByFilter($o['email'], $key, $o);

            }

        }
    }


    public function searchOrderByFilter($search, $exorder, $o)
    {
        
        $_convertedOrderId = 0;
        $filteredCollection = Mage::getModel('sales/order')->getCollection()->
            addFieldToSelect('entity_id')
            ->addAttributeToFilter('main_table.created_at',array('lteq'=>$o['created_at']))
            ->addAttributeToFilter('main_table.entity_id', array('nin' => array($exorder)))
            ->addFieldToFilter(array('main_table.customer_email', 'oadr.telephone'), array(array
                ('eq' => trim($search)), array(array('eq' => trim($o['telephone'])))));
                //->addFieldToFilter('lead_status',array('nin'=>CreteSol_Crm_Helper_Data::STATE_CONVERTED))
        //join with sales_flat_order_address
        $filteredCollection->getSelect()->join(array('oadr' => Mage::getSingleton('core/resource')->
                getTableName('sales_flat_order_address')),
            'main_table.entity_id = oadr.parent_id AND oadr.address_type="billing"', array('oadr.postcode',
                'oadr.telephone'));//->__toString();
                
        $totalOrder = $filteredCollection->count();

        $this->salesObj->load($exorder);
        $salesObject = Mage::getModel('sales/order');

        if ($totalOrder > 0)
        {

            foreach ($filteredCollection as $orders):
                $orderDetail = array();

                $_itemMatched = false;

                $salesObject->load($orders->getId());
                $address = $orders->getBillingAddress();

                $orderDetail[$orders->getId()]['email'] = trim($address->getEmail());
                $orderDetail[$orders->getId()]['postcode'] = trim($address->getPostcode());
                $orderDetail[$orders->getId()]['telephone'] = trim($address->getTelephone());
                $orderDetail[$orders->getId()]['created_at'] = $orders->getCreatedAt();

                foreach ($orders->getAllItems() as $item):

                    $productId = $item->getProductId();


                    $options = $item->getProductOptions();


                    if ($options['additional_options'][0]['label'] == 'Sample')
                    {
                         
                        if (array_key_exists($productId, $o))
                        {
                            $_itemMatched = true;
                            $orderDetail[$orders->getId()][$productId] = array(
                                'item_id' => (int)$productId,
                                'product_name' => trim($item->getName()),
                                'product_sku' => $item->getSku());
                            $orderDetail[$orders->getId()][$productId]['option'] = trim($options['additional_options'][0]['label']);

                        }


                    }

                endforeach;
                
                if ($_itemMatched)
                {
                    Mage::log($key . ' matched ' . $productId, null, 'Full-key-prod-search.log');


                    $salesObject->setData('lead_status', CreteSol_Crm_Helper_Data::STATE_CONVERTED);
                  
                    $salesObject->getResource()->saveAttribute($salesObject, 'lead_status');
                   
                    $salesObject->setData('rm_dup_status', 0);
                    $salesObject->getResource()->saveAttribute($salesObject, 'rm_dup_status');
                    
                    $this->salesObj->setData('lead_status',CreteSol_Crm_Helper_Data::STATE_CONVERTED_FROM);
                   

                    $this->salesObj->getResource()->saveAttribute($this->salesObj, 'lead_status');
                   
                    
                        $orderDetail[$orders->getId()]['has_sample'] = $_itemMatched;
                        $crossRef = Mage::getModel('crm/crmorders');
                                 $crossRef->load();
                                  if(!$this->checkRefOrder($orders->getId(),$exorder)){
                                    $crossRef->setData('crm_order_id',$orders->getId());
                                        $crossRef->setData('converted_order',$exorder);
                                        $crossRef->save();
                                 }
                                 
                                 unset($crossRef);
                    if($salesObject->getSkuWeighting() == NULL || $salesObject->getPostWeighting() == NULL || $salesObject->getProductSku() == NULL){
                                    $this->setOrderAttributes($orderDetail);
                     
                    }
                    

                } 
                $salesObject->setData('number_of_orders', $totalOrder);

                $salesObject->getResource()->saveAttribute($salesObject, 'number_of_orders');

            endforeach;

            
            
        }
        $salesObject->reset();
            $this->salesObj->reset();
            //$filteredCollection->reset();
            $this->total = (microtime(true) - $this->start) / 60;
            Mage::log($this->total, null, 'full-order-took-time.log');
    }
    
    public function checkRefOrder($order_id,$converted_id){
        $collection =Mage::getModel('crm/crmorders')->getCollection();
        $collection->addFieldToFilter('crm_order_id',$order_id)
        ->addFieldToFilter('converted_order',$converted_id);
        //Mage::log(  $collection->count().' order id = '.$order_id .' || '    , null, 'checkRefOderFun.log'); 
        if($collection->count() > 0)
                return true;
                else
                return false;
    }
    
    /**
     * @return array $array with index from date and to date
     * 
     * */
    public function getDateTime()
    {
        //$_configDateOptions =  Mage::getStoreConfig('crm/crm_group/date_options',Mage::app()->getStore());
        //if($_configDateOptions == '')
            $_configDateOptions = ' -10 months';
        $date = date('Y-m-d', Mage::getModel('core/date')->timestamp(time()));
        $fromDate =date('Y-m-d', strtotime('2015-01-26' )); //date('Y-m-d', strtotime('2015-03-08' . $_configDateOptions));
        $toDate = date('Y-m-d', strtotime('2015-01-28'));
        //$toDate = date('Y-m-d', Mage::getModel('core/date')->timestamp(time()));
        //echo $fromDate . ' - '. $toDate; exit;
        return array('from_date' => $fromDate, 'to_date' => $toDate);
    }

    /**
     * load post code csv and arrage it in key value like post char will be index and weighting will be value.
     * @param file path 
     * */
    public function getPostCodeCsv($file)
    {
        if(file_exists($file)){
        $objCsv = new Varien_File_Csv();
        $data = $objCsv->getData($file);
        if ($data)
        {
            // get first array as column name
            $columns = array_shift($data);

            // for remaining array items replace array keys with column names.
            foreach ($data as $k => $v)
            {
                $data[$k] = array_combine($columns, array_values($v));
            }
        }
        $data1 = array();
        foreach ($data as $k => $v)
        {

            foreach ($v as $s => $j)
            {
                if ($s == 'Postcode Area')
                    $data1[$j] = $v['Weighting'];
            }

        }
        $this->postCodeArray = array_change_key_case($data1,CASE_UPPER);
        }else{
            Mage::throwException("File not found....".$file);
        }
    }
    public function getSkuCsv($file)
    {
        if(file_exists($file)){
        
        
        $objCsv = new Varien_File_Csv();
        $data = $objCsv->getData($file);
        
        if (true)
        {
            // get first array as column name
            $columns = array_shift($data);

            // for remaining array items replace array keys with column names.
            foreach ($data as $k => $v)
            {
                $data[$k] = array_combine($columns, array_values($v));
            }
        }
        $data1 = array();
        foreach ($data as $k => $v)
        {

            foreach ($v as $s => $j)
            {
                if ($s == 'Sku')
                    $data1[$j] = $v['Weighting'];
            }

        }
        $this->skuCodeArray = $data1;
        }else{
            $this->_redirect('*/*/');
        }
    }
     /**
     * Cron Job is used for to not show repeating Customer and only show the latest order only so we have to update the
     * sales_flat_order column or attribute rm_dup_status to 1
     * 
     * */
    public function removeDupCustomerGrid(){
        $order_collection = Mage::getModel('sales/order')->getCollection();
        $order_collection->addFieldToSelect('entity_id')
        ->addFieldToSelect('created_at')
            
            ->addAttributeToFilter('lead_status', array('in' =>array(CreteSol_Crm_Helper_Data::STATE_UNCONVERTED)))
            //->addAttributeToFilter('created_at',Mage::getModel('core/date')->date('Y-m-d H:i:s'))
            ->setOrder('created_at', 'DESC');
                 
             $orderCount = $order_collection->count();
        
        
        if ($orderCount > 0)
        {
            foreach ($order_collection as $orders):
                     
                     $this->updateColumnRmDupStatus($orders->getBillingAddress()->getTelephone(),$orders->getBillingAddress()->getEmail());
                     
            endforeach;
        }
        
    }
   
   public function updateColumnRmDupStatus($tele,$email){
    $filteredCollection = Mage::getModel('sales/order')->getCollection();
    $filteredCollection->
            addFieldToSelect('entity_id')
            ->addAttributeToFilter('lead_status', array('in' => array(CreteSol_Crm_Helper_Data::STATE_UNCONVERTED)))
            ->addFieldToFilter(array('main_table.customer_email', 'oadr.telephone'), array(array
                ('eq' => trim($email)), array(array('eq' => trim($tele)))));
                $filteredCollection->getSelect()->join(array('oadr' => Mage::getSingleton('core/resource')->
                getTableName('sales_flat_order_address')),
            'main_table.entity_id = oadr.parent_id AND oadr.address_type="billing"', array('oadr.postcode',
                'oadr.telephone'))->order('main_table.created_at DESC');
                $m = Mage::getModel('sales/order');
                $totalOrder = $filteredCollection->count();
                if ($totalOrder > 0)
            {
                    $_rowGrid = 0;
                    foreach ($filteredCollection as $orders):
                               if($_rowGrid == 0){
                                 Mage::log('orderId:'.$orders->getId().' || Running.... ||',null,'remove-dup-customer.log');
                                        
                                        $m->load($orders->getId());
                                        if($m->getRmDupStatus() == 1){
                                            
                                            break;
                                        }
                                       $m->setData('rm_dup_status',1);
                                        $m->getResource()->saveAttribute($m,'rm_dup_status');
                                        
                               }else{
                                $m->load($orders->getId());
                                        $m->setData('rm_dup_status',0);
                                        $m->getResource()->saveAttribute($m,'rm_dup_status');
                               }
                           
                            $m->unsetData();
                            $_rowGrid++;
                    endforeach;
                    
            }
}
}