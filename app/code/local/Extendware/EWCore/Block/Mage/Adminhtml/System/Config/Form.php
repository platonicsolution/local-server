<?php
abstract class Extendware_EWCore_Block_Mage_Adminhtml_System_Config_Form extends Mage_Adminhtml_Block_System_Config_Form
{
    protected function getModuleNameIdentifier()
    {
    	return @array_pop(explode('_', $this->getModuleName()));
    }
    
    protected function _initObjects()
    {
    	// changes: replaced adminhtml/config with ewcore/adminhtml_config
    	//			replaced adminhtml/config_data with ewcore/adminhtml_config_data
    	//			replaced $this->_defaultFieldsetRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_fieldset');
        $this->_configRoot = Mage::getConfig()->getNode(null, $this->getScope(), $this->getScopeCode());

        $this->_configDataObject = Mage::getModel(strtolower($this->getModuleNameIdentifier()) . '/adminhtml_config_data')
            ->setSection($this->getSectionCode())
            ->setWebsite($this->getWebsiteCode())
            ->setStore($this->getStoreCode());

        $this->_configData = $this->_configDataObject->load();

        $this->_configFields = Mage::getSingleton(strtolower($this->getModuleNameIdentifier()) . '/adminhtml_config');

        $this->_defaultFieldsetRenderer = Mage::getBlockSingleton('ewcore/adminhtml_config_form_fieldset');
        $this->_defaultFieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
        return $this;
    }
    
	protected function _prepareElement($xmlElement, $helperName = null)
    {
    	$helper = Mage::helper($helperName ? $helperName : 'ewcore');
    	if ($xmlElement->ewoverrides) {
    		if (!$xmlElement->fields) {
    			foreach ($xmlElement->ewoverrides->children() as $xml) {
		    		$ifhelper = (string) $xml->ifhelper;
		    		if ($ifhelper) {
						$helperParts = explode('/', $ifhelper);
						$helperMethod = array_pop($helperParts);
						$helperName = implode('/', $helperParts);
						$compareValue = isset($xml->ifvalue) ? (string)$xml->ifvalue : 1;
		    			$compareValue = isset($xml->ifvalue) ? (string)$xml->ifvalue : 0; // done for backwards compat. remove at some point
						if (call_user_func_array(array(Mage::helper($helperName), $helperMethod), array()) == $compareValue) {
							if ($xml->field_option) {
								foreach ($xml->field_option->children() as $name => $value) {
									if ($name == 'depends') $xmlElement->depends = $value->hasChildren() ? $value : null;
									else {
										$string = (string)$value;
										if (in_array($name, array('comment', 'label'))) $string = $helper->__($string);
										if ($name == 'comment') $string = $this->cleanComment($string);
										$xmlElement->setNode($name, $string);
										
										if ($name == 'ewhelp') $xmlElement->setNode('ewhelp', $value);
										else if ($name == 'disabled') $xmlElement->setNode('disabled', (int)$value);
									}
								}
							}
						}
			        }
    			}
    		}
    	}
    	
    	return $this;
    }
    
    protected function _prepareFieldOriginalData($field, $xmlElement)
    {
    	$originalData = array();
        foreach ($xmlElement as $key => $value) {
			$originalData[$key] = $value;
        }
        
        if ($field->getData('ewhelp')) $originalData['ewhelp'] = $field->getData('ewhelp');
        $field->setOriginalData($originalData);
        // do not call parent because it is not compatible with Magento 1.4.0.0 and a few customers run this.
        //return parent::_prepareFieldOriginalData($field, $xmlElement);
    	return $this;
    }
    
	public function initFields($fieldset, $group, $section, $fieldPrefix='', $labelPrefix='')
    {
        if (!$this->_configDataObject) {
            $this->_initObjects();
        }

        // Extends for config data
        $configDataAdditionalGroups = array();

        foreach ($group->fields as $elements) {
            $elements = (array)$elements;
            // sort either by sort_order or by child node values bypassing the sort_order
            if ($group->sort_fields && $group->sort_fields->by) {
                $fieldset->setSortElementsByAttribute(
                    (string)$group->sort_fields->by,
                    $group->sort_fields->direction_desc ? SORT_DESC : SORT_ASC
                );
            } else {
                usort($elements, array($this, '_sortForm'));
            }

            foreach ($elements as $element) {
            	$helperName = $this->_configFields->getAttributeModule($section, $group, $element);
            	$this->_prepareElement($element, $helperName);
            	
                if (!$this->_canShowField($element)) {
                    continue;
                }

                if ((string)$element->getAttribute('type') == 'group') {
                    $this->_initGroup($fieldset->getForm(), $element, $section, $fieldset);
                    continue;
                }

                /**
                 * Look for custom defined field path
                 */
                $path = (string)$element->config_path;
                if (empty($path)) {
                    $path = $section->getName() . '/' . $group->getName() . '/' . $fieldPrefix . $element->getName();
                } elseif (strrpos($path, '/') > 0) {
                    // Extend config data with new section group
                    $groupPath = substr($path, 0, strrpos($path, '/'));
                    if (!isset($configDataAdditionalGroups[$groupPath])) {
                        $this->_configData = $this->_configDataObject->extendConfig(
                            $groupPath,
                            false,
                            $this->_configData
                        );
                        $configDataAdditionalGroups[$groupPath] = true;
                    }
                }

                $inherit = null;
                $data = $this->_configDataObject->getConfigDataValue($path, $inherit, $this->_configData);
                if ($element->frontend_model) {
                    $fieldRenderer = Mage::getBlockSingleton((string)$element->frontend_model);
                } else {
                    $fieldRenderer = $this->_defaultFieldRenderer;
                }

                $fieldRenderer->setForm($this);
                $fieldRenderer->setConfigData($this->_configData);

                $helperName = $this->_configFields->getAttributeModule($section, $group, $element);
                $fieldType  = (string)$element->frontend_type ? (string)$element->frontend_type : 'text';
                $name  = 'groups[' . $group->getName() . '][fields][' . $fieldPrefix.$element->getName() . '][value]';
                $label =  Mage::helper($helperName)->__($labelPrefix) . ' '
                    . Mage::helper($helperName)->__((string)$element->label);
                $hint  = (string)$element->hint ? Mage::helper($helperName)->__((string)$element->hint) : '';

                if ($element->backend_model) {
                    $model = Mage::getModel((string)$element->backend_model);
                    if (!$model instanceof Mage_Core_Model_Config_Data) {
                        Mage::throwException('Invalid config field backend model: '.(string)$element->backend_model);
                    }
                    $model->setPath($path)
                        ->setValue($data)
                        ->setWebsite($this->getWebsiteCode())
                        ->setStore($this->getStoreCode())
                        ->afterLoad();
                    $data = $model->getValue();
                }

                $comment = $this->_prepareFieldComment($element, $helperName, $data);
                $tooltip = $this->_prepareFieldTooltip($element, $helperName);
                $id = $section->getName() . '_' . $group->getName() . '_' . $fieldPrefix . $element->getName();
                
                if ($element->depends) {
                    foreach ($element->depends->children() as $dependent) {
                        /* @var $dependent Mage_Core_Model_Config_Element */

                        if (isset($dependent->fieldset)) {
                            $dependentFieldGroupName = (string)$dependent->fieldset;
                            if (!isset($this->_fieldsets[$dependentFieldGroupName])) {
                                $dependentFieldGroupName = $group->getName();
                            }
                        } else {
                            $dependentFieldGroupName = $group->getName();
                        }

                        $dependentFieldNameValue = $dependent->getName();
                        $dependentFieldGroup = $dependentFieldGroupName == $group->getName()
                            ? $group
                            : $this->_fieldsets[$dependentFieldGroupName]->getGroup();

                        $dependentId = $section->getName()
                            . '_' . $dependentFieldGroupName
                            . '_' . $fieldPrefix
                            . $dependentFieldNameValue;
                        $shouldBeAddedDependence = true;
                        $dependentValue = $dependent;
                        if (isset($dependent->value)) {
                        	$dependentValue = $dependent->value;
                        }
                        $dependentValue = (string)$dependentValue;
                        if (isset($dependent['separator'])) {
                            $dependentValue = explode((string)$dependent['separator'], $dependentValue);
                        }
                        $dependentFieldName = $fieldPrefix . $dependent->getName();
                        $dependentField     = $dependentFieldGroup->fields->$dependentFieldName;
                        /*
                         * If dependent field can't be shown in current scope and real dependent config value
                         * is not equal to preferred one, then hide dependence fields by adding dependence
                         * based on not shown field (not rendered field)
                         */
                        if (!$this->_canShowField($dependentField)) {
                            $dependentFullPath = $section->getName()
                                . '/' . $dependentFieldGroupName
                                . '/' . $fieldPrefix
                                . $dependent->getName();
                            $dependentValueInStore = Mage::getStoreConfig($dependentFullPath, $this->getStoreCode());
                            if (is_array($dependentValue)) {
                                $shouldBeAddedDependence = !in_array($dependentValueInStore, $dependentValue);
                            } else {
                                $shouldBeAddedDependence = $dependentValue != $dependentValueInStore;
                            }
                        }
                        if ($shouldBeAddedDependence) {
                            $this->_getDependence()
                                ->addFieldMap($id, $id)
                                ->addFieldMap($dependentId, $dependentId)
                                ->addFieldDependence($id, $dependentId, $dependentValue);
                        }
                    }
                }
                $sharedClass = '';
                if ($element->shared && $element->config_path) {
                    $sharedClass = ' shared shared-' . str_replace('/', '-', $element->config_path);
                }

                $requiresClass = '';
                if ($element->requires) {
                    $requiresClass = ' requires';
                    foreach (explode(',', $element->requires) as $groupName) {
                        $requiresClass .= ' requires-' . $section->getName() . '_' . $groupName;
                    }
                }

                $comment = $this->cleanComment($comment);
                $field = $fieldset->addField($id, $fieldType, array(
                    'name'                  => $name,
                    'label'                 => $label,
                    'comment'               => $comment,
                    'tooltip'               => $tooltip,
                    'hint'                  => $hint,
                    'value'                 => $data,
                    'inherit'               => $inherit,
                    'class'                 => $element->frontend_class . $sharedClass . $requiresClass,
                    'field_config'          => $element,
                    'scope'                 => $this->getScope(),
                    'scope_id'              => $this->getScopeId(),
                    'scope_label'           => $this->getScopeLabel($element),
                    'can_use_default_value' => $this->canUseDefaultValue((int)$element->show_in_default),
                    'can_use_website_value' => $this->canUseWebsiteValue((int)$element->show_in_website),
                	'disabled'				=> (bool)(int)$element->disabled,
                ));
                $this->_prepareFieldOriginalData($field, $element);

                if (isset($element->validate)) {
                    $field->addClass($element->validate);
                }

                if (isset($element->frontend_type)
                    && 'multiselect' === (string)$element->frontend_type
                    && isset($element->can_be_empty)
                ) {
                    $field->setCanBeEmpty(true);
                }

                $field->setRenderer($fieldRenderer);

                if ($element->source_model) {
                    // determine callback for the source model
                    $factoryName = (string)$element->source_model;
                    $method = false;
                    if (preg_match('/^([^:]+?)::([^:]+?)$/', $factoryName, $matches)) {
                        array_shift($matches);
                        list($factoryName, $method) = array_values($matches);
                    }

                    $sourceModel = Mage::getSingleton($factoryName);
                    if ($sourceModel instanceof Varien_Object) {
                        $sourceModel->setPath($path);
                    }
                    if ($method) {
                        if ($fieldType == 'multiselect') {
                            $optionArray = $sourceModel->$method();
                        } else {
                            $optionArray = array();
                            foreach ($sourceModel->$method() as $value => $label) {
                                $optionArray[] = array('label' => $label, 'value' => $value);
                            }
                        }
                    } else {
                        $optionArray = $sourceModel->toOptionArray($fieldType == 'multiselect');
                    }
                    $field->setValues($optionArray);
                }
            }
        }
        return $this;
    }
    
    private function cleanComment($comment) {
    	if (Mage::helper('ewcore/config')->isWhiteLabeled()) {
			$comment = Mage::helper('ewcore/whitelabel')->removeLinks($comment);;
			$comment = preg_replace('/Extendware/', Mage::helper('ewcore/whitelabel')->getCompanyName(), $comment);
			if (preg_match('/extendware/i', $comment)) $comment = null;
		}
		return $comment;
    }
	protected function _getDependence()
    {
        if (!$this->getChild('element_dependense')){
            $this->setChild('element_dependense',
                $this->getLayout()->createBlock('ewcore/adminhtml_config_form_element_dependence'));
        }
        return $this->getChild('element_dependense');
    }
    
	protected function _prepareFieldComment($element, $helper, $currentValue)
    {
        $comment = '';
        $commentNode = $element->comment;
        if (isset($element->demo_comment) and Mage::helper('ewcore/environment')->isDemoServer() === true) {
        	$commentNode = $element->demo_comment;
        }
        if ($commentNode) {
            $commentInfo = $commentNode->asArray();
            if (is_array($commentInfo)) {
                if (isset($commentInfo['model'])) {
                    $model = Mage::getModel($commentInfo['model']);
                    if (method_exists($model, 'getCommentText')) {
                        $comment = $model->getCommentText($element, $currentValue);
                    }
                }
            } else {
                $comment = Mage::helper($helper)->__($commentInfo);
            }
        }
        return $comment;
    }
    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ONLY ADDED FOR COMPATABILIT WITH MAGENTO 1.4.0.0 FOR THOSE FEW CUSTOMERS TRYING TO RUN INCOMPATIBLE VERSIONS
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	protected function _prepareFieldTooltip($element, $helper)
    {
        if ($element->tooltip) {
            return Mage::helper($helper)->__((string)$element->tooltip);
        } elseif ($element->tooltip_block) {
            return $this->getLayout()->createBlock((string)$element->tooltip_block)->toHtml();
        }
        return '';
    }
}
