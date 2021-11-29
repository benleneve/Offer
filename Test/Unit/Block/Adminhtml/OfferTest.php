<?php

namespace Benleneve\Offer\Test\Unit\Block\Adminhtml;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Benleneve\Offer\Block\Offer;

class OfferTest extends TestCase
{
    /**
     * @var Offer
     */
    protected $sampleClass;

    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->sampleClass = $objectManager->getObject(Offer::class);
    }

    public function testClassInstantiation(): void
    {
        self::assertInstanceOf(Offer::class, $this->sampleClass);
    }

    public function testHasActiveOffer(): void
    {
        self::assertIsBool($this->sampleClass->hasActiveOffer());
    }
}
