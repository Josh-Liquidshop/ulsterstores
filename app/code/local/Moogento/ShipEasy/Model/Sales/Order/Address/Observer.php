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


class Moogento_Shipeasy_Model_Sales_Order_Address_Observer
{
    public function saveAfter($observer)
    {
        $address = $observer->getAddress();
        if ($address->getEntityId() && $address->getParentId()) {

            $orderId = $address->getParentId();
            $order = Mage::getModel('sales/order')->load($orderId);
            if (!$order->getId()) {
                return $this;
            }

            $dataToUpgrade = array();
            $dataToUpgrade[$address->getAddressType().'_name'] = $address->getFirstname() . ' ' . $address->getLastname();

            if (
                    ($order->getIsVirtual() && ($address->getAddressType()=='billing')) ||
                    (!$order->getIsVirtual() && ($address->getAddressType()=='shipping'))
            ) {
                $dataToUpgrade['szy_customer_name'] = $address->getFirstname() . ' ' . $address->getLastname();
                $dataToUpgrade['szy_country'] = $address->getCountryId();
                $dataToUpgrade['szy_region'] = $address->getRegion();
            }

            $orderResource = Mage::getResourceSingleton('moogento_shipeasy/sales_order');
            $orderResource->massUpdateGridRow($orderId, $dataToUpgrade);
        }
    }
}
