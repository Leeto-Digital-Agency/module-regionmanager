<?php

namespace Leeto\RegionManager\Test\Unit\Ui\Component\Listing\Grid\Column\Country;

use PHPUnit\Framework\TestCase;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\Country;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Leeto\RegionManager\Ui\Component\Listing\Grid\Column\Country\CountryName as CountryNameColumn;
use Leeto\RegionManager\Model\Country as RegionManagerCountryModel;

class CountryNameTest extends TestCase
{
    /**
     * @var MockObject|ContextInterface
     */
    protected $contextMock;

    /**
     * @var MockObject|UiComponentFactory
     */
    protected $uiComponentFactoryMock;

    /**
     * @var MockObject|CountryFactory
     */
    protected $countryFactoryMock;

    /**
     * @var MockObject|Country
     */
    protected $countryModelMock;

    /**
     * @var CountryNameColumn
     */
    protected $countryNameColumn;

    public function setUp(): void
    {
        $this->contextMock = $this->getMockForAbstractClass(ContextInterface::class);
        $this->uiComponentFactoryMock = $this->createMock(UiComponentFactory::class);
        $this->countryFactoryMock = $this->createMock(CountryFactory::class);
        $this->countryModelMock = $this->createMock(Country::class);

        $objectManager = new ObjectManager($this);

        $this->countryNameColumn = $objectManager->getObject(
            CountryNameColumn::class,
            [
                'context' => $this->contextMock,
                'uiComponentFactory' => $this->uiComponentFactoryMock,
                'countryFactory' => $this->countryFactoryMock,
                'components' => [],
                'data' => []
            ]
        );
    }

    /**
     * Test for method prepareDataSource
     */
    public function testPrepareDataSource()
    {
        $this->countryFactoryMock->expects($this->once())->method('create')->willReturn($this->countryModelMock);
        $this->countryModelMock->expects($this->once())->method('loadByCode')->willReturn($this->countryModelMock);
        $this->countryModelMock->expects($this->once())->method('getName')->willReturn('Test Country');
        
        $dataSource['data']['items']['item'] = [RegionManagerCountryModel::ENTITY_ID => '15'];
        $actionColumn['data']['items']['item'] = [
            'country_name' => 'Test Country'
        ];

        $expectedResult = array_merge_recursive($dataSource, $actionColumn);
        $this->assertEquals($expectedResult, $this->countryNameColumn->prepareDataSource($dataSource));
    }
}
