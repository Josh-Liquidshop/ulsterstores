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
 * ID
 * File        Shoppaid.php
 *
 * @category   Moogento
 * @package    pickPack
 * @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
 * @license    https://moogento.com/License.html
 */
class Moogento_ShipEasy_Block_Adminhtml_Widget_Grid_Column_Renderer_Shippaid
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency
{

    public function renderCss()
    {
        return str_replace("a-right", "", parent::renderCss());
    }

    public function render(Varien_Object $row)
    {
        $baseShippingAmount = $row->getData('base_shipping_amount');
        $baseCurrencyCode  = $row->getData('base_currency_code');
        $orderCurrencyCode = $row->getData('order_currency_code');

        if ($baseCurrencyCode != $orderCurrencyCode) {
            $content = Mage::app()->getLocale()->currency($baseCurrencyCode)
                ->toCurrency($baseShippingAmount, array('precision' => 2));
            $content .= '[' . Mage::app()->getLocale()->currency($orderCurrencyCode)
                    ->toCurrency($baseShippingAmount, array('precision' => 2)) . ']';
        } else {
            $content = Mage::app()->getLocale()->currency($orderCurrencyCode)
                ->toCurrency($baseShippingAmount, array('precision' => 2));
        }

        return $content;
    }

    public function renderExport(Varien_Object $row)
    {
        $baseShippingAmount = $row->getData('base_shipping_amount');
        $baseCurrencyCode  = $row->getData('base_currency_code');
        $orderCurrencyCode = $row->getData('order_currency_code');

        if ($baseCurrencyCode != $orderCurrencyCode) {
            $content = Mage::app()->getLocale()->currency($baseCurrencyCode)
                ->toCurrency($baseShippingAmount, array('precision' => 2));
            $content .= '[' . Mage::app()->getLocale()->currency($orderCurrencyCode)
                    ->toCurrency($baseShippingAmount, array('precision' => 2)) . ']';
        } else {
            $content = Mage::app()->getLocale()->currency($orderCurrencyCode)
                ->toCurrency($baseShippingAmount, array('precision' => 2));
        }

        return $content;
    }
}