<?php

class Moogento_ShipEasy_Block_Adminhtml_Widget_Grid_Column_Renderer_Channel_Unity_Origin
    extends Moogento_ShipEasy_Block_Adminhtml_Widget_Grid_Column_Renderer
{
    public function render(Varien_Object $row)
    {
        $origin = $row->getData($this->getColumn()->getIndex());
        $helper = Mage::helper('moogento_shipeasy');
        $defaultValue = $this->getColumn()->getDefault();
        if (!$origin) {
            if (is_null($origin)) {
                return $defaultValue;
            }
            return $helper->__('n/a');
        } else {
            if (Mage::getStoreConfig('moogento_shipeasy/grid/channel_unity_origin_replace_with_image')) {
                $list = Mage::helper('core/string')->unserialize(Mage::getStoreConfig('moogento_shipeasy/grid/channel_unity_origin_images'));
                if ($list && isset($list[$origin]) && $list[$origin]) {
                    return '<img src="' . Mage::getBaseUrl('media').DS.'moogento/shipeasy/channelunity/' . $list[$origin] . '" style="max-width: 25px;" />';
                } else {
                    return $origin;
                }
            } else {
                return $origin;
            }
        }
    }
} 