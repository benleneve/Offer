<?php

namespace Benleneve\Offer\Test\Unit\Block\Adminhtml\Offer\Edit;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Benleneve\Offer\Block\Adminhtml\Offer\Edit\DeleteButton;

class DeleteButtonTest extends TestCase
{
    /**
     * @var DeleteButton
     */
    protected $sampleClass;

    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->sampleClass = $objectManager->getObject(DeleteButton::class);
    }

    public function testClassInstantiation(): void
    {
        self::assertInstanceOf(DeleteButton::class, $this->sampleClass);
    }
}
