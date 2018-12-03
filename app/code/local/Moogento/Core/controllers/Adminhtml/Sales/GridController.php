<?php


class Moogento_Core_Adminhtml_Sales_GridController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return true;
    }

    public function idsAction()
    {
        if ($this->getRequest()->getParam('reset_cache')) {
            $cache = Mage::app()->getCache();
            $cache->clean('matchingAnyTag', array('moogento_cache'));
        }

        $grid = Mage::app()->getLayout()->createBlock('moogento_core/adminhtml_sales_order_grid');
        $grid->setTemplate('');
        $grid->toHtml();

        $collection = $grid->getCollection();

        // if collection has rewritten and method doesn't exists
        if (method_exists($collection, 'setUseCache')) {
            $collection->setUseCache(true);
        }

        $gridIds = $collection->getAllIds();

        if(!empty($gridIds)) {
            $ids = join(",", $gridIds);
        } else {
            $ids = '';
        }

        $this->getResponse()->setBody($ids);
    }

} 