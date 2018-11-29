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
* File        Backorders.php
* @category   Moogento
* @package    pickPack
* @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
* @license    https://moogento.com/License.html
*/ 


class Moogento_ShipEasy_Block_Adminhtml_Widget_Grid_Column_Renderer_Backorders
    extends Moogento_ShipEasy_Block_Adminhtml_Widget_Grid_Column_Renderer
{
    protected $_block_name = 'moogento_shipeasy/adminhtml_sales_order_grid_backorders';
    
    public function render(Varien_Object $row)
    {
        return $this->getBlock()
            ->setOrder($row)
            ->toHtml();
    }

    public function renderExport(Varien_Object $row)
    {
        $value = (float)$this->_getValue($row);
        if ($value == 2) {
            return $this->__('All In Stock');
        } else if ($value == 0) {
            return $this->__('All Out Of Stock');
        } else {
            return $this->__('Some Out Of Stock');
        }
    }
}
