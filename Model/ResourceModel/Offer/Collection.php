<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Model\ResourceModel\Offer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Benleneve\Offer\Model\Offer;
use Benleneve\Offer\Model\ResourceModel\Offer as OfferResource;

/**
 * Class Offer Collection
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct(): void
    {
        $this->_init(Offer::class, OfferResource::class);
    }
}
