<?php

namespace Leeto\RegionManager\Test\Unit\Model\ResourceModel\Region;

use Leeto\RegionManager\Model\ResourceModel\Region\Collection as RegionCollection;
use Leeto\RegionManager\Model\ResourceModel\Region\CollectionFactory;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @var RegionCollection
     */
    protected $regionCollection;

    protected function setUp(): void
    {
        // Create a mock for RegionFactory
        $collectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Create a mock for RegionCollection
        $this->regionCollection = $this->getMockBuilder(RegionCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Return the mock RegionCollection when create() is called on CollectionFactory
        $collectionFactoryMock->method('create')->willReturn($this->regionCollection);
    }

    public function testCollection()
    {
        $this->regionCollection->method('toOptionArray')->willReturn(['1' => 'US']);
        $this->regionCollection->expects($this->atLeastOnce())->method('toOptionArray');
        $this->assertSame(['1' => 'US'], $this->regionCollection->toOptionArray());
    }
}
