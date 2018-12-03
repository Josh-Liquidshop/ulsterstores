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
* File        mysql4-upgrade-0.1.17-0.1.18.php
* @category   Moogento
* @package    pickPack
* @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
* @license    https://moogento.com/License.html
*/ 

$installer = $this;
$installer->startSetup();


$select = $this->getConnection()->select();
$select->from(
    array('config_table' => $this->getTable('core/config_data')),
    array('value' => 'value')
)->where(
    'config_table.path = "moogento_shipeasy/carriers/base_format"'
);

$currentCarrierFormat = $this->getConnection()->fetchOne($select);
if ($currentCarrierFormat) {
    try {
        $currentCarrierFormat = Mage::helper('core/string')->unserialize($currentCarrierFormat);
        if (is_array($currentCarrierFormat)) {
            foreach($currentCarrierFormat as $key => $carrierConfig) {
                $currentCarrierFormat[$key]['file'] = 'carrier_icon_'.strtolower($carrierConfig['code']).'.png';
            }
            $this->getConnection()->update(
                array('config_table' => $this->getTable('core/config_data')),
                array('value' => serialize($currentCarrierFormat)),
                'path = "moogento_shipeasy/carriers/base_format"'
            );
        }
    } catch (Exception $e) {

    }
}

$installer->endSetup();
