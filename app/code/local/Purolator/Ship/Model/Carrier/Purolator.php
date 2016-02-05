<?php
class Purolator_Ship_Model_Carrier_Purolator extends Mage_Shipping_Model_Carrier_Abstract
implements Mage_Shipping_Model_Carrier_Interface {
	protected $_code = 'purolator';
    var $client = null;
    private $_sandboxMode = false;
    
    public function __construct( $registry )
    {
        parent::__construct( $registry );
        if (Mage::getStoreConfig('carriers/'.$this->_code.'/sandbox')) {
			$this->_sandboxMode = true;
		}else{$this->_sandboxMode = false;}
        
        if ( $this->_sandboxMode == false ) //live mode
        {
            $this->PRODUCTION_KEY = Mage::getStoreConfig('carriers/'.$this->_code.'/prokey');
            $this->PRODUCTION_PASS = Mage::getStoreConfig('carriers/'.$this->_code.'/propass');
            $this->BILLING_ACCOUNT = Mage::getStoreConfig('carriers/'.$this->_code.'/billingaccount');
            $this->REGISTERED_ACCOUNT = Mage::getStoreConfig('carriers/'.$this->_code.'/registeredaccount');
        }
        else // sandbox mode
        {
            $this->PRODUCTION_KEY = "57f5b17d6f4c43229f86b5cb0af78d0b";
            $this->PRODUCTION_PASS = "Zdpq-!$>";
            $this->BILLING_ACCOUNT = "9999999999";
            $this->REGISTERED_ACCOUNT = "9999999999";
        }
        
    }
private function _createGetFullEstimatePWSSOAPClient()
    {
        $serviceFile = "";
        $location = "";
        if ( $this->_sandboxMode )
        {
            $serviceFile = Mage::getModuleDir('', 'Purolator_Ship') . DS .'admin' . DS. 'development' . DS .'EstimatingService.wsdl';
            $location = "https://devwebservices.purolator.com/EWS/V1/Estimating/EstimatingService.asmx";
        }else{
            $serviceFile = Mage::getModuleDir('', 'Purolator_Ship') . DS .'admin' . DS. 'production' . DS .'EstimatingService.wsdl';
            $location = "https://webservices.purolator.com/PWS/V1/Estimating/EstimatingService.asmx";
        }
        
       $this->client = new SoapClient( $serviceFile, 
                            array	(
                                    'trace'			=>	true,
									'location'	=>	$location,
                                    'uri'				=>	"http://purolator.com/pws/datatypes/v1",
                                    'login'			=>	$this->PRODUCTION_KEY,
                                    'password'	=>	$this->PRODUCTION_PASS
                                  )
                          );
  $headers[] = new SoapHeader ( 'http://purolator.com/pws/datatypes/v1', 
                                'RequestContext', 
                                array (
                                        'Version'           =>  '1.3',
                                        'Language'          =>  'en',
                                        'GroupID'           =>  'xxx',
                                        'RequestReference'  =>  'Rating Example'
                                      )
                              );                         
  $this->client->__setSoapHeaders($headers);
        return $this->client;
    }
	public function collectRates(Mage_Shipping_Model_Rate_Request $magentorequest)
	{
		if (!Mage::getStoreConfig('carriers/'.$this->_code.'/active')) {
			return false;
		}
		
        $myclient = $this->_createGetFullEstimatePWSSOAPClient();
        if ( $this->_sandboxMode == false ) //live mode
        {
           $response = $myclient->GetFullEstimate($this->_getProductionShipmentFields($magentorequest));
        }
        else // sandbox mode
        {
           $response = $myclient->GetFullEstimate($this->_getSandBoxShipmentFields());
        }
        

        

        
		$price = $this->getConfigData('price'); // set a default shipping price maybe 0
		//$price = 0;
		/*
		 //Case1: Price Depends on Country,State and Pin Code
		 echo $destCountry = $request->getDestCountryId().': Dest Country<br/>';
		 echo $destRegion = $request->getDestRegionId().': Dest Region<br/>';
		 echo $destRegionCode = $request->getDestRegionCode().': Dest Region Code<br/>';
		 print_r($destStreet = $request->getDestStreet()); echo ': Dest Street<br/>';
		 echo $destCity = $request->getDestCity().': Dest City<br/>';
		 echo $destPostcode = $request->getDestPostcode().': Dest Postcode<br/>';
		 echo $country_id = $request->getCountryId().': Package Source Country ID<br/>';
		 echo $region_id = $request->getRegionId().': Package Source Region ID<br/>';
		 echo $city = $request->getCity().': Package Source City<br/>';
		 echo $postcode = $request->getPostcode().': Package Source Post Code<br/>';

		 //Case2: Price Depends on Total Order Value or Weight
		 echo $packageValue = $request->getPackageValue().': Dest Package Value<br/>';
		 echo $packageValueDiscout = $request->getPackageValueWithDiscount().': Dest Package Value After Discount<br/>';
		 echo $packageWeight = $request->getPackageWeight().': Package Weight<br/>';
		 echo $packageQty = $request->getPackageQty().': Package Quantity <br/>';
		 echo $packageCurrency = $request->getPackageCurrency().': Package Currency <br/>';

		 //Case3: Price Depends on order dimensions
		 echo $packageheight = $request->getPackageHeight() .': Package height <br/>';
		 echo $request->getPackageWeight().': Package Width <br/>';
		 echo $request->getPackageDepth().': Package Depth <br/>';
		 


		//Case4: Price based on product attribute
		if ($request->getAllItems()) {
			foreach ($request->getAllItems() as $item) {
				if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
					continue;
				}

				if ($item->getHasChildren() && $item->isShipSeparately()) {
					foreach ($item->getChildren() as $child) {
						if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
							$product_id = $child->getProductId();
							$productObj = Mage::getModel('catalog/product')->load($product_id);
							$ship_price = $productObj->getData('shipping_price'); //our shipping attribute code
							$price += (float)$ship_price;
						}
					}
				} else {
					$product_id = $item->getProductId();
					$productObj = Mage::getModel('catalog/product')->load($product_id);
					$ship_price = $productObj->getData('shipping_price'); //our shipping attribute code
					$price += (float)$ship_price;
				}
			}
		}
      
		//Case5: Shipping option based configurable product option
		if ($request->getAllItems()) {
			foreach ($request->getAllItems() as $item) {
				if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
					continue;
				}
				if ($item->getHasChildren() && $item->isShipSeparately()) {
					foreach ($item->getChildren() as $child) {
						if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
							$product_id = $child->getProductId();
							$value = $item->getOptionByCode('info_buyRequest')->getValue();
							$params = unserialize($value);
							$attributeObj = Mage::getModel('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY,'shirt_size'); // our configurable attribute
							$attribute_id = $attributeObj->getAttributeId();
							$attribute_selected = $params['super_attribute'][$attribute_id];

							$label = '';
							foreach($attributeObj->getSource()->getAllOptions(false) as $option){
								if($option['value'] == $attribute_selected){
									$label =  $option['label'];
								}
							}
							if($label = 'Small'){
								$price += 15;
							} else if($label = 'Medium'){
								$price += 20;
							} else if($label = 'Large'){
								$price += 22;
							}
						}
					}
				} else {
					$product_id = $item->getProductId();
					$value = $item->getOptionByCode('info_buyRequest')->getValue();
					$params = unserialize($value);
					$attributeObj = Mage::getModel('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY,'shirt_size'); // our configurable attribute
					$attribute_id = $attributeObj->getAttributeId();
					$attribute_selected = $params['super_attribute'][$attribute_id];

					$label = '';
					foreach($attributeObj->getSource()->getAllOptions(false) as $option){
						if($option['value'] == $attribute_selected){
							$label =  $option['label'];
						}
					}
					if($label = 'Small'){
						$price += 15;
					} else if($label = 'Medium'){
						$price += 20;
					} else if($label = 'Large'){
						$price += 22;
					}
				}
			}
		}
		
		
		//Case6: Price based on custom options
		if ($request->getAllItems()) {
			foreach ($request->getAllItems() as $item) {
				if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
					continue;
				}
				if ($item->getHasChildren() && $item->isShipSeparately()) {
					foreach ($item->getChildren() as $child) {
						if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
							$product_id = $child->getProductId();
							$value = $item->getOptionByCode('info_buyRequest')->getValue();
							$params = unserialize($value);
							$options_select = $params['options'];

							$product = Mage::getModel('catalog/product')->load($product_id);
							$options = $product->getOptions();
							foreach ($options as $option) {
								if ($option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT) {
									$option_id =  $option->getId();
									foreach ($option->getValues() as $value) {
										if($value->getId() == $options_select[$option_id]){
											if($value->getTitle() == 'Express'){
												$price += 50;
											}else if($value->getTitle() == 'Normal'){
												$price += 10;
											}
										}

									}
								}
							}
						}
					}
				} else {
					$product_id = $item->getProductId();
					$value = $item->getOptionByCode('info_buyRequest')->getValue();
					$params = unserialize($value);
					$options_select = $params['options'];

					$product = Mage::getModel('catalog/product')->load($product_id);
					$options = $product->getOptions();
					foreach ($options as $option) {
						if ($option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT) {
							$option_id =  $option->getId();
							foreach ($option->getValues() as $value) {
								if($value->getId() == $options_select[$option_id]){
									if($value->getTitle() == 'Express'){
										$price += 50;
									}else if($value->getTitle() == 'Normal'){
										$price += 10;
									}
								}

							}
						}
					}
				}
			}
		}
		*/
		
$handling = Mage::getStoreConfig('carriers/'.$this->_code.'/handling');
$result = Mage::getModel('shipping/rate_result');
if($response && $response->ShipmentEstimates->ShipmentEstimate)
{
  //Loop through each Service returned and display the ID and TotalPrice
  foreach($response->ShipmentEstimates->ShipmentEstimate as $estimate)
  {
    
    $method = Mage::getModel('shipping/rate_result_method');
			$method->setCarrier($this->_code);
			$method->setMethod($estimate->ServiceID);
			$method->setCarrierTitle($this->getConfigData('title'));
			$method->setMethodTitle($estimate->ServiceID);
			$method->setPrice($estimate->TotalPrice);
			$method->setCost($price);
			$result->append($method);
    
  }
}

		
		else{
			$error = Mage::getModel('shipping/rate_result_error');
			$error->setCarrier($this->_code);
			$error->setCarrierTitle($this->getConfigData('name'));
			$error->setErrorMessage($this->getConfigData('specificerrmsg'));
			$result->append($error);
		}
		return $result;
	}
    
    
    
    private function _getSandBoxShipmentFields()
    {
        $request->Shipment->SenderInformation->Address->Name = "Aaron Summer";
$request->Shipment->SenderInformation->Address->StreetNumber = "1234";
$request->Shipment->SenderInformation->Address->StreetName = "Main Street";
$request->Shipment->SenderInformation->Address->City = "Vancouver";
$request->Shipment->SenderInformation->Address->Province = "BC";
$request->Shipment->SenderInformation->Address->Country = "CA";
$request->Shipment->SenderInformation->Address->PostalCode = "V6z3e8";    
$request->Shipment->SenderInformation->Address->PhoneNumber->CountryCode = "1";
$request->Shipment->SenderInformation->Address->PhoneNumber->AreaCode = "905";
$request->Shipment->SenderInformation->Address->PhoneNumber->Phone = substr( "123456789", -7 );
//Populate the Desination Information
$request->Shipment->ReceiverInformation->Address->Name = "Aaron Summer";
$request->Shipment->ReceiverInformation->Address->StreetNumber = "2245";
$request->Shipment->ReceiverInformation->Address->StreetName = "Douglas Road";
$request->Shipment->ReceiverInformation->Address->City = "Burnaby";
$request->Shipment->ReceiverInformation->Address->Province = "BC";
$request->Shipment->ReceiverInformation->Address->Country = "CA";
$request->Shipment->ReceiverInformation->Address->PostalCode = "V5C1A1";    
$request->Shipment->ReceiverInformation->Address->PhoneNumber->CountryCode = "1";
$request->Shipment->ReceiverInformation->Address->PhoneNumber->AreaCode = "604";
$request->Shipment->ReceiverInformation->Address->PhoneNumber->Phone = substr( "123456789", -7 );

//Future Dated Shipments - YYYY-MM-DD format
$request->Shipment->ShipmentDate = "2014-06-30";

//Populate the Package Information
$request->Shipment->PackageInformation->TotalWeight->Value = "10";
$request->Shipment->PackageInformation->TotalWeight->WeightUnit = "lb";
$request->Shipment->PackageInformation->TotalPieces = "1";
$request->Shipment->PackageInformation->ServiceID = "PurolatorExpress";
//Populate the Payment Information
$request->Shipment->PaymentInformation->PaymentType = "Sender";
$request->Shipment->PaymentInformation->BillingAccountNumber = $this->BILLING_ACCOUNT;
$request->Shipment->PaymentInformation->RegisteredAccountNumber = $this->REGISTERED_ACCOUNT;
//Populate the Pickup Information
$request->Shipment->PickupInformation->PickupType = "DropOff";
$request->ShowAlternativeServicesIndicator = "true";

//OriginSignatureNotRequired
$request->Shipment->PackageInformation->OptionsInformation->Options->OptionIDValuePair->ID = "OriginSignatureNotRequired";
$request->Shipment->PackageInformation->OptionsInformation->Options->OptionIDValuePair->Value = "true";
 return $request;
    }
    
private function _getProductionShipmentFields(Mage_Shipping_Model_Rate_Request $mgrequest)
    {
    
		$regionId = $mgrequest->getRegionId();
$region = Mage::getModel('directory/region')->load($regionId);  
    $request->Shipment->SenderInformation->Address->Name = "Aaron Summer";
//$request->Shipment->SenderInformation->Address->StreetNumber = "1234";
$request->Shipment->SenderInformation->Address->StreetName = $mgrequest->getStreet();
$request->Shipment->SenderInformation->Address->City = $mgrequest->getCity();
$request->Shipment->SenderInformation->Address->Province = $region->getCode();
$request->Shipment->SenderInformation->Address->Country = $mgrequest->getCountryId();
$request->Shipment->SenderInformation->Address->PostalCode = $mgrequest->getPostcode();    
$request->Shipment->SenderInformation->Address->PhoneNumber->CountryCode = "1";
$request->Shipment->SenderInformation->Address->PhoneNumber->AreaCode = "905";
$request->Shipment->SenderInformation->Address->PhoneNumber->Phone = substr( "123456789", -7 );
//Populate the Desination Information
$request->Shipment->ReceiverInformation->Address->Name = "Aaron Summer";
$request->Shipment->ReceiverInformation->Address->StreetName = $mgrequest->getDestStreet();
$request->Shipment->ReceiverInformation->Address->City = $mgrequest->getDestCity();
$regionId = $mgrequest->getDestRegionId();
$region = Mage::getModel('directory/region')->load($regionId);
$request->Shipment->ReceiverInformation->Address->Province = $region->getCode();
$request->Shipment->ReceiverInformation->Address->Country = $mgrequest->getDestCountryId();
$request->Shipment->ReceiverInformation->Address->PostalCode = $mgrequest->getDestPostcode();    
$request->Shipment->ReceiverInformation->Address->PhoneNumber->CountryCode = "1";
$request->Shipment->ReceiverInformation->Address->PhoneNumber->AreaCode = "604";
$request->Shipment->ReceiverInformation->Address->PhoneNumber->Phone = substr( "123456789", -7 );

//Future Dated Shipments - YYYY-MM-DD format
$request->Shipment->ShipmentDate = Mage::getModel('core/date')->date('Y-m-d');

//Populate the Package Information         
         
$request->Shipment->PackageInformation->TotalWeight->Value = $mgrequest->getPackageWeight();
$request->Shipment->PackageInformation->TotalWeight->WeightUnit = "lb";
$request->Shipment->PackageInformation->TotalPieces = $mgrequest->getPackageQty();
$request->Shipment->PackageInformation->ServiceID = "PurolatorExpress";
//Populate the Payment Information
$request->Shipment->PaymentInformation->PaymentType = "Sender";
$request->Shipment->PaymentInformation->BillingAccountNumber = $this->BILLING_ACCOUNT;
$request->Shipment->PaymentInformation->RegisteredAccountNumber = $this->REGISTERED_ACCOUNT;
//Populate the Pickup Information
$request->Shipment->PickupInformation->PickupType = "DropOff";
$request->ShowAlternativeServicesIndicator = "true";

//OriginSignatureNotRequired
$request->Shipment->PackageInformation->OptionsInformation->Options->OptionIDValuePair->ID = "OriginSignatureNotRequired";
$request->Shipment->PackageInformation->OptionsInformation->Options->OptionIDValuePair->Value = "true";
 return $request;
    } 
	public function getAllowedMethods()
	{
		return array('purolator'=>$this->getConfigData('name'));
	}
}