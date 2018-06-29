<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Shiprestriction
 */
if ('true' == (string)Mage::getConfig()->getNode('modules/Amasty_Methods/active'))
{
    class Amasty_Shiprestriction_Model_Shipping_Shipping_Pure extends Amasty_Methods_Model_Rewrite_Shipping_Shipping {}
} else 
{
    class Amasty_Shiprestriction_Model_Shipping_Shipping_Pure extends Mage_Shipping_Model_Shipping {}
}