<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Controller\Adminhtml\Offer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Benleneve\Offer\Model\OfferFactory;
use Benleneve\Offer\Model\ResourceModel\Offer as OfferResourceModel;

/**
 * Class Add Action
 */
class Add extends Action
{
    /**
     * @var OfferFactory
     */
    protected $offerFactory;

    /**
     * @var OfferResourceModel
     */
    private $offerResourceModel;

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param OfferFactory $offerFactory
     * @param OfferResourceModel $offerResourceModel
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        OfferFactory $offerFactory,
        OfferResourceModel $offerResourceModel,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->offerFactory = $offerFactory;
        $this->offerResourceModel = $offerResourceModel;
        $this->pageFactory = $pageFactory;
    }

    /**
     * Add or edit offer
     */
    public function execute()
    {
        $id = (int) $this->getRequest()->getParam('id');
        $resultPage = $this->pageFactory->create();
        $offerModel = $this->offerFactory->create();
        if ($id) {
            $this->offerResourceModel->load($offerModel, $id);
        }
        $resultPage->getConfig()->getTitle()->prepend($id ? __('Edit Offer') : __('Add New Offer'));

        return $resultPage;
    }
}
