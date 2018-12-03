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
* File        Extraaction.php
* @category   Moogento
* @package    pickPack
* @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
* @license    https://moogento.com/License.html
*/ 


class Moogento_ShipEasy_Block_Adminhtml_Sales_Order_Popup_View_Extraaction extends Mage_Adminhtml_Block_Widget_Grid_Massaction
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('moogento/shipeasy/sales/order/popup/view/extraaction.phtml');
    }

    public function getHtmlId()
    {
        if (!$this->getData('html_id')) {
            $this->setData('html_id', 'sales_order_grid_massaction');
        }
        return $this->getData('html_id');
    }

    public function addItems(array $items)
    {
        foreach ($items as $_itemId => $_itemData) {
            $this->addItem($_itemId, $_itemData);
        }
        return $this;
    }

}
