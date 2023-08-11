<?php

namespace Leeto\RegionManager\Test\Unit\Ui\Component\Listing\Grid\Column\Country;

use PHPUnit\Framework\TestCase;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Leeto\RegionManager\Ui\Component\Listing\Grid\Column\Country\Action;
use Magento\Framework\UrlInterface;
use Leeto\RegionManager\Model\Country;

class ActionTest extends TestCase
{
    /**
     * @var MockObject|UrlInterface
     */
    protected $urlInterfaceMock;

    /**
     * @var MockObject|ContextInterface
     */
    protected $contextMock;

    /**
     * @var MockObject|UiComponentFactory
     */
    protected $uiComponentFactoryMock;

    /**
     * @var Action
     */
    protected $actionMock;

    public function setUp(): void
    {
        $this->contextMock = $this->getMockForAbstractClass(ContextInterface::class);
        $this->uiComponentFactoryMock = $this->createMock(UiComponentFactory::class);
        $this->urlInterfaceMock = $this->createMock(UrlInterface::class);
        $objectManager = new ObjectManager($this);

        $this->actionMock = $objectManager->getObject(
            Action::class,
            [
                'context' => $this->contextMock,
                'uiComponentFactory' => $this->uiComponentFactoryMock,
                'urlBuilder' => $this->urlInterfaceMock,
                'components' => [],
                'data' => ['name' => 'Edit']
            ]
        );
    }

    /**
     * Test for method prepareDataSource
     */
    public function testPrepareDataSource()
    {
        $href = 'regions/country/addrow/id/15';
        $this->urlInterfaceMock->expects($this->once())->method('getUrl')
            ->with(
                'regions/country/addrow',
                ['id' => 15]
            )->willReturn($href);
        $dataSource['data']['items']['item'] = [Country::ENTITY_ID => '15'];
        $actionColumn['data']['items']['item'] = [
            'Edit' => [
                'edit' => [
                    'href' => $href,
                    'label' => __('Edit')
                ]
            ]
        ];
        $expectedResult = array_merge_recursive($dataSource, $actionColumn);
        $this->assertEquals($expectedResult, $this->actionMock->prepareDataSource($dataSource));
    }
}
