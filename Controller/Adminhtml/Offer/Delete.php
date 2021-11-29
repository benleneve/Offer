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
 * Class Delete Action
 */
class Delete extends Action
{
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
                $this->messageManager->addSuccessMessage(__('Offer deleted.'));
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred during deleting process.'));
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}
