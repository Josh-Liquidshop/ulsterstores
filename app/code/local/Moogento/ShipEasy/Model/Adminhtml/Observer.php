<?php

class Moogento_ShipEasy_Model_Adminhtml_Observer
{
    public function controller_action_predispatch_adminhtml_system_config_save($observer)
    {
        $request = Mage::app()->getRequest();

        $section = $request->getParam('section');
        $config  = new Mage_Core_Model_Config();
        switch ($section) {
            case 'moogento_shipeasy':
                $groups   = $request->getPost('status_group_table', array());
                $newValue = array();
                foreach ($groups as $statusGroup) {
                    $statuses                         = (!empty($statusGroup['statuses'])
                                                         && count($statusGroup['statuses']))
                        ? $statusGroup['statuses']
                        : array();
                    $newValue[ $statusGroup['name'] ] = $statuses;
                }

                $config->saveConfig('moogento_shipeasy/grid/szy_status_status_group', serialize($newValue));

                $imageTables = array(
                    'images_table' => array(
                        'path' => 'channelunity',
                        'column' => 'channel_unity_origin'
                    ),
                    'website_images_table' => array(
                        'path' => 'szy_websites',
                        'column' => 'szy_website_id',
                    ),
                    'store_images_table' => array(
                        'path' => 'szy_groups',
                        'column' => 'szy_store_name',
                    ),
                    'storeview_images_table' => array(
                        'path' => 'szy_stores',
                        'column' => 'szy_store_id',
                    ),
                );

                foreach ($imageTables as $code => $settings) {
                    $optImages   = $request->getPost($code, array());
                    $newValue = array();
                    foreach ($optImages as $id => $optImage) {
                        if (!empty($_FILES[$code]['tmp_name'][$id]['image'])) {
                            $_FILES[$id]['name']     = $_FILES[$code]['name'][$id]['image'];
                            $_FILES[$id]['type']     = $_FILES[$code]['type'][$id]['image'];
                            $_FILES[$id]['tmp_name'] = $_FILES[$code]['tmp_name'][$id]['image'];
                            $_FILES[$id]['error']    = $_FILES[$code]['error'][$id]['image'];
                            $_FILES[$id]['size']     = $_FILES[$code]['size'][$id]['image'];

                            try {
                                $uploader = new Varien_File_Uploader($id);
                                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                                $uploader->setAllowRenameFiles(true);
                                $uploader->setAllowCreateFolders(true);
                                $uploader->save(Mage::getBaseDir('media').DS.'moogento/shipeasy/' . $settings['path']);
                                $filename = $uploader->getUploadedFileName();
                                $optImage['image'] = $filename;
                            } catch (Exception $e) {
                                throw $e;
                            }

                        } else {
                            if (!isset($optImage['remove_image'])) {
                                $optImage['image'] = isset($optImage['old_image']) ? $optImage['old_image'] : '';
                            } else {
                                $optImage['image'] = '';
                            }
                        }
                        $newValue[ $optImage['name'] ] = $optImage['image'];
                    }

                    $config->saveConfig('moogento_shipeasy/grid/' . $settings['column'] . '_images', serialize($newValue));
                }

                $groups = $request->getPost('method_group_table', array());
                $config->saveConfig('moogento_shipeasy/grid/szy_shipping_method_method_group', serialize($groups));

                $post_data = $request->getPost('groups');
                $post_data = (array) $post_data;
                if (!isset($post_data['grid']['fields']["backorder_transparent_status"])) {
                    $config->saveConfig('moogento_shipeasy/grid/backorder_transparent_status', '');
                }

                if (isset($post_data['general']['fields']['cron_period'])) {

                    $period = (int)$post_data['general']['fields']['cron_period']['value'];
                    if (!$period || $period == 1) {
                        $value = '* * * * *';
                    } else {
                        $value = '*/' . $period . ' * * * *';
                    }
                    foreach (array(
                        'moogento_shipeasy_mkt_order_id_update',
                        'moogento_shipeasy_timezone_update',
                        'moogento_shipeasy_ebay_items_links_update',
                        'moogento_shipeasy_fill_columns',
                        'moogento_shipeasy_fix_old_columns',
                        'moogento_shipeasy_fill_product_columns'
                    ) as $cronKey) {
                        $cronPath = 'crontab/jobs/' . $cronKey . '/schedule/cron_expr';

                        try {
                            Mage::getModel('core/config_data')
                                ->load($cronPath, 'path')
                                ->setValue($value)
                                ->setPath($cronPath)
                                ->save();
                        } catch (Exception $e) {}
                    }
                }
                
                $import_data = $request->getPost('import_settings') ? array_values($request->getPost('import_settings')) : array();
                if (!$import_data) {
                    $import_data = array();
                }
                $config->saveConfig('moogento_shipeasy/config/import_options', Mage::helper('core')->jsonEncode($import_data));
                break;
        }
    }
    
    public function customer_save_after($observer)
    {
        $customer = $observer->getCustomer();
        $new_email = $customer->getEmail();
        $old_email = $customer->getOrigData('email');

        if($new_email != $old_email){
            $orders = Mage::getResourceModel('sales/order_grid_collection')->addFilter('main_table.customer_id', $customer->getId());
            foreach ($orders as $order){
                $customer_email_list = $order->getCustomerEmailList();
                if(strpos($customer_email_list, $new_email) === false){
                    $customer_email_list = $new_email.' '.$customer_email_list;
                } else {
                    $customer_email_list = $new_email.' '.str_replace($new_email, "", $customer_email_list);
                }
                $order->setCustomerEmailList($customer_email_list);
                Mage::helper('moogento_shipeasy/sales')->updateOnlyOrderGrigAttribute($order->getId(),'customer_email_list',$customer_email_list);
            }
        }
    }
}
