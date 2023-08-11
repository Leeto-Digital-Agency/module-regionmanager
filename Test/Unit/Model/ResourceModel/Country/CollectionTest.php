<?php

namespace Leeto\RegionManager\Test\Unit\Model\ResourceModel\Country;

use Leeto\RegionManager\Model\ResourceModel\Country\Collection as CountryCollection;
use Leeto\RegionManager\Model\ResourceModel\Country\CollectionFactory;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @var CountryCollection
     */
    protected $countryCollection;

    protected function setUp(): void
    {
        // Create a mock for CollectionFactory
        $collectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Create a mock for CountryCollection
        $this->countryCollection = $this->getMockBuilder(CountryCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Return the mock CountryCollection when create() is called on CollectionFactory
        $collectionFactoryMock->method('create')->willReturn($this->countryCollection);
    }

    public function testCollection()
    {
        $this->countryCollection->method('toOptionArray')->willReturn(['1' => 'US']);
        $this->countryCollection->expects($this->atLeastOnce())->method('toOptionArray');
        $this->assertSame(['1' => 'US'], $this->countryCollection->toOptionArray());
    }
}
