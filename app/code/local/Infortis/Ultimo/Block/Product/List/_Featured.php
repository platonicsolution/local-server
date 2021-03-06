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
        $country = Mage::getSingleton('core/session', array('name' => 'frontend'))->getCountry();
        $my_states = Mage::getModel( 'directory/region')->getCollection()->addFieldToSelect( 'region_id' )->addFieldToFilter( 'name', array('in' => array(Mage::getSingleton('core/session', array('name' => 'frontend'))->getRegion())))->load();
        foreach ( $my_states as $st ) {
					$state = $st->getData('region_id');
				}
        $city = Mage::getSingleton('core/session', array('name' => 'frontend'))->getCity();
        if($state == "")
        {
            $state = Mage::getSingleton('core/session', array('name' => 'frontend'))->getRegion();
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
       // $mycollection = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('entity_id', array('in' => $my_product_array));
//        //echo('<pre>');print_r($my_product_array);exit;
//        $uniqueskuid=array();
//        foreach($mycollection as $k)
//        {
//            $p = Mage::getModel('catalog/product')->load($k->getId());
//            $productID = Mage::getModel('catalog/product')->getCollection()->addFieldToFilter('nsku', $p->getNsku())->setOrder('price', 'ASC')->getFirstItem()->getData('entity_id');
//            if($productID != "")
//            {
//             $uniqueskuid[]=$productID;   
//            }else{
//                $uniqueskuid[]=$k->getId(); 
//            }
//            
//              
//        }
        $collection->addAttributeToFilter('entity_id', array('in' => $my_product_array));
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
