<?php

class Psolz_Returnpolicy_Block_Returnpolicy extends Mage_Core_Block_Template
{
    protected $_collection;
    public function _construct()
    {
        
        parent::_construct();
        $collection = Mage::getModel('returnpolicy/returnpolicy')->getCollection();
        $this->_collection=$collection;
        $this->setCollection($collection);
        //Zend_Debug::dump($this->getCollection()->getData());
    }

    protected function _prepareLayout()
    {
      
        parent::_prepareLayout();
        
        $pager = $this->getLayout()->createBlock('page/html_pager', '');//provide any name second parameter returnpolicy.pager or empty it will work
        //Zend_Debug::dump($pager);
        $pager->setCollection($this->getCollection());
        $this->setChild('pagers', $pager);// any name for first param could be anything
        $this->getCollection()->load();
        

        return $this;
    }

    public function getPagerHtml()
    {
        
        return $this->getChildHtml('pagers');//get the same of child which is set in setChild function
    }
    public function getProducts(){
          $c = Mage::getModel('catalog/product')->getCollection();
          return $c;
    }

    //public function getCollection()
//    {
//        echo 'yes';
//        $limit = 10;
//        $curr_page = 1;
//
//        if (Mage::app()->getRequest()->getParam('p'))
//        {
//            $curr_page = Mage::app()->getRequest()->getParam('p');
//        }
//
//        //Calculate Offset
//        $offset = ($curr_page - 1) * $limit;
//
//        $collection = Mage::getModel('returnpolicy/returnpolicy')->getCollection();
//
//        $collection->getSelect()->limit($limit, $offset);
//
//        return $collection;
//    }


}
