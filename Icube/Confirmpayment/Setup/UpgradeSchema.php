<?php namespace Icube\Confirmpayment\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $tableName = 'icube_confirmpayment';
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.0') < 0) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable($tableName))
                ->addColumn(
                    'confirmpayment_id',
                    Table::TYPE_SMALLINT,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'ConfirmPayment ID'
                )
                ->addColumn('order_id', Table::TYPE_TEXT, 255, ['nullable' => false])
                ->addColumn('payment_from', Table::TYPE_TEXT, 255, ['nullable' => false], 'Payment From')
                ->addColumn('payment_to', Table::TYPE_TEXT, 255, ['nullable' => false], 'Payment To')
                ->addColumn('account_number', Table::TYPE_TEXT, 255, ['nullable' => false], 'Bank Account Number')
                ->addColumn('holder_name', Table::TYPE_TEXT, 255, ['nullable' => false], 'Holder Name')
                ->addColumn('image', Table::TYPE_TEXT, 255, ['nullable' => false], 'Image')
                ->addColumn('amount', Table::TYPE_SMALLINT, null, ['nullable' => false], 'Amount Transferred')
                ->addColumn('transfer_date', Table::TYPE_DATETIME, null, ['nullable' => false], 'Transfer Date')
                ->setComment('Icube Confirm Payment');

            $installer->getConnection()->createTable($table);
        }

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $installer->run("ALTER TABLE icube_confirmpayment ADD COLUMN time_stamp TIMESTAMP;");
        }

        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $installer->getConnection();
                $connection->modifyColumn(
                    $installer->getTable($tableName),
                    'amount',
                    [
                        'type' => Table::TYPE_DECIMAL,
                        'length' => '17,2',
                        'nullable' => false,
                        'comment' => 'Payment asmount'
                    ]
                );
            }

        }


        $installer->endSetup();
    }

}