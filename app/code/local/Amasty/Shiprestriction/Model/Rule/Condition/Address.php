<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Shiprestriction
 */
class Amasty_Shiprestriction_Model_Rule_Condition_Address extends Amasty_Commonrules_Model_Rule_Condition_Address
{
    public function loadAttributeOptions()
    {
        parent::loadAttributeOptions();
        
        $attributes = $this->getAttributeOption();
        unset($attributes['shipping_method']);
        $attributes['street'] = Mage::helper('salesrule')->__('Address Line');
        $attributes['city'] = Mage::helper('salesrule')->__('City');
        
        $this->setAttributeOption($attributes);

        return $this;
    }
    
    public function getOperatorSelectOptions()
    {
        $operators = $this->getOperatorOption();
        if ($this->getAttribute() == 'street') {
             $operators = array(
                '{}'  => Mage::helper('rule')->__('contains'),
                '!{}' => Mage::helper('rule')->__('does not contain'),             
             );
        }
        
        return parent::_getOperatorOptions($operators);
    }
          
}