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
* File        mysql4-upgrade-0.1.1-0.1.2.php
* @category   Moogento
* @package    pickPack
* @copyright  Copyright (c) 2014 Moogento <info@moogento.com> / All rights reserved.
* @license    https://moogento.com/License.html
*/ 

$installer = $this;
$this->startSetup();
$this->getConnection()->resetDdlCache($this->getTable('sales/order_grid'));
if (!$installer->columnExists('sales/order_grid', 'szy_ebay_customer_id')) {
    $this->getConnection()->addColumn(
        $this->getTable('sales/order_grid'),
        'szy_ebay_customer_id',
        'VARCHAR(255) DEFAULT NULL'
    );
    $this->getConnection()->addKey(
        $this->getTable('sales/order_grid'),
        'szy_ebay_customer_id',
        'szy_ebay_customer_id'
    );
}

if (!$installer->columnExists('sales/order_grid', 'szy_customer_email')) {
    $this->getConnection()->addColumn(
        $this->getTable('sales/order_grid'),
        'szy_customer_email',
        'varchar(255) default NULL'
    );
    $this->getConnection()->addKey(
        $this->getTable('sales/order_grid'),
        'szy_customer_email',
        'szy_customer_email'
    );

    //Left column: order_grid | right: order_table
    $select = $this->getConnection()->select();
    $select->join(
        array('order_table'=>$this->getTable('sales/order')),
        'order_table.entity_id = order_grid.entity_id',
        array('szy_customer_email' => 'customer_email')
    );

    $this->getConnection()->query(
        $select->crossUpdateFromSelect(
            array('order_grid' => $this->getTable('sales/order_grid'))
        )
    );
}

$this->endSetup();
