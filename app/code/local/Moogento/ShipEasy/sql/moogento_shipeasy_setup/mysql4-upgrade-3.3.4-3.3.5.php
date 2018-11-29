<?php

$installer = $this;
$installer->startSetup();

if (!$installer->columnExists('sales/order_grid', 'channel_unity_origin')) {
    $this->getConnection()->addColumn(
        $this->getTable('sales/order_grid'),
        'channel_unity_origin',
        Varien_Db_Ddl_Table::TYPE_TEXT.' default NULL'
    );
}
if (!$installer->columnExists('sales/order', 'channel_unity_origin')) {
    $this->getConnection()->addColumn(
        $this->getTable('sales/order'),
        'channel_unity_origin',
        Varien_Db_Ddl_Table::TYPE_TEXT.' default NULL'
    );
}
$installer->endSetup();