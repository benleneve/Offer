<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Benleneve\Offer\Model\Offer;

/**
 * @codeCoverageIgnore
 */
class Uninstall implements UninstallInterface
{
    /**
     * Uninstall DB schema for a module
     *
     * @param SchemaSetupInterface $setup Magento schema setup instance
     * @param ModuleContextInterface $context Magento module context instance
     *
     * @return void
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $installer = $setup;
        $installer->startSetup();

        $installer->getConnection()->dropTable($installer->getTable(Offer::TABLE_OFFER_CATEGORY));

        $installer->getConnection()->dropTable($installer->getTable(Offer::TABLE_OFFER));

        $installer->endSetup();
    }
}
