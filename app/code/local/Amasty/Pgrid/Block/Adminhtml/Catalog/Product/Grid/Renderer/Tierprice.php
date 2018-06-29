<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Pgrid
 */


class Amasty_Pgrid_Block_Adminhtml_Catalog_Product_Grid_Renderer_Tierprice
    extends Amasty_Pgrid_Block_Adminhtml_Catalog_Product_Grid_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $product = Mage::getModel('catalog/product')->load($row->getEntityId());
        $tierPriceString = '';
        foreach ($product->getTierPrice() as $tierPriceItem) {
            if ((int)$tierPriceItem['price_qty'] != 0 && (int)$tierPriceItem['website_price'] != 0) {
                $tierPriceString .= '<div>' .
                    Mage::helper('ampgrid')->__('For Qty') . ' = ' . (int)$tierPriceItem['price_qty'] .
                    Mage::helper('ampgrid')->__(' Price') . ' = ' . (int)$tierPriceItem['website_price'] .'</div>';
            }
        }

        return $tierPriceString;
    }
}