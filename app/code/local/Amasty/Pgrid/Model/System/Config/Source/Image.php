<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Pgrid
 */


class Amasty_Pgrid_Model_System_Config_Source_Image
{
    public function toOptionArray()
    {
        $hlp = Mage::helper('ampgrid');
        $options = array(
            array(
                'value' => 'image',
                'label' => $hlp->__('Base Image')
            ),
            array(
                'value' => 'small_image',
                'label' => $hlp->__('Small Image')
            ),
            array(
                'value' => 'thumbnail',
                'label' => $hlp->__('Thumbnail')
            ),
            array(
                'value' => 'carousel',
                'label' => $hlp->__('Media Gallery (All images as Carousel)')
            )
        );
        return $options;
    }
}