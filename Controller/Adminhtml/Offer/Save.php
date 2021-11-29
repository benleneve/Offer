<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Controller\Adminhtml\Offer;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Benleneve\Offer\Model\ImageUploader;
use Benleneve\Offer\Model\Offer;
use Benleneve\Offer\Model\OfferFactory;

/**
 * Class Save Action
 */
class Save extends Action
{
    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var OfferFactory
     */
    private $offerFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param ImageUploader $imageUploader
     * @param OfferFactory $gridFactory
     */
    public function __construct(
        Context $context,
        ImageUploader $imageUploader,
        OfferFactory $offerFactory
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
        $this->offerFactory = $offerFactory;
    }

    /**
     * Save offer
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $data = $this->getRequest()->getParam('offers');
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$data) {
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }
        try {
            $offer = $this->offerFactory->create();
            // fix - failed to Parse Time String at Position 0 in Magento 2
            $data[Offer::FIELD_FROM_DATE] = $this->cleanDate($data[Offer::FIELD_FROM_DATE]);
            $data[Offer::FIELD_TO_DATE] = $this->cleanDate($data[Offer::FIELD_TO_DATE]);
            // get category ids
            $data['categories_id'] = implode(',' , $data['categories']);
            // upload of new image
            if (isset(
                $data[Offer::FIELD_FEATURED_IMAGE][0]['name'],
                $data[Offer::FIELD_FEATURED_IMAGE][0]['tmp_name']
            )) {
                $data[Offer::FIELD_FEATURED_IMAGE] = $data[Offer::FIELD_FEATURED_IMAGE][0]['name'];
                $this->imageUploader->moveFileFromTmp($data[Offer::FIELD_FEATURED_IMAGE]);
            } elseif (isset($data[Offer::FIELD_FEATURED_IMAGE][0]['name'])
                && !isset($data[Offer::FIELD_FEATURED_IMAGE][0]['tmp_name'])
            ) {
                $data[Offer::FIELD_FEATURED_IMAGE] = $data[Offer::FIELD_FEATURED_IMAGE][0]['name'];
            } else {
                $data[Offer::FIELD_FEATURED_IMAGE] = '';
            }
            // set all data in offer
            $offer->setData($data);
            if (isset($data[Offer::FIELD_ID])) {
                $offer->setEntityId($data[Offer::FIELD_ID]);
            }
            $offer->save();
            $this->messageManager->addSuccessMessage(__('Offer has been successfully saved'));
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('An error occurred during saving process'));
        }

        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }

    /**
     * Clean date format
     *
     * @param string $date
     *
     * @return string
     */
    private function cleanDate(string $date): string
    {
        $date = str_replace('/', '-', $date);

        return date('Y-m-d', strtotime($date));
    }

}
