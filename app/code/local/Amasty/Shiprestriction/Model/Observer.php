<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Shiprestriction
 */
class Amasty_Shiprestriction_Model_Observer extends Amasty_Commonrules_Model_Observer
{
    protected $_allRules = null;
    protected $_module = 'amshiprestriction';
    
    public function restrictRates($observer) 
    {
        $request = $observer->getRequest();
        $result  = $observer->getResult();

        $rates = $result->getAllRates();
        if (!count($rates)){
            return $this;
        }
            
        $rules = $this->_getRestrictionRules($request);
        if (!count($rules)){
             return $this;
        }
        
        $result->reset();
        
        $isEmptyResult = true;
        $lastError     = Mage::helper('amshiprestriction')->__('Sorry, no shipping quotes are available for the selected products and destination');
        $lastRate      = null;
        $isRestrict    = false;

        foreach ($rates as $rate){
            $isValid = true;
            foreach ($rules as $rule){
                if ($rule->restrict($rate)){
                    $lastRate  = $rate;
                    $lastError = $rule->getMessage();
                    $isValid   = false;
                    $isRestrict= true;
                    break;
                }
            }
            if ($isValid){
                $result->append($rate);
                $isEmptyResult = false;                    
            }
        }

        $isShowMessage = Mage::getStoreConfig('amshiprestriction/general/error_message');
        if ($isEmptyResult || ($isShowMessage && $isRestrict)){
            $error = Mage::getModel('shipping/rate_result_error');
            $error->setCarrier($lastRate->getCarrier());
            $error->setCarrierTitle($lastRate->getMethodTitle());
            $error->setErrorMessage($lastError);

            $result->append($error);
        }
        
        return $this;
    }
   
    protected function _getRestrictionRules($request)
    {
        $all = $request->getAllItems();
        if (!$all){
            return array();
        }
        $firstItem = current($all);
        $address = $firstItem->getAddress();
        if (!$address){
            $quote = $firstItem->getQuote();     
            if (!$quote) { return array(); } // we need it for true order editor

            $address = $quote->getShippingAddress(); 
        }
        $address->setItemsToValidateRestrictions($request->getAllItems());
        
       
        //multishipping optimization
        if (is_null($this->_allRules)){
            $this->_allRules = Mage::getModel('amshiprestriction/rule')
                ->getCollection()
                ->addAddressFilter($address)
                ->addIsAdminFilter()
                ->addBackorderFilter($all);
            
            $this->_allRules->load();
            foreach ($this->_allRules as $rule){
                $rule->afterLoad(); 
            }                
        }

	// remember old                 
        $subtotal = $address->getSubtotal();
        $baseSubtotal = $address->getBaseSubtotal();

        /** @var Amasty_Shiprestriction_Helper_Data $hlp */
        $hlp =  Mage::helper('amshiprestriction');

        // set new
        $includeTax = Mage::getStoreConfig('amshiprestriction/general/tax');
        $includeDiscount = Mage::getStoreConfig('amshiprestriction/general/discount');
        $hlp->modifySubtotal($address, $includeTax, $includeDiscount);


        $validRules = array();
        foreach ($this->_allRules as $rule) {
            $hlp->clearProducts();

            $codes = $this->getCouponCodes($request);
            if ($hlp->isCouponValid($rule, $codes)
                && !$hlp->isCouponValid($rule, $codes, true)
                && $rule->validate($address)
            ){
                // remember used products
                $newMessage = $hlp->parseMessage($rule->getMessage(), $hlp->getProducts());
                $rule->setMessage($newMessage);

                $validRules[] = $rule;
            }
        }

        // restore
        $address->setSubtotal($subtotal);
        $address->setBaseSubtotal($baseSubtotal);
        
        return $validRules;                
    }

    public function getCouponCodes($request)
    {
        if (!count($request->getAllItems()))
            return array();

        $firstItem = current($request->getAllItems());
        $codes = trim(strtolower($firstItem->getQuote()->getCouponCode()));

        if (!$codes)
            return array();

        $providedCouponCodes = explode(",",$codes);

        foreach ($providedCouponCodes as $key => $code){
            $providedCouponCodes[$key] = trim($code);
        }

        return $providedCouponCodes;

    }
}