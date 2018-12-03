<?php

$config  = new Mage_Core_Model_Config();
$websiteImages = array();
foreach (Mage::app()->getWebsites() as $website) {
    if (Mage::getStoreConfig('moogento_shipeasy/grid/szy_website_id_' . $website->getCode() . '_logo')) {
        $websiteImages[$website->getCode()] = basename(Mage::getStoreConfig('moogento_shipeasy/grid/szy_website_id_' . $website->getCode() . '_logo'));
    };
}
$config->saveConfig('moogento_shipeasy/grid/szy_website_id_images', serialize($websiteImages));

$storeImages = array();
foreach (Mage::app()->getWebsites() as $website) {
    foreach ($website->getStores() as $store) {
        if (Mage::getStoreConfig('moogento_shipeasy/grid/szy_store_id_store_view_' . $store->getCode() . '_logo')) {
            $websiteImages[$store->getCode()] = basename(Mage::getStoreConfig('moogento_shipeasy/grid/szy_store_id_store_view_' . $store->getCode() . '_logo'));
        };
    }
}
$config->saveConfig('moogento_shipeasy/grid/szy_store_id_images', serialize($storeImages));

$groupImages = array();
foreach (Mage::app()->getWebsites() as $website) {
    foreach ($website->getGroups() as $group) {
        if (Mage::getStoreConfig('moogento_shipeasy/grid/szy_store_name_store_view_' . $group->getId() . '_logo')) {
            $websiteImages[$group->getId()] = basename(Mage::getStoreConfig('moogento_shipeasy/grid/szy_store_name_store_view_' . $group->getId() . '_logo'));
        };
    }
}
$config->saveConfig('moogento_shipeasy/grid/szy_store_name_images', serialize($groupImages));
