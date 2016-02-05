<?php
/**
 * CreteSol_Crm_Model_Rolesoptions use for system config option to set default role for CRM dashbord to get users there.
 *
 */
class CreteSol_Crm_Model_Rolesoptions{
  
	/**
	 * Options for system config
	 *
	 * @return array
	 */
	public function toOptionArray()
	{
		// Load admin roles
		$roles = Mage::getModel( 'admin/roles' )->getCollection();
		$rolesArray = array();
		foreach ( $roles as $role ) {
			$rolesArray[] = array( 'value' => $role->getRoleId(), 'label' => $role->getRoleName() );
		}
		
		return $rolesArray;
	}
}