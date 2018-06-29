<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Pgrid
 */

class Amasty_Pgrid_Adminhtml_Ampgrid_AttributeController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $attributesKey = Mage::app()->getRequest()->getParam('attributesKey', '');
        $block = $this->getLayout()->createBlock(
            'ampgrid/adminhtml_catalog_product_grid_attributes',
            '',
            array('attributes_key'=>$attributesKey)
        );
        if ($block) {
            Mage::helper('ambase/utils')->_echo($block->toHtml());
            Mage::helper('ambase/utils')->_exit(1);
        }
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/products');
    }

    /**
     * save columns grid settings in to groups
     * @throws Exception
     */
    public function saveAction()
    {
        $request = Mage::app()->getRequest();

        if (1 == $request->getParam('delete_group')) {
            $this->_deleteGroup();
            return $this->_redirectReferer();
        }

        $extraKey = $request->getParam('attributesKey', '');
        $isNewGroup = $request->getParam('is-new-group');

        //Save Group
        $currentGroup = Mage::getModel('ampgrid/group');
        if (1 != $isNewGroup) {
            $currentGroup->loadActiveGroup($extraKey);
        } else {
            $currentGroup->setData('title', $request->getParam('group-name'));
            $currentGroup->setData('user_id', Mage::getSingleton('admin/session')->getUser()->getId());
        }
        $currentGroup->save();

        //Save Columns
        $columns = $this->getRequest()->getParam('column');
        $this->_saveColumns($columns, $currentGroup, $isNewGroup);

        //Save Attributes
        $attributes = $request->getParam('pattribute', array());
        if (!Mage::helper('catalog/product_flat')->isAvailable() && count($attributes) > 55) {
            $message = Mage::helper('ampgrid')->__(
                'You are trying to add more than 55 attributes to the grid when the flat tables usage is disabled.
                        Enable the flat tables to add such number of attributes.'
            );
            Mage::getSingleton('adminhtml/session')->addError($message);
            $this->_redirectReferer();
            return;
        }
        foreach($attributes as $key => $attr) {
            if (!$attr['attribute_id']) {
                unset($attributes[$key]);
            }
        }
        $this->_saveAttributes($attributes, $currentGroup);

        if (1 == $isNewGroup) {
            $this->_changeGroup($currentGroup->getId(), $extraKey);
            Mage::getConfig()->cleanCache();
        }
        $this->_redirectReferer();

    }

    protected function _deleteGroup()
    {
        $attributesKey = Mage::app()->getRequest()->getParam('attributesKey', '');

        $groupId = Mage::helper('ampgrid')->getSelectedGroupId();
        $currentGroup = Mage::getModel('ampgrid/group')->load($groupId);
        $currentGroup->delete();

        $defaultGroup = Mage::helper('ampgrid')->getDefaultGroup($attributesKey);
        return $this->_changeGroup($defaultGroup, $attributesKey);
    }

    /**
     * @param array $columnsData
     * @param Amasty_Pgrid_Model_Group $currentGroup
     * @param 1|0 $isNewGroup
     *
     * @throws Exception
     */
    protected function _saveColumns($columnsData, $currentGroup, $isNewGroup)
    {
        foreach ($columnsData as $columnId => $columnData) {
            $columnModel = Mage::getModel('ampgrid/groupcolumn');
            if (1 != $isNewGroup) {
                $columnModel->load($columnData['group_column_id']);
            } else {
                $columnModel->setData('column_id', $columnId);
                $columnModel->setData('group_id', $currentGroup->getId());
            }

            if(array_key_exists('is_editable', $columnData)) {
                $columnModel->setData('is_editable', $columnData['is_editable']);
            }
            if(array_key_exists('is_display_totals', $columnData)) {
                $columnModel->setData('is_display_totals', $columnData['is_display_totals']);
            }
            $columnModel->setData('is_visible', $columnData['is_visible']);
            if (array_key_exists('custom_title', $columnData)) {
                $columnModel->setData('custom_title', $columnData['custom_title']);
            }
            $columnModel->save();
        }
    }

    /**
     * @param array $attributesData
     * @param Amasty_Pgrid_Model_Group $currentGroup
     *
     * @throws Exception
     */
    protected function _saveAttributes($attributesData, $currentGroup)
    {
        $attrModel = Mage::getModel('ampgrid/groupattribute');
        $attrModel->getCollection()
            ->addFieldToFilter('group_id', $currentGroup->getId())
            ->walk('delete');
        if (!empty($attributesData)) {
            $copyAttribute = array();
            foreach ($attributesData as $key => &$value) {
                $value['group_id'] = $currentGroup->getId();

                if (in_array($value['attribute_id'], $copyAttribute)) {
                    unset($attributesData[$key]);
                } else {
                    $copyAttribute[] = $value['attribute_id'];
                }
            }
            $attrModel->insert($attributesData);
        }
    }

    public function changeGroupAction()
    {
        $request = Mage::app()->getRequest();
        $groupId = $request->getParam('group_id', 1);
        $extraKey = $request->getParam('attributesKey', '');
        $this->_changeGroup($groupId,$extraKey);
        Mage::getConfig()->cleanCache();
        return $this->_redirectReferer();
    }

    protected function _redirectBack()
    {
        $backUrl = Mage::app()->getRequest()->getParam('backurl');
        if (!$backUrl) {
            $backUrl = Mage::getUrl('adminhtml/catalog/product');
        }
        return $this->getResponse()->setRedirect($backUrl);
    }

    protected function _changeGroup($groupId, $attributeKey = '') {
        if (Mage::getStoreConfig('ampgrid/attr/byadmin')) {
            $attributeKey .= Mage::getSingleton('admin/session')->getUser()->getId();
        }
        Mage::getConfig()->saveConfig('ampgrid/attributes/ongrid' . $attributeKey, $groupId);
    }
}
