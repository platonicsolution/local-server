  <?php
/**
 * FME Service Center
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   FME
 * @package    Service Center
 * @author     Muhammad Qaisar Satti <shumail123@gmail.com>
 *         
 * @copyright  Copyright 2015 © www.fmeextensions.com All right reserved
 */

class Dev14_CustomerGallery_Block_Adminhtml_Gallery_Edit_Tab_Images extends Mage_Adminhtml_Block_Widget
{  
  protected function _prepareForm()
    {
  $data = $this->getRequest()->getPost();
        $form = new Varien_Data_Form();
        $form->setValues($data);
        $this->setForm($form);
        
        return parent::_prepareForm();
    }
    
  public function images(){
  
           $test = Mage::registry('gallery_data');

           return $test; 
  }
  
  public function __construct()
    {
        parent::__construct();
        $this->setTemplate('customergallery/blank.phtml');
         $this->setId('customergallery_content');
         $this->setHtmlId('customergallery_content');
    }
    
    protected function _prepareLayout()
    {
        $this->setChild('uploader',
            $this->getLayout()->createBlock('adminhtml/media_uploader')
        );

        $this->getUploader()->getConfig()
            ->setUrl(Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('*/*/upload'))
            ->setFileField('image')
            ->setFilters(array(
                'images' => array(
                    'label' => Mage::helper('adminhtml')->__('Images (.gif, .jpg, .png)'),
                    'files' => array('*.gif', '*.jpg','*.jpeg', '*.png')
                )
            ));
    
         $this->setChild(
            'delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->addData(array(
                    'id'      => '{{id}}-delete',
                    'class'   => 'delete',
                    'type'    => 'button',
                    'label'   => Mage::helper('adminhtml')->__('Remove'),
                    'onclick' => $this->getJsObjectName() . '.removeFile(\'{{fileId}}\')'
                ))
        );    
            
        return parent::_prepareLayout();
    }

    /**
     * Retrive uploader block
     *
     * @return Mage_Adminhtml_Block_Media_Uploader
     */
    public function getUploader()
    {
        return $this->getChild('uploader');
    }

    /**
     * Retrive uploader block html
     *
     * @return string
     */
    public function getUploaderHtml()
    {
        return $this->getChildHtml('uploader');
    }

    public function getJsObjectName()
    {
        return $this->getHtmlId() . 'JsObject';
    }

    public function getAddImagesButton()
    {
        return $this->getButtonHtml(
            Mage::helper('catalog')->__('Add New Images'),
            $this->getJsObjectName() . '.showUploader()',
            'add',
            $this->getHtmlId() . '_add_images_button'
        );
    }

    public function getImagesJson()
    { 
      $polje = $this->images();
 
      if(is_array($polje)) {
            $value['images'] = $polje;
            /*echo "<pre>";
            print_r($value['images']);
            exit;*/
            if(count($value['images'])>0) {
                    $i;
                    foreach ($value['images'] as &$image) {
                        $image['url'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'customergallery/images'.$image['image']; 
                        $image['value_id'] = $image['image_id'];
                        unset($image['image_id']);
                        $image['file'] = $image['image'];
                        unset($image['image']);
                        $image['label'] = $image['my_lable'];
                        unset($image['my_lable']);
                        
                        $image['position'] = $image['my_position'];
                        unset($image['my_position']);
                        $image['disabled'] = $image['my_disabled'];
                        unset($image['my_disabled']);
                        $i++;
                    }
                    return Zend_Json::encode($value['images']);
            }
          
    }
return '[]';
    }
    public function getImagesValuesJson()
    {
        $values = array();

        return Zend_Json::encode($values);
    }


    /**
     * Enter description here...
     *
     * @return array
     */
    public function getMediaAttributes()
    {

    }
    
    public function getImageTypes()
    {
      $type = array();
        $type['customergallery']['label'] = "customergallery";
        $type['customergallery']['field'] = "customergallery";
        
      $imageTypes = array();

      return $type;
    }
    
    public function getImageTypesJson()
    {
        return Zend_Json::encode($this->getImageTypes());
    }
    
    public function getCustomRemove()
    {
        return $this->setChild(
            'delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->addData(array(
                    'id'      => '{{id}}-delete',
                    'class'   => 'delete',
                    'type'    => 'button',
                    'label'   => Mage::helper('adminhtml')->__('Remove'),
                    'onclick' => $this->getJsObjectName() . '.removeFile(\'{{fileId}}\')'
                ))
        );
    }
    
    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    public function getCustomValueId()
    {
        return $this->setChild(
            'value_id',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->addData(array(
                    'id'      => '{{id}}-value',
                    'class'   => 'value_id',
                    'type'    => 'text',
                    'label'   => Mage::helper('adminhtml')->__('ValueId'),
                ))
        );
    }
    
    public function getValueIdHtml()
    {
        return $this->getChildHtml('value_id');
    }
 
}
