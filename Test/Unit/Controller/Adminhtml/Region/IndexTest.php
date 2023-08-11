<?php

namespace Leeto\RegionManager\Test\Unit\Controller\Adminhtml\Region;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Leeto\RegionManager\Controller\Adminhtml\Country\Index;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;
use Magento\Framework\AuthorizationInterface;

class IndexTest extends TestCase
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
     * @var MockObject|ResultFactory
     */
    protected $resultFactoryMock;

    /**
     * @var MockObject|PageFactory
     */
    protected $pageFactoryMock;

    /**
     * @var Index
     */
    protected $indexController;

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
     * @var MockObject|AuthorizationInterface
     */
    protected $authorizationMock;

    /**
     * @var MockObject|ObjectManagerInterface
     */
    protected $objectManagerMock;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->addMethods(['getTitle'])
            ->onlyMethods(
                [
                    'getAuthorization',
                    'getResultFactory',
                    'getResultRedirectFactory',
                    'getObjectManager'
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->page = $this->getMockBuilder(Page::class)
            ->addMethods(['create'])
            ->onlyMethods(['getConfig', 'setActiveMenu'])
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
        
        $this->pageFactoryMock = $this->getMockBuilder(PageFactory::class)
            ->onlyMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
            
        $this->page->expects($this->any())->method('create')->willReturn($this->page);
        $this->page->expects($this->any())->method('setActiveMenu')->willReturn($this->page);
            
        $this->authorizationMock = $this->getMockBuilder(AuthorizationInterface::class)
        ->addMethods(['create'])
        ->onlyMethods(['isAllowed'])
        ->disableOriginalConstructor()
        ->getMock();
        
        $this->authorizationMock->expects($this->any())->method('create')->willReturn($this->authorizationMock);
        
        $this->contextMock->expects($this->any())->method('getObjectManager')->willReturn($this->objectManagerMock);
        $this->contextMock->expects($this->any())->method('getAuthorization')->willReturn($this->authorizationMock);
        $this->contextMock->expects($this->any())->method('getResultFactory')->willReturn($this->resultFactoryMock);
        $this->contextMock->expects($this->any())->method('getResultRedirectFactory')
            ->willReturn($this->resultFactoryMock);
        $this->indexController = $this->objectManager->getObject(
            Index::class,
            [
                'context' => $this->contextMock,
                'resultPageFactory' => $this->pageFactoryMock,
            ]
        );
    }

    /**
     * Run test execute method
     */
    public function testExecute()
    {
        $this->pageFactoryMock->expects($this->atLeastOnce())->method('create')->willReturn($this->page);
        $this->page->expects($this->atLeastOnce())->method('setActiveMenu')->willReturn($this->page);
        $this->configTitle->expects($this->any())->method('prepend')->willReturnSelf();
        $this->pageConfig->expects($this->any())->method('getTitle')->willReturn($this->configTitle);
        $this->page->expects($this->any())->method('getConfig')->willReturn($this->pageConfig);

        $this->assertSame($this->page, $this->indexController->execute());
    }

    /**
     * Run test isAllowed method
     */
    public function testIsAllowed()
    {
        $this->authorizationMock->expects($this->any())->method('isAllowed')->willReturn(true);
        $this->indexController->isAllowed();
    }
}
