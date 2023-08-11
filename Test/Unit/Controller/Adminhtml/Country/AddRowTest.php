<?php

namespace Leeto\RegionManager\Test\Unit\Controller\Adminhtml\Country;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Leeto\RegionManager\Model\CountryFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Leeto\RegionManager\Controller\Adminhtml\Country\AddRow;
use Leeto\RegionManager\Model\Country;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;

class AddRowTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|Context
     */
    protected $contextMock;

    /**
     * @var MockObject|Registry
     */
    protected $registryMock;

    /**
     * @var MockObject|CountryFactory
     */
    protected $countryFactoryMock;

    /**
     * @var MockObject|ResultFactory
     */
    protected $resultFactoryMock;

    /**
     * @var AddRow
     */
    protected $addRowController;

    /**
     * @var MockObject|RequestInterface
     */
    protected $requestMock;

    /**
     * @var MockObject|Country
     */
    protected $countryMock;

    /**
     * @var MockObject|Page
     */
    protected $page;

    /**
     * @var MockObject|Config
     */
    protected $pageConfig;

    /**
     * @var MockObject|Title
     */
    protected $configTitle;

    /**
     * @var MockObject|ObjectManagerInterface
     */
    protected $objectManagerMock;
    
    /**
     * @var MockObject|AuthorizationInterface
     */
    protected $authorizationMock;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->countryMock = $this->getMockBuilder(Country::class)
            ->onlyMethods(['getEntityId'])
            ->addMethods(['create', 'getCountryId'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockForAbstractClass(
            RequestInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['getParam', 'getPost', 'getPostValue', 'getQuery', 'setParam']
        );

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->addMethods(['getTitle'])
            ->onlyMethods(
                [
                    'getRequest',
                    'getObjectManager',
                    'getEventManager',
                    'getResponse',
                    'getMessageManager',
                    'getResultRedirectFactory',
                    'getSession',
                    'getResultFactory',
                    'getAuthorization'
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->registryMock = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->countryFactoryMock = $this->getMockBuilder(CountryFactory::class)
            ->onlyMethods(['create'])
            ->addMethods(['load'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->countryFactoryMock->expects($this->any())->method('create')->willReturn($this->countryFactoryMock);

        $this->resultFactoryMock = $this->getMockBuilder(ResultFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->page = $this->getMockBuilder(Page::class)
            ->addMethods(['create'])
            ->onlyMethods(['getConfig'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->pageConfig = $this->getMockBuilder(Config::class)
            ->onlyMethods(['getTitle'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->configTitle = $this->getMockBuilder(Title::class)
            ->onlyMethods(['prepend'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->authorizationMock = $this->getMockBuilder(AuthorizationInterface::class)
            ->addMethods(['create'])
            ->onlyMethods(['isAllowed'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->authorizationMock->expects($this->any())->method('create')->willReturn($this->authorizationMock);
        $this->configTitle->expects($this->any())->method('prepend')->willReturnSelf();
        $this->pageConfig->expects($this->any())->method('getTitle')->willReturn($this->configTitle);
        $this->page->expects($this->any())->method('create')->willReturn($this->page);
        $this->page->expects($this->any())->method('getConfig')->willReturn($this->pageConfig);

        $this->resultFactoryMock->expects($this->any())->method('create')->willReturn($this->page);

        $this->objectManagerMock = $this->getMockBuilder(ObjectManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->expects($this->any())->method('getAuthorization')->willReturn($this->authorizationMock);
        $this->contextMock->expects($this->any())->method('getObjectManager')->willReturn($this->objectManagerMock);
        $this->contextMock->expects($this->any())->method('getResultFactory')->willReturn($this->resultFactoryMock);
        $this->contextMock->expects($this->any())
                ->method('getResultRedirectFactory')
                ->willReturn($this->resultFactoryMock);

        $this->addRowController = $this->objectManager->getObject(
            AddRow::class,
            [
                'context' => $this->contextMock,
                'coreRegistry' => $this->registryMock,
                'countryFactory' => $this->countryFactoryMock,
            ]
        );
    }

    /**
     * Run test execute method
     *
     * @param int|bool $countryId
     * @return void
     *
     * @dataProvider dataProviderExecute
     */
    public function testExecute($countryId, $countryTitle)
    {
        $this->requestMock->expects($this->atLeastOnce())
            ->method('getParam')
            ->willReturnMap(
                [
                    ['id', false, $countryId]
                ]
            );

        $this->countryFactoryMock->expects($this->any())
            ->method('load')
            ->willReturn($this->countryMock);

        $this->countryMock->expects($this->any())
            ->method('getCountryId')
            ->willReturn($countryTitle);

        $this->countryMock->expects($this->any())
            ->method('getEntityId')
            ->willReturn($countryId);

        $this->addRowController->execute();
    }

    /**
     * Data provider for execute
     *
     * @return array
     */
    public function dataProviderExecute()
    {
        return [
            [
                'countryId' => 2,
                'countryTitle' => 'TEST'
            ],
            [
                'countryId' => null,
                'countryTitle' => null
            ]
        ];
    }

    public function testIsAllowed()
    {
        $this->authorizationMock->expects($this->any())->method('isAllowed')->willReturn(true);
        $this->addRowController->isAllowed();
    }
}
