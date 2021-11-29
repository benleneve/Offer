<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Model\ResourceModel;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Benleneve\Offer\Model\ImageUploader;
use Benleneve\Offer\Model\Offer as OfferModel;

/**
 * Class Offer Resource Model
 */
class Offer extends AbstractDb
{
    /**
     * @var File
     */
    private $file;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * Constructor
     *
     * @param Context $context
     * @param File $file
     * @param Filesystem $fileSystem
     */
    public function __construct(
        Context $context,
        File $file,
        Filesystem $fileSystem
    ) {
        parent::__construct($context);
        $this->file = $file;
        $this->fileSystem = $fileSystem;
    }

    /**
     * Initialize offer resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(OfferModel::TABLE_OFFER, OfferModel::FIELD_ID);
    }

    /**
     * @param AbstractModel $object
     *
     * @return $this
     */
    protected function _afterSave(AbstractModel $object): Offer
    {
        $this->saveCategories($object);

        return parent::_afterSave($object);
    }

    /**
     * @param AbstractModel $object
     *
     * @throws FileSystemException
     *
     * @return $this
     */
    protected function _afterDelete(AbstractModel $object): Offer
    {
        $this->deleteCategory((int) $object->getId());
        $this->deleteFeaturedImage($object);

        return parent::_afterSave($object);
    }

    /**
     * Get all categories for an offer
     *
     * @param OfferModel $offer

     * @return array
     */
    public function getCategories(OfferModel $offer): array
    {
        $offerCategoryTable = $this->getTable(OfferModel::TABLE_OFFER_CATEGORY);
        $dbConnection = $this->getConnection();
        $categories  = $dbConnection
            ->select()
            ->from($offerCategoryTable, OfferModel::FIELD_CATEGORY_ID)
            ->where('offer_id=?', (int)$offer->getId());

        return $dbConnection->fetchCol($categories);
    }

    /**
     * Manage insert and delete in table offers_category (used in _afterSave)
     *
     * @param AbstractModel $offer
     *
     * @return $this
     */
    private function saveCategories(AbstractModel $offer)
    {
        $offerCategoryTable = $this->getTable(OfferModel::TABLE_OFFER_CATEGORY);
        $dbConnection = $this->getConnection();
        $offerId = (int) $offer->getId();

        $newCategories = explode(',', $offer->getCategoriesId());
        $oldCategories = $offer->getCategories();

        // delete old categories
        $categoriesToDelete = array_diff($oldCategories, $newCategories);
        if (!empty($categoriesToDelete)) {
            foreach ($categoriesToDelete as $categoryId) {
                $this->deleteCategory($offerId, (int) $categoryId);
            }
        }

        // add new categories
        $categoriesToAdd = array_diff($newCategories, $oldCategories);
        if (!empty($categoriesToAdd)) {
            $data = [];
            foreach ($categoriesToAdd as $category) {
                $data[] = [
                    OfferModel::FIELD_OFFER_ID => $offerId,
                    OfferModel::FIELD_CATEGORY_ID => (int) $category
                ];
            }
            $dbConnection->insertMultiple($offerCategoryTable, $data);
        }

        return $this;
    }

    /**
     * Delete specific category (used in _afterDelete)
     *
     * @param int $offerId
     * @param int|null $categoryId
     */
    private function deleteCategory(int $offerId, int $categoryId = null): void
    {
        $offerCategoryTable = $this->getTable(OfferModel::TABLE_OFFER_CATEGORY);
        $dbConnection = $this->getConnection();
        $params = ['offer_id=?' => $offerId];
        if ($categoryId) {
            $params['category_id=?'] = $categoryId;
        }
        $dbConnection->delete($offerCategoryTable, $params);
    }

    /**
     * Delete image (used in _afterDelete)
     *
     * @param AbstractModel $offer
     *
     * @throws FileSystemException
     */
    private function deleteFeaturedImage(AbstractModel $offer): void
    {
        $mediaDirectory = $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
        $imagePath = $mediaDirectory->getAbsolutePath() . ImageUploader::IMAGE_PATH . '/' . $offer->getFeaturedImage();
        if ($this->file->isExists($imagePath))  {
            $this->file->deleteFile($imagePath);
        }
    }
}
