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
 * File        Flag.php
 *
 * @category   Moogento
 * @package    pickPack
 * @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
 * @license    https://moogento.com/License.html
 */
class Moogento_ShipEasy_Block_Adminhtml_Directory_Country_Flag extends Mage_Adminhtml_Block_Template
{
    protected $_countryModel = null;

    protected function _getCountryImageUrl()
    {
        if (!$this->getCountryId()) {
            $country_id = 'null';
        } else {
            $country_id = strtolower($this->getCountryId());
        }

        return Mage::getDesign()->getSkinUrl('moogento/shipeasy/images/flags/') . $country_id . '.png';
    }

    protected function _getCountryTitle()
    {
        if ($this->getCountryId() && $this->getCountryId() != '') {
            if (is_null($this->_countryModel)) {
                $this->_countryModel = Mage::getModel('directory/country')->loadByCode($this->getCountryId());
            }
            $result = '';
            if ($this->_countryModel->getId()) {
                $result = $this->_countryModel->getName();
            }
        } else {
            $result = 'null';
        }

        return $result;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('moogento/shipeasy/directory/country/flag.phtml');
    }
}
