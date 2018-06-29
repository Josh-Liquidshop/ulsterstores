<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Shiprestriction
 */ 
class Amasty_Shiprestriction_Model_Rule extends Amasty_Commonrules_Model_Rule
{
    public function _construct()
    {
        $this->_type = 'amshiprestriction';
        parent::_construct();
    }
    
    public function restrict($rate)
    {
        if (false !== strpos($this->getCarriers(), ',' . $rate->getCarrier(). ','))
            return true;
        
        $m = $this->getMethods();    
        $m = str_replace("\r\n", "\n", $m);
        $m = str_replace("\r", "\n", $m);
        $m = str_replace("(", "\(", $m);
        $m = str_replace(")", "\)", $m);
        $m = trim($m);
        if (!$m){
            return false;
        }
        
        $m = array_unique(explode("\n", $m));
        foreach ($m as $pattern){
            $pattern = explode('::', $pattern);
            $patternCount = count($pattern);

            $rateCarrier = $rate->getCarrier();
            $rateMethod = $rate->getMethodTitle();

            if ($patternCount == 1) { //method compare
                $method = trim($pattern[0]);
                $posMethod = stripos($rateMethod, $method);
                if ($posMethod !== false) {
                    return true;
                }
            } elseif ($patternCount >= 3) { //carrier::regular_expression::regex
                if ($pattern[2] == 'regex') {
                    $carrier = $pattern[0];
                    $carrierPattern = '/' . preg_quote(trim($carrier)) . '/i';
                    $pattern = $pattern[1];
                    if (preg_match($pattern, $rateMethod) && preg_match($carrierPattern, $rateCarrier)){
                        return true;
                    }
                } elseif ($pattern[2] === '') {
                    $carrier = $pattern[0];
                    $posCarrier = stripos($rateCarrier, $carrier);
                    $method = $pattern[1];
                    if ($posCarrier !== false && $method == $rateMethod){
                        return true;
                    }
                } else {
                    $patternCount = 2;
                }
            }

            if ($patternCount == 2) { //carrier::method
                $carrier = $pattern[0];
                $posCarrier = stripos($rateCarrier, $carrier);
                $method = $pattern[1];
                $posMethod = stripos($rateMethod, $method);
                if ($posCarrier !== false && $posMethod !== false){
                    return true;
                }
            }

        }
        return false;
    }
}
