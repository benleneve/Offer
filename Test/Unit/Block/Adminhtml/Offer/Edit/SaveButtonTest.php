<?php

namespace Benleneve\Offer\Test\Unit\Block\Adminhtml\Offer\Edit;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Benleneve\Offer\Block\Adminhtml\Offer\Edit\SaveButton;

class SaveButtonTest extends TestCase
{
    /**
     * @var SaveButton
     */
    protected $sampleClass;

    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->sampleClass = $objectManager->getObject(SaveButton::class);
    }

    public function testClassInstantiation(): void
    {
        self::assertInstanceOf(SaveButton::class, $this->sampleClass);
    }

    public function testGetButtonData(): void
    {
        self::assertIsArray($this->sampleClass->getButtonData());
    }
}
