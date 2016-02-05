<?php

class Dev14_CustomerGallery_IndexController extends Mage_Core_Controller_Front_Action
{
	 public function showalbumAction()
	{
		$X= $_POST["A"];
		$block=$this->getLayout()->createBlock('customergallery/showalbum')->setGalleryId($X)->toHtml();

		//$Id=$block->getId();

		$response = array('html' =>$block );
		return  $this->getResponse()->setBody(json_encode($response));

	}

}