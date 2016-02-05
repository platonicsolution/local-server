<?php
class Extendware_EWPageCache_Block_Adminhtml_Config_Edit extends Extendware_EWCore_Block_Mage_Adminhtml_System_Config_Edit
{
	protected function _prepareLayout()
    {
    	$this->setChild('flush_cachedlog_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => $this->__('Flush Cached Log'),
                    'onclick'   => 'confirmSetLocation(\''
                . $this->__('Are you sure you want to flush the cached log? This will reset all the history that the crawler uses to crawl URLs. This history can take several weeks to rebuild after a flush.')
                . '\', \'' . $this->getFlushLogUrl() . '\')',
                    'class' => 'delete',
                ))
        );
        
        $this->setChild('selective_flush_fullpage_cache_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => $this->__('Flush By Group'),
                    'onclick'   => 'setLocation(\'' . $this->getFlushByGroupUrl() . '\')',
                    'class' => 'delete'
                ))
        );
        
        $this->setChild('tag_flush_fullpage_cache_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => $this->__('Flush By Tag'),
                    'onclick'   => 'setLocation(\'' . $this->getFlushyTagUrl() . '\')',
                    'class' => 'delete'
                ))
        );
        
        $this->setChild('flush_fullpage_cache_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => $this->__('Flush All Pages'),
                    'onclick'   => 'confirmSetLocation(\''
                . $this->__('Are you sure you want to flush the full page cache?') . ' ' . (!Mage::helper('ewpagecache/config')->isTaggingEnabled() ? $this->__('If you enable Tagging, then you will not have to do full flushes as frequently.') : '')
                . '\', \'' . $this->getFlushUrl() . '\')',
                    'class' => 'delete',
                ))
        );
        return parent::_prepareLayout();
    }
    
    public function getCustomFormButtonHtml() {
    	return $this->getChildHtml('flush_cachedlog_button') . $this->getChildHtml('flush_fullpage_cache_button') . $this->getChildHtml('selective_flush_fullpage_cache_button') . $this->getChildHtml('tag_flush_fullpage_cache_button');
    }
    
    public function getFlushUrl() {
    	return $this->getUrl('*/adminhtml_cache/flush');
    }
    
	public function getFlushByGroupUrl() {
    	return $this->getUrl('*/adminhtml_cache/flushByGroup');
    }
    
	public function getFlushyTagUrl() {
    	return $this->getUrl('*/adminhtml_cache/flushByTag');
    }
    
	public function getFlushLogUrl() {
    	return $this->getUrl('*/adminhtml_cache/flushLog');
    }
}
