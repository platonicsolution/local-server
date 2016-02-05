<?php
// app/code/local/Envato/Recentproducts/Model/Recentproducts.php
class Test_Recentproducts_Model_Recentproducts extends Mage_Core_Model_Abstract {
  public function getRecentProducts() {
    $products = Mage::getModel("catalog/product")
                ->getCollection()
                //                
                ->addAttributeToSelect('*')
                //->getMaxPrice()
                //->setOrder('entity_id', 'DESC')
                //->addAttributeToSort('price', 'desc');
                ->setOrder('price','DESC')
                ->setPageSize(3);
                
                
    return $products;
  }
}