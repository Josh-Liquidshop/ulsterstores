<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Shiprestriction
 */ 
class Amasty_Shiprestriction_Helper_Data extends Amasty_Commonrules_Helper_Data
{

    const STORAGE_KEY = 'amshiprestriction_products';
    
    
    public function getAllCarriers()
    {
        $carriers = array();
        foreach (Mage::getStoreConfig('carriers') as $code=>$config){
            if (!empty($config['title'])){
                $carriers[] = array('value'=>$code, 'label'=>$config['title'] . ' [' . $code . ']');
            }
        }  
        return $carriers;      
    }

    public function parseMessage($message, $products)
    {
        $allProducts = implode(', ', $products);
        $lastProduct = end($products);
        $newMessage = str_replace('{all-products}', $allProducts, $message);
        $newMessage = str_replace('{last-product}', $lastProduct, $newMessage);

        return $newMessage;
    }

    public function clearProducts()
    {
        Mage::unregister(self::STORAGE_KEY);
    }

    /**
     * @param $name string product name
     */
    public function addProduct($name)
    {
        $oldNames = $this->getProducts();
        if (!in_array($name, $oldNames)) {
            $oldNames[] = $name;
        }
        $this->_saveProducts($oldNames);

        return $this;
    }

    public function getProducts()
    {
        $names = Mage::registry(self::STORAGE_KEY);
        if (empty($names)) {
            $names = array();
        }

        return $names;
    }

    protected function _saveProducts($names)
    {
        Mage::unregister(self::STORAGE_KEY);
        Mage::register(self::STORAGE_KEY, $names);

        return $this;
    }

}