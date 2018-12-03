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
* File        Observer.php
* @category   Moogento
* @package    pickPack
* @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
* @license    https://moogento.com/License.html
*/ 


class Moogento_ShipEasy_Model_Dataflow_Profile_Observer
{
    public function addFilterToProfileCollection($observer)
    {
        $collection = $observer->getCollection();
        if (
            ($collection instanceof Mage_Dataflow_Model_Mysql4_Profile_Collection)
            || ($collection instanceof Mage_Dataflow_Model_Resource_Profile_Collection)
        ) {
            $collection->addFieldToFilter(
                'profile_id',
                array(
                    'neq' => 0
                )
            );
        }
    }
}
