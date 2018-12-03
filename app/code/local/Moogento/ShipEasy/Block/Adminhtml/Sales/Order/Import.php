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
* File        Import.php
* @category   Moogento
* @package    pickPack
* @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
* @license    https://moogento.com/License.html
*/ 


class Moogento_ShipEasy_Block_Adminhtml_Sales_Order_Import extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected $_blockGroup = 'moogento_shipeasy';
    protected $_controller = 'adminhtml_sales_order';
    protected $_mode = 'import';

    public function __construct()
    {

        parent::__construct();
        $this->_headerText = Mage::helper('moogento_shipeasy')->__('Shipping Tracks Import');
        $this->removeButton('back');
        $this->removeButton('reset');
        $this->_updateButton('save', 'label', Mage::helper('moogento_shipeasy')->__('Run Import'));
        $this->_updateButton('save', 'on_click', 'editForm.submit(); $(\'import_file\').setValue(\'\');');
    }
}
