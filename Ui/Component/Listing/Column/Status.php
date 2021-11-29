<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Benleneve\Offer\Model\Offer;

/**
 * Class Status
 */
class Status extends Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource row data source
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        $dataSource = parent::prepareDataSource($dataSource);
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[Offer::FIELD_IS_ACTIVE] = (int) $item[Offer::FIELD_IS_ACTIVE] === 0
                    ? __('Disabled')
                    : __('Enabled');
            }
        }
        return $dataSource;
    }
}
