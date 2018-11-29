<?php

class Moogento_Core_Model_Mysql4_Sales_Order_Grid_Rewrite_IWDOrderGrid_Collection
    extends IWD_OrderGrid_Model_Resource_Order_Grid_Collection
{
    protected $_useCache = false;

    public function clear()
    {
        $this->_setIsLoaded(false);
        $this->_items = array();
        $this->_totalRecords = null;
        return $this;
    }

    public function setUseCache($flag)
    {
        $this->_useCache = $flag;

        return $this;
    }

    public function getAllIds($limit=null, $offset=null)
    {
        if (!$this->_useCache) {
            return parent::getAllIds($limit, $offset);
        }
        $session = Mage::getSingleton('adminhtml/session');

        $filter = $session->getData('sales_order_gridfilter');
        if (!$filter) {
            $filter = 'EMPTY';
        }

        $cache = Mage::app()->getCache();

        $cacheKey = 'Order_Grid_Collection_AllIds_' . $filter;
        $data = $cache->load($cacheKey);
        if ($data) return explode(',',$data);

        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(Zend_Db_Select::COLUMNS);

        $idsSelect->columns($this->getResource()->getIdFieldName(), 'main_table');

        $data = $this->getConnection()->fetchCol($idsSelect);
        $cache->save(implode(',', $data), $cacheKey, array('moogento_cache'), 600);
        return $data;
    }

    public function getSelectCountSql()
    {
        $this->_renderFilters();
        $controllerName = Mage::app()->getRequest()->getControllerName();

        if ($controllerName == 'sales_order' || $controllerName == 'sales_archive_order') {

            $unionSelect = clone $this->getSelect();

            $unionSelect->reset(Zend_Db_Select::ORDER);
            $unionSelect->reset(Zend_Db_Select::LIMIT_COUNT);
            $unionSelect->reset(Zend_Db_Select::LIMIT_OFFSET);

            $countSelect = clone $this->getSelect();
            $countSelect->reset();
            $countSelect->from(array('a' => $unionSelect), 'COUNT(*)');
            return $countSelect;
        }

        if (method_exists($this, 'getIsCustomerMode') && $this->getIsCustomerMode()) {
            return parent::getSelectCountSql();
        } else {
            $countSelect = clone $this->getSelect();
            $countSelect->reset(Zend_Db_Select::ORDER);
            $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
            $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
            $countSelect->reset(Zend_Db_Select::COLUMNS);

            $countSelect->columns('main_table.entity_id');
        }

        $select = $this->getConnection()->select();
        $select->from($countSelect);
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->columns('count(*)');

        return $select;
    }

    public function addFieldToFilter($field, $condition = null) {
        if($field == 'increment_id') {
            $field = 'main_table.'.$field;
        }
        return parent::addFieldToFilter($field, $condition);
    }
}