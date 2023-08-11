<?php

namespace Leeto\RegionManager\Test\Unit\Ui\DataProvider;

use PHPUnit\Framework\TestCase;
use Leeto\RegionManager\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Leeto\RegionManager\Model\ResourceModel\Region\Collection as RegionCollection;
use Leeto\RegionManager\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;
use Leeto\RegionManager\Model\ResourceModel\Country\Collection as CountryCollection;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Leeto\RegionManager\Ui\DataProvider\CountryForm;

class CountryFormTest extends TestCase
{
    /**
     * @var MockObject|RegionCollectionFactory
     */
    protected $regionCollectionFactoryMock;

    /**
     * @var MockObject|CountryCollectionFactory
     */
    protected $countryCollectionFactoryMock;

    /**
     * @var MockObject|RegionCollection
     */
    protected $regionCollectionMock;

    /**
     * @var MockObject|CountryCollection
     */
    protected $countryCollectionMock;

    /**
     * @var CountryForm
     */
    protected $countryForm;

    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->regionCollectionFactoryMock = $this->createMock(RegionCollectionFactory::class);
        $this->countryCollectionFactoryMock = $this->createMock(CountryCollectionFactory::class);
        $this->regionCollectionMock = $this->createMock(RegionCollection::class);
        $this->countryCollectionMock = $this->createMock(CountryCollection::class);
        $this->regionCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->regionCollectionMock);
        $this->countryCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->countryCollectionMock);

        $this->countryForm = $objectManager->getObject(
            CountryForm::class,
            [
                'name' => 'CountryForm',
                'primaryFieldName' => 'country_id',
                'requestFieldName' => 'country_id',
                'collectionFactory' => $this->countryCollectionFactoryMock,
                'regionCollection' => $this->regionCollectionFactoryMock
            ]
        );
    }

    /**
     * Test for method getData
     */
    public function testGetData()
    {
        $countryCollection = [
            $this->getCountryMock(),
            $this->getCountryMock()
        ];

        $regionCollection = [
            $this->getRegionMock(),
            $this->getRegionMock()
        ];

        $this->countryCollectionMock->expects($this->once())
            ->method('getItems')
            ->willReturn($this->countryCollectionMock);
        $this->countryCollectionMock->expects($this->atLeastOnce())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($countryCollection));

        $this->regionCollectionMock->expects($this->atLeastOnce())
            ->method('addFieldToFilter')
            ->willReturn($this->regionCollectionMock);
        $this->regionCollectionMock->expects($this->atLeastOnce())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($regionCollection));

        $this->countryForm->getData();
    }

    /**
     * Create Country Collection Mock
     *
     * @return Leeto\RegionManager\Model\ResourceModel\Country\Collection|MockObject
     */
    protected function getCountryMock()
    {
        $countryMock = $this->getMockBuilder(CountryCollection::class)
            ->onlyMethods(['getData'])
            ->addMethods(['getCountryId', 'setData', 'getId'])
            ->disableOriginalConstructor()
            ->getMock();
        $countryMock->expects($this->atLeastOnce())->method('getCountryId')->willReturn($countryMock);
        $countryMock->expects($this->atLeastOnce())->method('getId')->willReturn(1);
        $countryMock->expects($this->atLeastOnce())->method('getData')->willReturn($countryMock);

        return $countryMock;
    }

    /**
     * Create Region Collection Mock
     *
     * @return Leeto\RegionManager\Model\ResourceModel\Region\Collection|MockObject
     */
    protected function getRegionMock()
    {
        $regionMock = $this->getMockBuilder(RegionCollection::class)
            ->addMethods(['getCode', 'getDefaultName', 'getRegionId', 'getCountryId'])
            ->disableOriginalConstructor()
            ->getMock();
        $regionMock->expects($this->atLeastOnce())->method('getCode')->willReturn($regionMock);
        $regionMock->expects($this->atLeastOnce())->method('getDefaultName')->willReturn($regionMock);
        $regionMock->expects($this->atLeastOnce())->method('getRegionId')->willReturn($regionMock);
        $regionMock->expects($this->atLeastOnce())->method('getCountryId')->willReturn($regionMock);

        return $regionMock;
    }
}
