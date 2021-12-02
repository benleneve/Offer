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
use Magento\PageCache\Model\Cache\Type as PageCacheType;
use Benleneve\Offer\Model\Offer;

/**
 * Class Delete Action
 */
class Delete extends Action
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
     * Delete offer
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $id = $this->getRequest()->getParam('id');
        $offer = $this->_objectManager->create(Offer::class)->load($id);
        /** @var Offer $offer */
        if ($offer) {
            try {
                $offer->delete();
                // clean cache to for html regeneration
                $this->cacheTypeList->cleanType(PageCacheType::TYPE_IDENTIFIER);
                $this->messageManager->addSuccessMessage(__('Offer deleted'));
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred during deleting process'));
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}
