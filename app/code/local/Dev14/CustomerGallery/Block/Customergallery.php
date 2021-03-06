<?php
class Dev14_CustomerGallery_Block_CustomerGallery extends Mage_Core_Block_Template
{

// public function getCutomergallery1(){
//  $customergallery= Mage::getModel('customergallery/gallery')->getCollection()
//             ->join(array('images' => 'customergallery/galleryimages'),'main_table.gallery_id=images.gallery_id',array('*'))
//  ->addFieldToFilter('main_table.gallery_id',$GalleryId);
// echo '<pre>';
// print_r($customergallery);
// exit;
//   return $customergallery;



// }

public function getCutomergallery()
{
	$customergallery=Mage::getModel('customergallery/gallery')->getCollection()->addFieldToFilter('category',0);
	return $customergallery;
}


public function getCutomergalleryimages()
{
	$customergalleryimages= Mage::getModel('customergallery/galleryimages')->getCollection()->addFieldToFilter('category',0);
	return $customergalleryimages;
}



public function getProducts()
{
	$Products=Mage::getModel('catalog/product');
	return $Products;
}

public function getCurrency()
{
	$currency=Mage::app()->getLocale()->currency( $currency_code )->getSymbol();
	return $currency;
}


public function getResized()
{
	$resize=Mage::helper('customergallery');
	return $resize;
}


}