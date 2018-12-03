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
 * File        Email.php
 * @category   Moogento
 * @package    pickPack
 * @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
 * @license    https://moogento.com/License.html
 */


class Moogento_ShipEasy_Block_Adminhtml_Widget_Grid_Column_Renderer_Weight
    extends Moogento_ShipEasy_Block_Adminhtml_Widget_Grid_Column_Renderer
{
    protected $_block_name = 'moogento_shipeasy/adminhtml_sales_order_grid_weight';

    public function render(Varien_Object $row)
    {
        return $this->getBlock()
            ->setOrder($row)
            ->toHtml();
    }

    /*public function getItemValue(Varien_Object $row)
    {
        if ($this->getColumn()->getEditable()) {
            $value = $this->_getValue($row);
            return $value
                . ($this->getColumn()->getEditOnly() ? '' : ($value != '' ? '' : '&nbsp;'))
                . $this->_getInputValueElement($row);
        }
        return $this->_getValue($row);
    }*/
}
