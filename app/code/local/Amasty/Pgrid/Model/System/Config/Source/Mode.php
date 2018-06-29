<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Pgrid
 */


class Amasty_Pgrid_Model_System_Config_Source_Mode
{
    public function toOptionArray()
    {
        $hlp = Mage::helper('ampgrid');
        $options = array(
            array(
                'value' => 'single',
                'label' => $hlp->__('Single Cell')
            ),
            array(
                'value' => 'multi',
                'label' => $hlp->__('Multi Cell')
            )
        );
        return $options;
    }
}
