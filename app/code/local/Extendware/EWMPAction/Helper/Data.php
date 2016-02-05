<?php
class Extendware_EWMPAction_Helper_Data extends Extendware_EWCore_Helper_Data_Abstract
{
	public function getDisabledActions() {
		static $disabled = null;
    	if ($disabled === null) {
    		$disabled = array();
    		if (Mage::helper('ewmpaction/config')->getDisabledActions()) {
    			$disabled = preg_split('/\s*,\s*/', Mage::helper('ewmpaction/config')->getDisabledActions());
    		}
    	}
    	return $disabled;
	}
	
	public function getActions() {
		$actions = array(
			'price_update', 'price_round', 'price_modify', 'price_modifycost', 'price_copy',
			'price_special_update', 'price_special_round', 'price_special_modify', 'price_special_modifyprice',  'price_special_modifycost', 'price_special_copy', 'price_special_remove',
			'conditionedprice_tier_copy', 'conditionedprice_tier_delete',
			'conditionedprice_group_copy', 'conditionedprice_group_delete',
			'category_assign', 'category_unassign',
			'associations_related_add', 'associations_related_delete', 
			'associations_upsell_add', 'associations_upsell_delete', 
			'associations_crosssell_add', 'associations_crosssell_delete', 
			'misc_duplicate', 'customoptions_copy', 'attributeset_change', 'misc_delete'
		);
		
		$sortedActions = array();
		foreach ($actions as $action) {
			$sortedActions[] = $action;
			if ($action == 'price_special_remove') {
				$customActions = $this->getConfig()->getCustomPriceAttributes();
				foreach ($customActions as $code) {
					$sortedActions[] = 'ew:' . $code . ':' . 'update';
					$sortedActions[] = 'ew:' . $code . ':' . 'round';
					$sortedActions[] = 'ew:' . $code . ':' . 'modify';
					if ($code != 'cost') $sortedActions[] = 'ew:' . $code . ':' . 'modifycost';
					if ($code != 'price') $sortedActions[] = 'ew:' . $code . ':' . 'modifyprice';
				}
			}
		}
		
		return $sortedActions;
	}
}