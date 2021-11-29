<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Setup;

use Exception;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Benleneve\Offer\Model\Offer;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup Magento schema setup instance
     * @param ModuleContextInterface $context Magento module context instance
     *
     * @throws Exception
     *
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        // create table benleneve_offer
        $tableName = $installer->getTable(Offer::TABLE_OFFER);
        if (!$installer->getConnection()->isTableExists($tableName)) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    Offer::FIELD_ID,
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true,
                    ],
                    'Offer id'
                )
                ->addColumn(
                    Offer::FIELD_LABEL,
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Offer label'
                )
                ->addColumn(
                    Offer::FIELD_CONTENT,
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Offer content'
                )
                ->addColumn(
                    Offer::FIELD_REDIRECT_URL,
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Offer redirect url'
                )
                ->addColumn(
                    Offer::FIELD_FROM_DATE,
                    Table::TYPE_TIMESTAMP,
                    null,
                    [],
                    'Offer from date'
                )->addColumn(
                    Offer::FIELD_TO_DATE,
                    Table::TYPE_TIMESTAMP,
                    null,
                    [],
                    'Offer to date'
                )->addColumn(
                    Offer::FIELD_IS_ACTIVE,
                    Table::TYPE_BOOLEAN,
                    null,
                    [
                        'nullable' => false,
                        'default' => 0,
                    ],
                    'Offer is active'
                )
                ->addColumn(
                    Offer::FIELD_FEATURED_IMAGE,
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Offer Featured Image'
                )
                ->addColumn(
                    Offer::FIELD_CREATED_AT,
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => Table::TIMESTAMP_INIT,
                    ],
                    'Offer Created At'
                )
                ->addColumn(
                    Offer::FIELD_UPDATED_AT,
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => Table::TIMESTAMP_INIT_UPDATE,
                    ],
                    'Offer Updated At'
                )
                ->setComment('Benleneve Offer table');
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $tableName,
                $setup->getIdxName(
                    $tableName,
                    [Offer::FIELD_LABEL, Offer::FIELD_FEATURED_IMAGE],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                [Offer::FIELD_LABEL, Offer::FIELD_FEATURED_IMAGE],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }

        // create table benleneve_offer_category
        $tableName = $installer->getTable(Offer::TABLE_OFFER_CATEGORY);
        if (!$installer->getConnection()->isTableExists($tableName)) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    Offer::FIELD_OFFER_ID,
                    TABLE::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                    ],
                    'Offer Id'
                )->addColumn(
                    Offer::FIELD_CATEGORY_ID,
                    TABLE::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                    ],
                    'Category Id'
                )
                ->addForeignKey(
                    $installer->getFkName(
                        Offer::TABLE_OFFER_CATEGORY,
                        Offer::FIELD_OFFER_ID,
                        Offer::TABLE_OFFER,
                        Offer::FIELD_ID
                    ),
                    Offer::FIELD_OFFER_ID,
                    $installer->getTable(Offer::TABLE_OFFER),
                    Offer::FIELD_ID,
                    Table::ACTION_CASCADE
                )
                ->setComment('Benleneve Offer Category table');
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
