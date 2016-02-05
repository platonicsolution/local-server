<?php
class Dev14_CustomerGallery_Adminhtml_GalleryController extends
    Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $block = $this->getLayout()->createBlock('customergallery/adminhtml_gallery',
            'customergallery-block');

        $this->_addContent($block);
        $this->renderLayout();
    }

    /**
     * Added to remove access denied page after upgrade to magento 1.9.2.1
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('customergallery');
    }

   

    public function uploadAction()
    {
        $result = array();
        try {
            $uploader = new Varien_File_Uploader('image');
            $uploader->setAllowedExtensions(array(
                'jpg',
                'jpeg',
                'gif',
                'png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(Mage::getBaseDir('media') . DS . "customergallery");

            $result['url'] = Mage::getBaseUrl('media') . "/" . "customergallery" . '/' . $result['file'];
            $fileName = $result['file'];
            $result['file'] = $result['file'] . '.tmp';

            $result['cookie'] = array(
                'name' => session_name(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain());
        }
        catch (exception $e) {
            $result = array('error' => $e->getMessage(), 'errorcode' => $e->getCode());
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }


    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('test/set_time')->_addBreadcrumb('test Manager',
            'test Manager');
        return $this;
    }
    public function editAction()
    {
        try {
            $postData = $this->getRequest()->getPost();
            $GalleryId = $this->getRequest()->getParam('id');
            $Model = Mage::getModel('customergallery/gallery')->load($GalleryId);


            $Modelimages = Mage::getModel('customergallery/galleryimages')->getCollection()->
                addFieldToFilter('main_table.gallery_id', $GalleryId)->setOrder('position',
                'asc');
            $i = 1;
            foreach ($Modelimages as $row) {
                $img_data[] = array(
                    'url' => $row->getData('images'),
                    "value_id" => $i,
                    'productid' => $row['product_id'],
                    'reviewername' => $row['reviewer_name'],
                    'review' => $row['review'],
                    'label' => $row['imagesname'],
                    'position' => $row['position'],
                    'category' => $Model['category'],
                    "file" => "/b/$i/black_12.jpg",
                    "img_label" => "ddfdfd");
                $i++;
            }
            // $Modelimages222 = Mage::getModel('customergallery/galleryimages');

            //     $Modelimages222
            //     ->setCategory($Model['category'])
            //     ->save();

            $mainData[0]['name'] = $Model->getName();
            $mainData[0]['thumbnail'] = $Model->getThumbnail();
            $mainData[0]['category'] = $Model->getCategory();
            Mage::register('gallery_data', $mainData);
            Mage::register('photogallery_img', $img_data);
            $this->loadLayout();

            $this->_addBreadcrumb('test Manager', 'test Manager');
            $this->_addBreadcrumb('Test Description', 'Test Description');
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('customergallery/adminhtml_gallery_edit'))->
                _addLeft($this->getLayout()->createBlock('customergallery/adminhtml_gallery_edit_tabs'));
            $this->renderLayout();
            return;
        }
        catch (exception $e) {
            echo $e->getMessage();
            exit;
        }


        $GalleryId = $this->getRequest()->getParam('id');
        $Model = Mage::getModel('customergallery/gallery')->load($GalleryId);
        try {
            if ($Model->getId() || $GalleryId == 0) {
                $Model = Mage::getModel('customergallery/gallery')->getCollection()->join(array
                    ('images' => 'customergallery/galleryimages'),
                    'main_table.gallery_id=images.gallery_id', array('*'), null, 'left')->
                    addFieldToFilter('main_table.gallery_id', $GalleryId);

                $img_data = array();
                $i = 1;
                foreach ($Model as $row) {
                    $img_data[] = array(
                        'image_id' => $i,
                        'image' => $row['images'],
                        'my_lable' => $row['imagesname'],
                        'my_position' => 0,
                        'my_disabled' => 1);

                    $i++;
                }
                Mage::register('photogallery_img', $img_data);
            }

        }

        catch (exception $e) {
            echo $e->getMessage();

        }


        if ($GalleryId != 0) {

            //Mage::register('gallery_data', $Model);

            $this->loadLayout();

            $this->_addBreadcrumb('test Manager', 'test Manager');
            $this->_addBreadcrumb('Test Description', 'Test Description');
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('customergallery/adminhtml_gallery_edit'))->
                _addLeft($this->getLayout()->createBlock('customergallery/adminhtml_gallery_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError('Test does not exist');
            $this->_redirect('*/*/');
        }
    }


    public function newAction()
    {
        $GalleryId = $this->getRequest()->getParam('id');
        $Model = Mage::getModel('customergallery/gallery')->getCollection()->join(array
            ('images' => 'customergallery/galleryimages'),
            'main_table.gallery_id=images.gallery_id', array('*'))->addFieldToFilter('main_table.gallery_id',
            $GalleryId);


        $img_data = array();
        Mage::register('photogallery_img', $img_data);
        $this->loadLayout();

        $this->_addBreadcrumb('test Manager', 'test Manager');
        $this->_addBreadcrumb('Test Description', 'Test Description');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('customergallery/adminhtml_gallery_edit'))->
            _addLeft($this->getLayout()->createBlock('customergallery/adminhtml_gallery_edit_tabs'));
        $this->renderLayout();


    }


    public function massDeleteAction()
    {
        $taxIds = $this->getRequest()->getParam('tax_id');
        if (!is_array($taxIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('tax')->__('Please select tax(es).'));
        } else {
            try {
                $rateModel = Mage::getModel('customergallery/gallery');
                foreach ($taxIds as $taxId) {
                    $rateModel->load($taxId)->delete();
                }

                $Modelimages = Mage::getModel('customergallery/galleryimages')->getCollection()->
                    addFieldToFilter('main_table.gallery_id', $taxIds);
                foreach ($Modelimages as $key => $value) {
                    $value->delete();

                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('tax')->__('Total of %d record(s) were deleted.',
                    count($taxIds)));
            }
            catch (exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/');
    }


    public function saveAction()
    {

        $s = $this->getRequest()->getPost();
        $imagestab = json_decode($s['customergallery_images'], true);

// echo "<pre>";
// print_r($s['fileinputname']['delete']);
// exit;

        $a = $this->getRequest()->getPost();

        if ($this->getRequest()->getPost())
            if (isset($_FILES['fileinputname']['name']) and (file_exists($_FILES['fileinputname']['tmp_name']))) {
                

                try {
                    $uploader = new Varien_File_Uploader('fileinputname');
                    $uploader->setAllowedExtensions(array(
                        'jpg',
                        'jpeg',
                        'gif',
                        'png'));


                    $uploader->setAllowRenameFiles(false);

                    $uploader->setFilesDispersion(false);

                    $path = Mage::getBaseDir('media') . DS;

                    $path_thumbnail = $uploader->save($path, $_FILES['fileinputname']['name']);
                    $data['fileinputname'] = $_FILES['fileinputname']['name'];
                    
                }
                catch (exception $e) {

                }
            } else {

                if (isset($_FILES['fileinputname']['delete']) && $_FILES['fileinputname']['delete'] ==
                    1)
                    $path_thumbnail = '';
                else
                    unset($data['fileinputname']);
            }



            

                try {
                    $postData = $this->getRequest()->getPost();

                    $Model = Mage::getModel('customergallery/gallery')->load($this->getRequest()->
                        getParam('id'));
                    if ($Model->getId()) {
                        $Modelimages = Mage::getModel('customergallery/galleryimages')->getCollection()->
                            addFieldToFilter("gallery_id", $this->getRequest()->getParam('id'))->
                            getFirstItem();

                             $Model->addData($postData)->setId($this->getRequest()->getParam('id'));
                             if(isset($path_thumbnail['file']) && $path_thumbnail['file'] != ""){
                                $Model->setThumbnail($path_thumbnail['file']); 
                                 }
                             $Model->save();

                        $Modelimages222 = Mage::getModel('customergallery/galleryimages')->
                            getCollection()->addFieldToFilter("gallery_id", $this->getRequest()->getParam('id'));

                        foreach ($Modelimages222 as $key => $value) {
                            $value->delete();
                        }

                        foreach ($imagestab as $key => $value) {
                            $Modelimages = Mage::getModel('customergallery/galleryimages');
                            if ($value['removed'] == 0 && $value['position'] > 0) {
                                $Modelimages->addData($postData)->setGalleryId($Model['gallery_id'])->
                                    setcategory($Model['category'])->setimages($value['url'])->setImagesname($value['label'])->
                                    setPosition($value['position'])->setProductId($value['productid'])->
                                    setReviewerName($value['reviewername'])->setReview($value['review'])->save();


                            }
                        }


                    }


                                             if($s['fileinputname']['delete']==1){
                                                $path_thumbnail['file'] = '';
                                                $Model->setThumbnail('')->save(); 
                                            }


                    $Modelimages = Mage::getModel('customergallery/galleryimages');


                    if ($this->getRequest()->getParam('id') <= 0) {
                          $Model->setData($postData);
                             if(isset($path_thumbnail['file']) && $path_thumbnail['file'] != ""){
                                $Model->setThumbnail($path_thumbnail['file']); 
                                 }

                             $Model->save();






                        foreach ($imagestab as $key => $value) {
                            

                            if ($value['removed'] == 0 && $value['position'] > 0) {
                                $Modelimages->addData($postData)->setGalleryId($Model['gallery_id'])->
                                    setcategory($Model['category'])->setimages($value['url'])->setImagesname($value['label'])->
                                    setPosition($value['position'])->setProductId($value['productid'])->
                                    setReviewerName($value['reviewername'])->setReview($value['review'])->save();

                            }
                        }
                    }


                    Mage::getSingleton('adminhtml/session')->addSuccess('successfully saved');
                    Mage::getSingleton('adminhtml/session')->settestData(false);
                    $this->_redirect('*/*/');
                    return;
                }

                catch (exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->settestData($this->getRequest()->
                        getPost());
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
            
            $this->_redirect('*/*/');
    }


}

?>