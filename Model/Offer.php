<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Benleneve\Offer\Model\ResourceModel\Offer as OfferResourceModel;
use Benleneve\Offer\Model\ResourceModel\Offer\Collection as OfferCollection;
use Benleneve\Offer\Model\ResourceModel\Offer\CollectionFactory as OfferCollectionFactory;

/**
 * Class Offer
 */
class Offer extends AbstractModel
{
    /**
     * @var string Benleneve offer table name
     */
    public const TABLE_OFFER = 'benleneve_offer';

    /* Offer fields */
    public const FIELD_ID = 'id';
    public const FIELD_LABEL = 'label';
    public const FIELD_CONTENT = 'content';
    public const FIELD_REDIRECT_URL = 'redirect_url';
    public const FIELD_FROM_DATE = 'from_date';
    public const FIELD_TO_DATE = 'to_date';
    public const FIELD_IS_ACTIVE = 'is_active';
    public const FIELD_FEATURED_IMAGE = 'featured_image';
    public const FIELD_CREATED_AT = 'created_at';
    public const FIELD_UPDATED_AT = 'updated_at';

    /**
     * @var string Benleneve offer category table name
     */
    public const TABLE_OFFER_CATEGORY = 'benleneve_offer_category';

    /* Offer category fields */
    public const FIELD_OFFER_ID = 'offer_id';
    public const FIELD_CATEGORY_ID = 'category_id';

    /**
     * @var OfferResourceModel
     */
    private $offerResourceModel;

    /**
     * @var OfferCollectionFactory
     */
    private $offerCollectionFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param OfferResourceModel $offerResourceModel
     * @param OfferCollectionFactory $offerCollectionFactory
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        OfferResourceModel $offerResourceModel,
        OfferCollectionFactory $offerCollectionFactory,
        Registry $registry
    )
    {
        parent::__construct($context, $registry);
        $this->offerResourceModel = $offerResourceModel;
        $this->offerCollectionFactory = $offerCollectionFactory;
    }

    /**
     * Initialize offer model
     */
    protected function _construct(): void
    {
        $this->_init(OfferResourceModel::class);
    }

    /**
     * Get categories for an offer
     *
     * @return array
     */
    public function getCategories(): array
    {
        if (!$this->hasData('category_ids')) {
            $categoriesIds = $this->offerResourceModel->getCategories($this);
            $this->setData('category_ids', $categoriesIds);
        }

        return (array) $this->_getData('category_ids');
    }

    /**
     * Get all active offers by category ids
     *
     * @param array $categoryIds
     *
     * @return OfferCollection
     */
    public function getActiveOffersByCategoryId(array $categoryIds)
    {
        return $this->offerCollectionFactory->create()
            ->join(
                ['offer_category' => self::TABLE_OFFER_CATEGORY],
                'offer_category.offer_id=main_table.id',
                ['category_id' => self::FIELD_CATEGORY_ID]
            )
            ->addFieldToFilter('offer_category.category_id', ['in' => $categoryIds])
            ->addFieldToFilter('main_table.is_active', ['eq' => 1])
            ->addFieldToFilter('main_table.from_date', ['to' => time(), 'datetime' => true])
            ->addFieldToFilter('main_table.to_date', ['from' => time(), 'datetime' => true]);
    }
}
