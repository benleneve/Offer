<?php

namespace Benleneve\Offer\Test\Unit\Block\Adminhtml\Offer\Edit;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Benleneve\Offer\Block\Adminhtml\Offer\Edit\BackButton;

class BackButtonTest extends TestCase
{
    /**
     * @var BackButton
     */
    protected $sampleClass;

    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->sampleClass = $objectManager->getObject(BackButton::class);
    }

    public function testClassInstantiation(): void
    {
        self::assertInstanceOf(BackButton::class, $this->sampleClass);
    }
}
