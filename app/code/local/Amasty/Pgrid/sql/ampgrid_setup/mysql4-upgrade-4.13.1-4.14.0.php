<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Pgrid
 */


$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('ampgrid/grid_column')}` 
      ADD COLUMN `display_totals` TINYINT(1) DEFAULT 0;
    ALTER TABLE `{$this->getTable('ampgrid/grid_group_column')}` 
      ADD COLUMN `is_display_totals` TINYINT(1) DEFAULT 0;
    UPDATE `{$this->getTable('ampgrid/grid_column')}`
      SET `display_totals` = 1
      WHERE `code` IN ('price', 'qty', 'qty_sold');
    ALTER TABLE `{$this->getTable('ampgrid/grid_group_attribute')}` 
      ADD COLUMN `is_display_totals` TINYINT(1) DEFAULT 0;
    INSERT INTO `{$this->getTable('ampgrid/grid_column')}`
      (`code`, `title`, `column_type`, `editable`, `visible`, `display_totals`)
      VALUES ('backorders', 'Backorders', 'extra', 0, 1, 0);
");

$groupCollection = Mage::getModel('ampgrid/group')->getCollection();
$column = Mage::getModel('ampgrid/column')->getCollection()->addFieldToFilter('code', 'backorders')->getFirstItem();
if (0 < $groupCollection->getSize()
    && $column->getId()
) {
    foreach ($groupCollection as $group) {
        $this->run("
            INSERT INTO `{$this->getTable('ampgrid/grid_group_column')}`
              (`column_id`, `group_id`, `custom_title`, `is_editable`, `is_visible`, `is_display_totals`)
              VALUES ({$column->getId()}, {$group->getId()}, 'Backorders', 0, 1, 0);
        ");
    }
}

$this->endSetup();
