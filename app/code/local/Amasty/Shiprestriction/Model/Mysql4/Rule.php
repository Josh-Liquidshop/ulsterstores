<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Shiprestriction
 */ 
class Amasty_Shiprestriction_Model_Mysql4_Rule extends Amasty_Commonrules_Model_Resource_Rule
{
    public function _construct()
    {
        $this->_type = 'amshiprestriction';
        parent::_construct();
    }
}