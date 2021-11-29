<?php

namespace Benleneve\Offer\Test\Unit\Model\Source;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Benleneve\Offer\Model\Config\Source\Status;

class StatusTest extends TestCase
{
    /**
     * @var Status
     */
    protected $sampleClass;

    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->sampleClass = $objectManager->getObject(Status::class);
    }

    public function testClassInstantiation(): void
    {
        self::assertInstanceOf(Status::class, $this->sampleClass);
    }

    public function testToOptionArray(): void
    {
        self::assertIsArray($this->sampleClass->toOptionArray());
    }
}
