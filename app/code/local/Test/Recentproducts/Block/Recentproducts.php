<?php
// app/code/local/Envato/Recentproducts/Block/Recentproducts.php
class Test_Recentproducts_Block_Recentproducts extends Mage_Core_Block_Template {
  public function getRecentProducts() {
    // call model to fetch data
    $arr_products = array();
    $products = Mage::getModel("recentproducts/recentproducts")->getRecentProducts();
   return $products;
   var_dump($products);die();
    
//echo "<pre>";
 //print_r($products);
 //echo "</pre>";
 
   // foreach ($products as $product) {
//      $price = Mage::helper('core')->currency($product->getPrice());
//      $cart_url = Mage::helper('checkout/cart')->getAddUrl($product);
//      $arr_products[] = array(
//        'id' => $product->getId(),
//        'name' => $product->getName(),
//        'url' => $product->getProductUrl(),
//        'price' => $price,
//        'img' => $product->getImageUrl(),
//        'addToCart' => $cart_url
//      );
//    }
//    
//    return $arr_products;
//{{block type="recentproducts/recentproducts" name="recentproducts_recentproducts" template="recentproducts/recentproducts.phtml"}}
  }
}
