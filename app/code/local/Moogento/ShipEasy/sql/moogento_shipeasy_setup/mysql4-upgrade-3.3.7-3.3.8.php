<?php

$installer = $this;
$installer->startSetup();

if (!$installer->columnExists('sales/shipment', 'admin_user_id')) {
    $this->getConnection()->addColumn(
        $this->getTable('sales/shipment'),
        'admin_user_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT.' default NULL'
    );
}
$installer->endSetup();