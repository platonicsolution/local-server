<?php

	/**
	 * MageWorx
	 * MageWorx SeoTemplates Extension
	 *
	 * @category   MageWorx
	 * @package    MageWorx_SeoTemplates
	 * @copyright  Copyright (c) 2015 MageWorx (http://www.mageworx.com/)
	 */
	class MT_SeoTemplates_Model_Catalog_Category_Template_Category extends MageWorx_SeoTemplates_Model_Catalog_Category_Template_Category
	{
		protected $_useDefault      = array();
		protected $_defaultCategory = null;

		public function process()
		{
			if ( !$this->_category instanceof Mage_Catalog_Model_Category )
			{
				return;
			}
			$string = $this->__compile( $this->getTemplate() );

			return $string;
		}

		protected function __compile( $template )
		{
			$vars = $this->__parse( $template );
			foreach ( $vars as $key => $params )
			{
				foreach ( $params[ 'attributes' ] as $n => $attribute )
				{
//					if ( $attribute == 'range' and !$this->_category->getTmIsRange() )
//					{
//						$template = "";
//						break 2;
//					}
					switch ( $attribute )
					{
						case
						'range':
							$value = "";
							if ( $this->_category->getTmIsRange() )
							{
								$value = "range";
							}
							//Mage::log( "asfasdfasdf" );
							break;
						case 'category':
							$value = $this->_category->getName();
							break;
						case 'price':
						case 'special_price':
							break;
						case 'parent_category':
							$value = '';
							if ( $this->_category->getParentId() )
							{
								$cat = Mage::getModel( 'catalog/category' )->load( $this->_category->getParentId() );
								if ( $cat->getId() !== Mage::app()->getWebsite( Mage::app()->getStore( $this->_category->getStoreId() )->getWebsite()->getId() )->getDefaultStore()->getRootCategoryId() )
								{
									$value = $cat->getName();
								}
							}
							break;
						case 'categories':
							$separator = (string)Mage::getStoreConfig( 'catalog/seo/title_separator' );
							$separator = ' ' . $separator . ' ';
							$paths = explode( '/', $this->_category->getPath() );
							if ( is_array( $paths ) )
							{
								array_shift( $paths );
								if ( Mage::helper( 'seotemplates/config' )->isCropRootCategory( $this->_category->getStoreId() ) )
								{
									array_shift( $paths );
								}
								foreach ( $paths as $categoryId )
								{
									$category = Mage::getModel( 'catalog/category' )->load( $categoryId );
									$path[] = trim( $category->getName() );
								}
							}
							else
							{
								$categories = $this->_category->getParentCategories();
								// add category path breadcrumb
								foreach ( $categories as $categoryId )
								{
									$category = Mage::getModel( 'catalog/category' )->load( $categoryId->getId() );
									$path[] = trim( $category->getName() );
								}
							}
							if ( !empty( $path ) && is_array( $path ) && count( $path ) > 0 )
							{
								$path = array_filter( $path );
								$value = join( $separator, array_reverse( $path ) );
							}
							else
							{
								$value = '';
							}
							break;
						case 'store_view_name':
							$value = Mage::app()->getStore( $this->_category->getStoreId() )->getName();
							break;
						case 'page_number':
							$value = Mage::getBlockSingleton( 'page/html_pager' )->getCurrentPage();
							if ( $value < 2 )
							{
								$value = '';
							}
							break;
						case 'store_name':
							$value = Mage::app()->getStore( $this->_category->getStoreId() )->getGroup()->getName();
							break;
						case 'website_name':
							$value = Mage::app()->getStore( $this->_category->getStoreId() )->getWebsite()->getName();
							break;
						default:
							if ( $_attr = $this->_category->getResource()->getAttribute( $attribute ) )
							{
								$value = $_attr->getSource()->getOptionText( $this->_category->getData( $attribute ) );
							}
							if ( !$value )
							{
								$value = $this->_category->getData( $attribute );
							}
							if ( is_array( $value ) )
							{
								$value = implode( ', ', $value );
							}
					}
					if ( !empty( $value ) )
					{
						$value = $params[ 'prefix' ] . $value . $params[ 'suffix' ];
						break;
					}
				}
				$template = str_replace( $key, $value, $template );
			}
			$template = rtrim( $template, "-" );
			$template = str_replace( array('--', '---', '----', '- -'), '-', $template );

			return $template;
		}
	}
