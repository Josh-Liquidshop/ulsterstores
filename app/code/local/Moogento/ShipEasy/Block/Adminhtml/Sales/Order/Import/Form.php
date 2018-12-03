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
 * File        Form.php
 *
 * @category   Moogento
 * @package    pickPack
 * @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
 * @license    https://moogento.com/License.html
 */
class Moogento_ShipEasy_Block_Adminhtml_Sales_Order_Import_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected $_preset = null;

    public function __construct()
    {
        parent::__construct();
        $this->setId('track_import_form');
        $this->setTitle(Mage::helper('moogento_shipeasy')->__('Process : CSV'));
    }

    protected function _getPreset($id)
    {
        if (is_null($this->_preset)) {

            $this->_preset = array();

            for ($i = 1; $i <= 3; $i++) {
                if ($i == 1) {
                    $configSuffix = 'szy_custom_attribute_preset';
                } else if ($i == 2) {
                    $configSuffix = 'szy_custom_attribute2_preset';
                } else {
                    $configSuffix = 'szy_custom_attribute3_preset';
                }

                $configPresets = Mage::getStoreConfig('moogento_shipeasy/grid/' . $configSuffix);
                $configPresets = explode("\n", $configPresets);

                $presets = array();
                foreach ($configPresets as $preset) {
                    $preset = trim($preset);
                    if (empty($preset)) {
                        continue;
                    }

                    if (strpos($preset, '|') !== false) {
                        list($label, $color) = explode('|', $preset);
                        $presets[ $label ] = $label;
                    } else {
                        $presets[ $preset ] = $preset;
                    }
                }
                $presets['custom'] = 'New Value';

                $this->_preset[ $i ] = $presets;
            }


        }

        return $this->_preset[ $id ];
    }

    protected function _prepareForm()
    {

        $form = new Moogento_ShipEasy_Block_Adminhtml_Widget_Data_Form(
            array(
                'id'      => 'edit_form',
                'action'  => $this->getUrl('adminhtml/system_convert_shipments/post'),
                'method'  => 'post',
                'enctype' => 'multipart/form-data',
                'target'  => '_blank'
            )
        );

        $fieldset = $form->addFieldset(
            'fieldset_main',
            array(
                'legend' => Mage::helper('moogento_shipeasy')->__('Process Orders : CSV'),
                'class'  => 'fieldset-wide import_shipment'
            )
        );

        $fieldset->addField(
            'import_file',
            'file',
            array(
                'name'  => 'import_file',
                'label' => Mage::helper('moogento_shipeasy')->__('Upload CSV File :'),
                'note'  => Mage::helper('moogento_shipeasy/import')->getFieldNote(),
            )
        );

        $actionField = $fieldset->addField('action', 'select', array(
            'name'    => 'action',
            'label'   => 'Action',
            'options' => array(
                'update'              => 'Update order',
                'ship'          => 'Ship',
                'invoice'       => 'Invoice',
                'ship_invoice'  => 'Ship & Invoice',
                'change_status' => 'Change status'
            )
        ));
        $statuses    = Mage::getSingleton('sales/order_config')->getStatuses();
        $statusField = $fieldset->addField('status', 'select', array(
            'name'    => 'status',
            'label'   => Mage::helper('moogento_shipeasy')->__('Change status to:'),
            'options' => $statuses,
        ));

        $fieldset->addField('notify_customer', 'select', array(
            'name'    => 'notify_customer',
            'label'   => 'Notify Customer',
            'options' => array(
                0 => 'No',
                1 => 'Yes'
            )
        ));

        for ($i = 1; $i <= 3; $i++) {
            $attributeName = Mage::getStoreConfig("moogento_shipeasy/grid/szy_custom_attribute_header");
            if ($i == 2) {
                $attributeName = Mage::getStoreConfig("moogento_shipeasy/grid/szy_custom_attribute2_header");
            } else if ($i == 3) {
                $attributeName = Mage::getStoreConfig("moogento_shipeasy/grid/szy_custom_attribute3_header");
            }

            $fieldset->addField('additional_action_' . $i, 'select', array(
                'name'    => 'additional_action_' . $i,
                'label'   => 'Update Attribute "' . $attributeName . '"',
                'options' => array(
                    1 => 'Yes',
                    0 => 'No',
                ),
                'class'   => 'szy_attribute',
            ));

            $fieldset->addField('attr_preset_' . $i, 'select', array(
                'label'   => 'Attribute "' . $attributeName . '" Value',
                'name'    => 'attr_preset_' . $i,
                'options' => $this->_getPreset($i),
                'class'   => 'szy_attribute_preset',
            ));

            $fieldset->addField('custom_value_' . $i, 'text', array(
                'label' => 'Attribute "' . $attributeName . '" Custom Value',
                'name'  => 'attr_custom_value_' . $i,
                // 'style'     => 'width: 280px !important',
                'class' => 'szy_attribute_preset_new',
            ));

        }

        $form->setUseContainer(true);
        $this->setForm($form);

        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                                           ->addFieldMap($actionField->getHtmlId(), $actionField->getName())
                                           ->addFieldMap($statusField->getHtmlId(), $statusField->getName())
                                           ->addFieldDependence(
                                               $statusField->getName(),
                                               $actionField->getName(),
                                               'change_status'
                                           )
        );

        return parent::_prepareForm();

    }
}
