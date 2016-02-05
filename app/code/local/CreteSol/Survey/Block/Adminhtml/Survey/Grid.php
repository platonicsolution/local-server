<?php
 
class CreteSol_Survey_Block_Adminhtml_Survey_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('surveyGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
 

    protected function _prepareCollection()
    {
        
        $collection = Mage::getModel('survey/survey')->getCollection();
        
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'=> $this->__('Survey #'),
            'width' => '50px',
            'type'  => 'int',
            'index' => 'id',
        ));
        $this->addColumn('completed', array(
            'header'=> $this->__('Completed On'),
            'width' => '50px',
            'type' => 'datetime',
            'format' => Mage::app()->getLocale()
                ->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            'index' => 'completed',
        ));

      $this->addColumn('token', array(
            'header'=> $this->__('OrderId / Token #'),
            'width' => '50px',
            'type'  => 'int',
            'index' => 'token',
        ));
        $this->addColumn('q1', array(
            'header'=> $this->__('Q1 #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'q1',
        ));
        $this->addColumn('q2', array(
            'header'=> $this->__('Q2 #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'q2',
        ));
        $this->addColumn('q3', array(
            'header'=> $this->__('Q3 #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'q3',
        ));
        $this->addColumn('q4', array(
            'header'=> $this->__('Q4 #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'q4',
        ));
        $this->addColumn('q5', array(
            'header'=> $this->__('Q5 #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'q5',
        ));
            $this->addColumn('q6', array(
            'header'=> $this->__('Q6 #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'q6',
        ));
        $this->addColumn('q7', array(
            'header'=> $this->__('Q7 #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'q7',
        ));
        $this->addColumn('q8', array(
            'header'=> $this->__('Q8 #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'q8',
        ));
        $this->addColumn('q9', array(
            'header'=> $this->__('Q9 #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'q9',
        ));
        
        return parent::_prepareColumns();
    }
 
  
    public function getRowUrl($row)
    {
        //return $this->getUrl('*/*/edit', array('entity_id' => $row->getId()));
    }
 
    public function getGridUrl()
    {
      return $this->getUrl('*/*/grid', array('_current'=>true));
    }
 
 
}