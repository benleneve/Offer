<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Controller\Adminhtml\Offer;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Benleneve\Offer\Model\Offer;

/**
 * Class MassDelete Action
 */
class MassDelete extends Action
{
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
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted', $deletedOffers));
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}
