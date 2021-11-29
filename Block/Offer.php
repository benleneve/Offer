<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Block;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Benleneve\Offer\Model\ImageUploader;
use Benleneve\Offer\Model\Offer as OfferModel;
use Benleneve\Offer\Model\ResourceModel\Offer\Collection as OfferCollection;

/**
 * Class Offer
 */
class Offer extends Template
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var OfferModel
     */
    private $offerModel;

    /**
     * @var OfferCollection|null
     */
    private $offers;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param OfferModel $offerModel
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        OfferModel $offerModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->offerModel = $offerModel;
        $this->loadActiveOffers();
    }

    /**
     * Current category has active offers
     *
     * @return bool
     */
    public function hasActiveOffer(): bool
    {
        return !empty($this->offers);
    }

    /**
     * Get all active offer for current page
     *
     * @return OfferCollection
     */
    public function getActiveOffers(): ?OfferCollection
    {
        return $this->offers;
    }

    /**
     * Return Image Folder Path
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getMediaPath(): string
    {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl . ImageUploader::IMAGE_PATH . '/';
    }

    /**
     * Load active offers for current page
     */
    private function loadActiveOffers(): void
    {
        $product = $this->getCurrentProduct();
        if ($product) {
            // get current page category
            $categoryIds = $product->getCategoryIds();
            if (!empty($categoryIds)) {
                $this->offers = $this->offerModel->getActiveOffersByCategoryId($categoryIds);
            }
        }
    }

    /**
     * Current category has active offers
     *
     * @return mixed|null
     */
    private function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }
}
