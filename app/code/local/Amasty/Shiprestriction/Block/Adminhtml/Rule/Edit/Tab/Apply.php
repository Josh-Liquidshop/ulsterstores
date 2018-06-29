<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Shiprestriction
 */
class Amasty_Shiprestriction_Block_Adminhtml_Rule_Edit_Tab_Apply extends Amasty_Commonrules_Block_Adminhtml_Rule_Edit_Tab_Apply
{
    protected function _prepareForm()
    {
        $model = Mage::registry('amshiprestriction_rule');
        $this->_setRule($model);
        return parent::_prepareForm();
    }
}
