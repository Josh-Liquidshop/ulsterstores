<?php

$installer = $this;
$installer->startSetup();

if (!$installer->columnExists('sales/order_grid', 'szy_qty')) {
    $installer->run("ALTER TABLE `{$this->getTable('sales/order_grid')}` ADD COLUMN `szy_qty` DECIMAL(10,4) NULL DEFAULT NULL;");
} else {
    $installer->run("ALTER TABLE `{$this->getTable('sales/order_grid')}` CHANGE `szy_qty` `szy_qty` DECIMAL( 10, 4 ) NULL DEFAULT NULL;");
}


$installer->endSetup();