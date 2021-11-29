<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Benleneve\Offer\Controller\Adminhtml\Offer;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Benleneve\Offer\Model\ImageUploader;
use Benleneve\Offer\Model\Offer;

/**
 * Class Upload Action
 */
class Upload extends Action implements HttpPostActionInterface
{
    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * Constructor
     *
     * @param Context $context
     * @param ImageUploader $imageUploader
     */
    public function __construct(Context $context, ImageUploader $imageUploader)
    {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Upload file controller action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $image = $this->_request->getParam('param_name', Offer::FIELD_FEATURED_IMAGE);

        try {
            $result = $this->imageUploader->saveFileToTmpDir($image);
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
