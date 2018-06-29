<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Shiprestriction
 */
require_once Mage::getModuleDir('controllers', 'Amasty_Commonrules').DS.'Adminhtml'.DS.'Amcommonrules'.DS.'RuleController.php';

class Amasty_Shiprestriction_Adminhtml_Amshiprestriction_RuleController extends Amasty_Commonrules_Adminhtml_Amcommonrules_RuleController
{
    protected function _construct()
    {
        parent::_construct();
        $this->_title       = 'Shipping Restriction';
        $this->_namespace   = 'amshiprestriction';
    }
}