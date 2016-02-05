<?php

class Infortis_Ultimo_Block_Product_List_Featured extends Mage_Catalog_Block_Product_List
{
	protected $_collectionCount = NULL;
	protected $_productCollectionId = NULL;
	protected $_cacheKeyArray = NULL;
	
	/**
	 * Initialize block's cache
	 */
	protected function _construct()
	{
		parent::_construct();

		$this->addData(array(
			'cache_lifetime'    => 99999999,
			'cache_tags'        => array(Mage_Catalog_Model_Product::CACHE_TAG),
		));
	}
	
	/**
	 * Get Key pieces for caching block content
	 *
	 * @return array
	 */
	public function getCacheKeyInfo()
	{
		if (NULL === $this->_cacheKeyArray)
		{
			$this->_cacheKeyArray = array(
				'INFORTIS_ITEMSLIDER',
				Mage::app()->getStore()->getCurrentCurrency()->getCode(),
				//Mage::app()->getStore()->getCurrentCurrencyCode(),
				
				Mage::app()->getStore()->getId(),
				Mage::getDesign()->getPackageName(), ///
				Mage::getDesign()->getTheme('template'), 
				Mage::getSingleton('customer/session')->getCustomerGroupId(),
				'template' => $this->getTemplate(),
				
				$this->getBlockName(),
				$this->getCategoryId(),
				$this->getShowItems(),
				$this->getIsResponsive(),
				$this->getBreakpoints(),
				$this->getHideButton(),
				$this->getTimeout(),
				$this->getInitDelay(),
				
				(int)Mage::app()->getStore()->isCurrentlySecure(),
				$this->getUniqueCollectionId(),
			);
		}
		return $this->_cacheKeyArray;
	}
	
	/**
	 * Get collection id
	 *
	 * @return string
	 */
	public function getUniqueCollectionId()
	{
		if (NULL === $this->_productCollectionId)
		{
			$this->_prepareCollectionAndCache();
		}
		return $this->_productCollectionId;
	}
	
	/**
	 * Get number of products in the collection
	 *
	 * @return int
	 */
	public function getCollectionCount()
	{
		if (NULL === $this->_collectionCount)
		{
			$this->_prepareCollectionAndCache();
		}
		return $this->_collectionCount;
	}
	
	/**
	 * Prepare collection id, count collection
	 */
	protected function _prepareCollectionAndCache()
	{
		$ids = array();
		$i = 0;
		foreach ($this->_getProductCollection() as $product)
		{
			$ids[] = $product->getId();
			$i++;
		}
		
		$this->_productCollectionId = implode("+", $ids);
		$this->_collectionCount = $i;
	}
	
	/**
	 * Retrieve loaded category collection.
	 * Variables collected from CMS markup: category_id, product_count, is_random
	 */
	protected function _getProductCollection()
	{
		if (is_null($this->_productCollection))
		{
			$categoryID = $this->getCategoryId();
			if($categoryID)
			{
				$category = new Mage_Catalog_Model_Category();
				$category->load($categoryID);
				$collection = $category->getProductCollection();
			}
			else
			{
				$collection = Mage::getResourceModel('catalog/product_collection');
			}
   
   $userids=array();
        $state ="";
        Mage::getModel('core/cookie')->set('testcookie', 'testvalue');
        if (Mage::getModel('core/cookie')->get('testcookie') != "") {
        $country = Mage::getModel('core/cookie')->get('Country');
    } else {
        $country = Mage::getSingleton('core/session', array('name' => 'frontend'))->getCountry();
    }
        if (Mage::getModel('core/cookie')->get('testcookie') != "") {
        $my_states = Mage::getModel( 'directory/region')->getCollection()->addFieldToSelect( 'region_id' )->addFieldToFilter( 'name', array('in' => array(Mage::getModel('core/cookie')->get('Region'))))->load();
        foreach ( $my_states as $st ) {
					$state = $st->getData('region_id');
				}
    } else {
        $my_states = Mage::getModel( 'directory/region')->getCollection()->addFieldToSelect( 'region_id' )->addFieldToFilter( 'name', array('in' => array(Mage::getSingleton('core/session', array('name' => 'frontend'))->getRegion())))->load();
        foreach ( $my_states as $st ) {
					$state = $st->getData('region_id');
				}
    }
        if (Mage::getModel('core/cookie')->get('testcookie') != "") {
        $city = Mage::getModel('core/cookie')->get('City');
    } else {
        $city = Mage::getSingleton('core/session', array('name' => 'frontend'))->getCity();
    }      
        if($state == "")
        {
            if (Mage::getModel('core/cookie')->get('testcookie') != "") {
        $state = Mage::getModel('core/cookie')->get('Region');
    } else {
        $state = Mage::getSingleton('core/session', array('name' => 'frontend'))->getRegion();
    }
        }
        $my_suppliers = Mage::getModel( 'multisuppliers/multisuppliers')->getCollection()->load();
         foreach ( $my_suppliers as $supplier ) {
            $shipppingaddress = json_decode($supplier->getData('shippingaddress'));
					foreach($shipppingaddress as $sph){
     if(strtolower($sph->country) == strtolower($country))
     {
        if(strtolower($sph->state) == strtolower($state) || $sph->state == 'All')
        {
            if(strtolower($sph->city) == strtolower($city) || $sph->city == 'All')
            {
                $userids[] = $supplier->getData('userid'); 
            }
           
        }
        
     }
     }
				}
                
    $my_users = Mage::getModel('admin/user')->getCollection()->addFieldToSelect( 'user_id' )->addFieldToFilter( 'user_id', array('in' => $userids))->addFieldToFilter( 'is_active', 1)->load();
    foreach ( $my_users as $user ) {
					$my_users_array[] = $user->getUserId();
				}
                 
        $my_products = Mage::getModel( 'multisuppliers/productscollect')->getCollection()->addFieldToSelect( 'product_id' )->addFieldToFilter( 'user_id', array('in' => $my_users_array))->load();
                    foreach ( $my_products as $product ) {
					$my_product_array[] = $product->getProductId();
				}
      $_testproductCollection = Mage::getResourceModel('catalog/product_collection')
->addAttributeToSelect('*')->addAttributeToFilter('type_id','configurable');
$_testproductCollection->addAttributeToFilter('entity_id', array('in' => $my_product_array))->load();
$nskuarray = array();
foreach($_testproductCollection as $_testproduct){ 
    if($_testproduct->getData('nsku') !="")
    {
      $nskuarray[$_testproduct->getData('nsku')][$_testproduct->getData('entity_id')] = $_testproduct->getData('price');  
    }
    
}
//echo("<pre>");print_r($nskuarray);exit;
$minprice = array();
foreach($nskuarray as $index => $arr)
{
 $minprice[] = array_search(min($nskuarray[$index]), $nskuarray[$index]);   
}
        $collection->addAttributeToFilter('entity_id', array('in' => $minprice));
			Mage::getModel('catalog/layer')->prepareProductCollection($collection);
			
			if ($this->getIsRandom())
			{
				$collection->getSelect()->order('rand()');
			}
			$collection->addStoreFilter();
			$productCount = $this->getProductCount() ? $this->getProductCount() : 8;
			$collection->setPage(1, $productCount)
				->load();
		
        
        
        	
			$this->_productCollection = $collection;
		}
		return $this->_productCollection;
	}
	
	/**
	 * Create unique block id for frontend
	 *
	 * @return string
	 */
	public function getFrontendHash()
	{
		return md5(implode("+", $this->getCacheKeyInfo()));
	}
}
