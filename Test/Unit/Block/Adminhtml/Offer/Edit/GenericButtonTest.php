<?php

namespace Benleneve\Offer\Test\Unit\Block\Adminhtml\Offer\Edit;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Benleneve\Offer\Block\Adminhtml\Offer\Edit\GenericButton;

class GenericButtonTest extends TestCase
{
    /**
     * @var GenericButton
     */
    protected $sampleClass;

    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->sampleClass = $objectManager->getObject(GenericButton::class);
    }

    public function testClassInstantiation(): void
    {
        self::assertInstanceOf(GenericButton::class, $this->sampleClass);
    }
}
