<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Controller\Adminhtml\Offer;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Controller\Result\Redirect;
use Benleneve\Offer\Model\Offer;
use Magento\PageCache\Model\Cache\Type as PageCacheType;

/**
 * Class MassDelete Action
 */
class MassDelete extends Action
{
    /**
     * @var TypeListInterface
     */
    private $cacheTypeList;

    /**
     * Constructor
     *
     * @param Context $context
     * @param TypeListInterface $cacheTypeList
     */
    public function __construct(
        Context $context,
        TypeListInterface $cacheTypeList
    ) {
        parent::__construct($context);
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * Mass delete offer
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $deletedOffers = 0;
        $ids = $this->getRequest()->getParam('selected', []);
        if (!is_array($ids) || !count($ids)) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }
        foreach ($ids as $id) {
            /** @var Offer $offer */
            $offer = $this->_objectManager->create(Offer::class)->load($id);
            if ($offer) {
                try {
                    $offer->delete();
                    $deletedOffers++;
                } catch (Exception $e) {}
            }
        }
        if ($deletedOffers > 0) {
            // clean cache to for html regeneration
            $this->cacheTypeList->cleanType(PageCacheType::TYPE_IDENTIFIER);
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted', $deletedOffers));
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}
