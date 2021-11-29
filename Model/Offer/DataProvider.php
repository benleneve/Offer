<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Model\Offer;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Benleneve\Offer\Model\ImageUploader;
use Benleneve\Offer\Model\Offer;
use Benleneve\Offer\Model\ResourceModel\Offer\CollectionFactory as OfferCollectionFactory;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * DataProvider constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param OfferCollectionFactory $offerCollectionFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        OfferCollectionFactory $offerCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, [], []);
        $this->collection = $offerCollectionFactory->create();
        $this->storeManager = $storeManager;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array
    {
        $offers = $this->collection->getItems();
        /** @var Offer $offer */
        foreach ($offers as $offer) {
            $offerData = $offer->getData();
            // load category data
            $offerData['categories'] = $offer->getCategories();
            // load image data
            if (isset($offerData[Offer::FIELD_FEATURED_IMAGE]) && $offerData[Offer::FIELD_FEATURED_IMAGE] !== '') {
                $name = $offerData[Offer::FIELD_FEATURED_IMAGE];
                unset($offerData[Offer::FIELD_FEATURED_IMAGE]);

                // TODO catch Exception
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $offerData[Offer::FIELD_FEATURED_IMAGE][] = [
                    'name' => $name,
                    'url' => $mediaUrl . ImageUploader::IMAGE_PATH . '/' . $name,
                ];
            }
            $this->data[$offer->getId()]['offers'] = $offerData;
        }
        return $this->data;
    }
}
