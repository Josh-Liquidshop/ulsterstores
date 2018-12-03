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
 * File        Tax.php
 *
 * @category   Moogento
 * @package    pickPack
 * @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
 * @license    https://moogento.com/License.html
 */
class Moogento_ShipEasy_Block_Adminhtml_Widget_Grid_Column_Renderer_Tax
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency
{

    public function renderCss()
    {
        return str_replace("a-right", "", parent::renderCss());
    }

    public function render(Varien_Object $row)
    {
        $orderId = $row->getData('entity_id');
        $orderAllData = Mage::getModel('sales/order')->load($orderId);

        $baseCurrencyCode  = $row->getData('base_currency_code');
        $orderCurrencyCode = $row->getData('order_currency_code');

        $taxInfo = $orderAllData->getFullTaxInfo();
        $baseRealAmount = $taxInfo[0]['base_real_amount'];
        $amount = $taxInfo[0]['amount'];
        if ($baseCurrencyCode != $orderCurrencyCode) {
            $content = Mage::app()->getLocale()->currency($baseCurrencyCode)
                ->toCurrency($baseRealAmount, array('precision' => 2));
            $content .= '[' . Mage::app()->getLocale()->currency($orderCurrencyCode)
                    ->toCurrency($amount, array('precision' => 2)) . ']';
        } else {
            $content = Mage::app()->getLocale()->currency($orderCurrencyCode)
                ->toCurrency($amount, array('precision' => 2));
        }

        return $content;
    }

    public function renderExport(Varien_Object $row)
    {
        $orderId = $row->getData('entity_id');
        $orderAllData = Mage::getModel('sales/order')->load($orderId);

        $content = '';
        $taxInfo = $orderAllData->getFullTaxInfo();//base_real_amount

        $base_currency_code  = $row->getData('base_currency_code');
        $order_currency_code = $row->getData('order_currency_code');
        $base_real_amount    = $taxInfo[0]['base_real_amount'];

        $amount = $taxInfo[0]['amount'];

        if ($base_currency_code != $order_currency_code) {
            $content = Mage::app()->getLocale()->currency($base_currency_code)
                           ->toCurrency($base_real_amount, array('precision' => 2));
            $content .= '[' . Mage::app()->getLocale()->currency($order_currency_code)
                                  ->toCurrency($amount, array('precision' => 2)) . ']';
        } else {
            $content = Mage::app()->getLocale()->currency($order_currency_code)
                           ->toCurrency($amount, array('precision' => 2));
        }

        return $content;
    }
}