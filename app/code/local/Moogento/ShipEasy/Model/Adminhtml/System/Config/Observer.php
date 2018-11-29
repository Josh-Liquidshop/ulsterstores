<?php 
/** 
* Moogento
* 
* SOFTWARE LICENSE
* 
* This source file is covered by the Moogento End User License Agreement
* that is bundled with this extension in the file License.html
* It is also available online here:
* https://moogento.com/License.html
* 
* NOTICE
* 
* If you customize this file please remember that it will be overwritten
* with any future upgrade installs. 
* If you'd like to add a feature which is not in this software, get in touch
* at moogento.com for a quote.
* 
* ID          pe+sMEDTrtCzNq3pehW9DJ0lnYtgqva4i4Z=
* File        Observer.php
* @category   Moogento
* @package    pickPack
* @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
* @license    https://moogento.com/License.html
*/ 


class Moogento_ShipEasy_Model_Adminhtml_System_Config_Observer
{
    protected $_websiteCodeFields = null;
    protected $_groupCodeFields = null;
    protected $_storeCodeFields = null;

    protected $_flagsCache = array();


    public function model_config_data_save_before($observer) 
    {
        $this->_flagsCache['mkt_order_id_show_ebay_sales_number'] = Mage::getStoreConfigFlag('moogento_shipeasy/grid/mkt_order_id_show_ebay_sales_number');
        $this->_flagsCache['mkt_order_id_show_mkt_link'] = Mage::getStoreConfigFlag('moogento_shipeasy/grid/mkt_order_id_show_mkt_link');
        $this->_flagsCache['szy_custom_product_attribute_inside'] = Mage::getStoreConfig('moogento_shipeasy/grid/szy_custom_product_attribute_inside');
        $this->_flagsCache['szy_custom_product_attribute2_inside'] = Mage::getStoreConfig('moogento_shipeasy/grid/szy_custom_product_attribute2_inside');
    }


    public function admin_system_config_changed_section_moogento_shipeasy($observer)
    {

        $section = Mage::app()->getRequest()->getParam('section');
        if ($section == 'moogento_shipeasy') {
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');

            if (Mage::helper('moogento_core')->isInstalled('Ess_M2ePro')) {
                if ($this->_flagsCache['mkt_order_id_show_ebay_sales_number']
                    != Mage::getStoreConfigFlag('moogento_shipeasy/grid/mkt_order_id_show_ebay_sales_number')
                    || $this->_flagsCache['mkt_order_id_show_mkt_link']
                       != Mage::getStoreConfigFlag('moogento_shipeasy/grid/mkt_order_id_show_mkt_link')
                ) {
                    $query
                        = "
                        UPDATE " . Mage::getSingleton('core/resource')->getTableName('sales/order_grid') . "
                        SET mkt_order_id = NULL
                        WHERE entity_id in (
                          select magento_order_id from " . Mage::getSingleton('core/resource')
                                                               ->getTableName('M2ePro/Order') . "
                          where magento_order_id is not null
                        )";

                    $write->query($query);
                }
            }

            if ($this->_flagsCache['szy_custom_product_attribute_inside'] != Mage::getStoreConfig('moogento_shipeasy/grid/szy_custom_product_attribute_inside')
                || $this->_flagsCache['szy_custom_product_attribute2_inside'] != Mage::getStoreConfig('moogento_shipeasy/grid/szy_custom_product_attribute2_inside'))
            {
                if ($this->_flagsCache['szy_custom_product_attribute_inside'] != Mage::getStoreConfig('moogento_shipeasy/grid/szy_custom_product_attribute_inside')) {
                    $write->query('UPDATE ' . Mage::getSingleton('core/resource')->getTableName('sales/order_grid') . ' SET szy_custom_product_attribute = NULL');
                }
                if ($this->_flagsCache['szy_custom_product_attribute2_inside'] != Mage::getStoreConfig('moogento_shipeasy/grid/szy_custom_product_attribute2_inside')) {
                    $write->query('UPDATE ' . Mage::getSingleton('core/resource')->getTableName('sales/order_grid') . ' SET szy_custom_product_attribute2 = NULL');
                }

                Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('moogento_shipeasy')->__('The product attribute columns may be incorrect for some time untill they get filled in'));
            }
        }
    }
}
