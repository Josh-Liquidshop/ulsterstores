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
 * File        Order.php
 *
 * @category   Moogento
 * @package    pickPack
 * @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
 * @license    https://moogento.com/License.html
 */
class Moogento_ShipEasy_Block_Adminhtml_System_Config_Grid_Column_Form_Order
    extends Mage_Adminhtml_Block_System_Config_Form
{

    protected function _getOrderStatutesInfo()
    {
        $statuses = Mage::getSingleton('sales/order_config')->getStatuses();
        $xml      = '<config><groups><colors><fields>';
        $counter  = 0;
        foreach ($statuses as $statusCode => $statusLabel) {
            $statusCode = strtolower(str_replace(' ', '_', $statusCode));
            $counter++;
            $xml .= '<' . $statusCode . '>';
            $xml .= "<label><![CDATA[" . $statusLabel . "]]></label>";
            $xml .= '<frontend_type>text</frontend_type>';
            $xml .= '<frontend_model>moogento_shipeasy/colorpicker</frontend_model>';
            $xml .= '<sort_order>' . $counter . '</sort_order>';
            $xml .= "<show_in_default>1</show_in_default>";
            $xml .= "<show_in_website>0</show_in_website>";
            $xml .= "<show_in_store>0</show_in_store>";
            $xml .= '</' . $statusCode . '>';
        }
        $counter++;
        $xml .= '<hover_highlight>';
        $xml .= "<label><![CDATA[Hover Highlight Percent]]></label>";
        $xml .= '<frontend_type>text</frontend_type>';
        $xml .= '<sort_order>' . $counter . '</sort_order>';
        $xml .= "<show_in_default>1</show_in_default>";
        $xml .= "<show_in_website>0</show_in_website>";
        $xml .= "<show_in_store>0</show_in_store>";
        $xml .= '</hover_highlight>';
        $xml .= '</fields></colors></groups></config>';

        return $xml;
    }

    public function initForm()
    {
        $this->_initObjects();

        $form = new Varien_Data_Form();

        $sections = $this->_configFields->getSection(
            $this->getSectionCode(), $this->getWebsiteCode(), $this->getStoreCode()
        );

        $statusesXml = $this->_getOrderStatutesInfo();
        try {
            $sections->extend(
                new Varien_Simplexml_Element($statusesXml)
            );
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::log($statusesXml, null, 'szy.log');
        }

        if (empty($sections)) {
            $sections = array();
        }
        foreach ($sections as $section) {
            /* @var $section Varien_Simplexml_Element */
            if (!$this->_canShowField($section)) {
                continue;
            }

            foreach ($section->groups as $groups) {


                $groups = (array)$groups;
                usort($groups, array($this, '_sortForm'));

                foreach ($groups as $group) {

                    /* @var $group Varien_Simplexml_Element */
                    if (!$this->_canShowField($group)) {
                        continue;
                    }

                    if ($group->frontend_model) {
                        $fieldsetRenderer = Mage::getBlockSingleton((string)$group->frontend_model);
                    } else {
                        $fieldsetRenderer = $this->_defaultFieldsetRenderer;
                    }

                    $fieldsetRenderer->setForm($this);
                    $fieldsetRenderer->setConfigData($this->_configData);
                    $fieldsetRenderer->setGroup($group);

                    if ($this->_configFields->hasChildren($group, $this->getWebsiteCode(), $this->getStoreCode())) {

                        $helperName = $this->_configFields->getAttributeModule($section, $group);

                        $fieldsetConfig = array('legend' => Mage::helper($helperName)->__((string)$group->label));
                        if (!empty($group->comment)) {
                            $fieldsetConfig['comment'] = (string)$group->comment;
                        }
                        if (!empty($group->expanded)) {
                            $fieldsetConfig['expanded'] = (bool)$group->expanded;
                        }

                        $fieldset = $form->addFieldset(
                            $section->getName() . '_' . $group->getName(), $fieldsetConfig
                        )
                            ->setRenderer($fieldsetRenderer);
                        $this->_prepareFieldOriginalData($fieldset, $group);
                        $this->_addElementTypes($fieldset);

                        if ($group->clone_fields) {
                            if ($group->clone_model) {
                                $cloneModel = Mage::getModel((string)$group->clone_model);
                            } else {
                                Mage::throwException(
                                    'Config form fieldset clone model required to be able to clone fields'
                                );
                            }
                            foreach ($cloneModel->getPrefixes() as $prefix) {
                                $this->initFields($fieldset, $group, $section, $prefix['field'], $prefix['label']);
                            }
                        } else {
                            $this->initFields($fieldset, $group, $section);
                        }

                        $this->_fieldsets[$group->getName()] = $fieldset;

                    }
                }
            }
        }
        $this->setForm($form);

        return $this;
    }

}
