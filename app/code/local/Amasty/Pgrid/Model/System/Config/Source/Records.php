<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Pgrid
 */
class Amasty_Pgrid_Model_System_Config_Source_Records
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => '20',
                'label' => Mage::helper('ampgrid')->__('20')
            ),
            array(
                'value' => '30',
                'label' => Mage::helper('ampgrid')->__('30')
            ),
            array(
                'value' => '50',
                'label' => Mage::helper('ampgrid')->__('50')
            ),
            array(
                'value' => '100',
                'label' => Mage::helper('ampgrid')->__('100')
            ),
            array(
                'value' => '200',
                'label' => Mage::helper('ampgrid')->__('200')
            ),
            array(
                'value' => '500',
                'label' => Mage::helper('ampgrid')->__('500')
            ),
            array(
                'value' => '1000',
                'label' => Mage::helper('ampgrid')->__('1000')
            ),
            array(
                'value' => '5000',
                'label' => Mage::helper('ampgrid')->__('5000')
            )
        );
    }
}
