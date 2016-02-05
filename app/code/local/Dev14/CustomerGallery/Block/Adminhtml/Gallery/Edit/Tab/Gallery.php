 <?php


class Dev14_CustomerGallery_Block_Adminhtml_Gallery_Edit_Tab_Gallery extends Mage_Adminhtml_Block_Widget
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
         $test = Mage::registry('photogallery_img');
         $img_data = $test;
         return $img_data; 
 }
 
 public function __construct()
    {
        parent::__construct();
        $this->setTemplate('customergallery/customgallery.phtml');
        $this->setId('photogallery_content');
        $this->setHtmlId('photogallery_content');
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
     if(is_array($polje) && count($polje)) {
            $value['images'] = $polje;

            if(count($value['images'])>0) {
                 
                 	
                 return Zend_Json::encode($value['images']);
         }
         return '[]';
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
        $type['gallery']['label'] = "gallery";
        $type['gallery']['field'] = "gallery";
        
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
