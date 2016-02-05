<?php
class Dev14_CustomerGallery_Block_Adminhtml_Gallery_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

  protected function _prepareMassaction()
{
$this->setMassactionIdField('tax_calculation_rate_id');
$this->getMassactionBlock()->setFormFieldName('tax_id');
 
$this->getMassactionBlock()->addItem('delete', array(
'label'=> Mage::helper('tax')->__('Delete'),
'url'  => $this->getUrl('*/*/massDelete', array('' => '')),        // public function massDeleteAction() in Mage_Adminhtml_Tax_RateController
'confirm' => Mage::helper('tax')->__('Are you sure?')
));
 
return $this;
}
   public function __construct()
   {
       parent::__construct();
       $this->setId('galleryGrid');
       $this->setDefaultSort('gallery_id');
       $this->setDefaultDir('DESC');
       $this->setSaveParametersInSession(true);
   }
 


protected function _prepareCollection()
   {
      $collection = Mage::getModel('customergallery/gallery')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
    }




    
   protected function _prepareColumns()
   {

    $this->addColumn('gallery_id',
             array(
                    'header' => 'ID',
                    'align' =>'right',
                    'width' => '50px',
                    'index' => 'gallery_id',
               ));


       $this->addColumn('name',
               array(
                    'header' => 'name',
                    'align' =>'left',
                    'index' => 'name',
              ));

        
      

        
         return parent::_prepareColumns();

    }
    public function getRowUrl($row)
    {
         return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}