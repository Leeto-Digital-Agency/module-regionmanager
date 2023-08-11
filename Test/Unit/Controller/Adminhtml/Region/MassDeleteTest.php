<?php

namespace Leeto\RegionManager\Test\Unit\Controller\Adminhtml\Region;

use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Leeto\RegionManager\Model\ResourceModel\Region\CollectionFactory;
use Leeto\RegionManager\Model\ResourceModel\Region\Collection;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Backend\Model\Session;
use Magento\Framework\App\ActionFlag;
use Leeto\RegionManager\Controller\Adminhtml\Region\MassDelete;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Backend\Helper\Data as BackendHelper;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\Redirect as RedirectController;

class MassDeleteTest extends TestCase
{
    /**
     * @var MockObject|AbstractDb
     */
    protected $abstractDbMock;
    
    /**
     * @var MockObject|RedirectController
     */
    protected $redirectControllerMock;

    /**
     * @var MockObject|ResultInterface
     */
    protected $resultInterfaceMock;

    /**
     * @var MockObject|EventManagerInterface
     */
    protected $eventManagerMock;

    /**
     * @var MockObject|ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|BackendHelper
     */
    protected $backendHelperMock;
    
    /**
     * @var MassDelete
     */
    protected $massDeleteController;

    /**
     * @var MockObject|RequestInterface
     */
    protected $requestMock;

    /**
     * @var MockObject|ObjectManagerInterface
     */
    protected $objectManagerMock;

    /**
     * @var MockObject|Context
     */
    protected $contextMock;

    /**
     * @var MockObject|ActionFlag
     */
    protected $actionFlagMock;

    /**
     * @var MockObject|Filter
     */
    protected $filterMock;

    /**
     * @var MockObject|CollectionFactory
     */
    protected $regionCollectionFactoryMock;

    /**
     * @var MockObject|Collection
     */
    protected $regionCollectionMock;

    /**
     * @var MockObject|RedirectInterface
     */
    protected $redirectInterfaceMock;

    /**
     * @var MockObject|ResultFactory
     */
    protected $resultFactoryMock;

    /**
     * @var MockObject|MessageManagerInterface
     */
    protected $messageManagerInterfaceMock;

    /**
     * @var MockObject|Session
     */
    protected $sessionMock;
    
    /**
     * @var MockObject|ResponseInterface
     */
    protected $responseInterfaceMock;

    /**
     * @var MockObject|AuthorizationInterface
     */
    protected $authorizationMock;

    public function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        
        $this->requestMock = $this->getMockForAbstractClass(
            RequestInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['getParam', 'getPost', 'getPostValue', 'getQuery', 'setParam']
        );

        $this->authorizationMock = $this->getMockBuilder(AuthorizationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->regionCollectionMock = $this->getMockBuilder(Collection::class)
            ->onlyMethods(['getIterator', 'getItems'])
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->resultInterfaceMock = $this->getMockBuilder(ResultInterface::class)
            ->onlyMethods(['setHeader', 'renderResult'])
            ->addMethods(['setPath'])
            ->setMethods(['setHttpResponseCode'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->filterMock = $this->getMockBuilder(Filter::class)
            ->onlyMethods(['getCollection', 'getFilterIds', 'applySelectionOnTargetProvider'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventManagerMock = $this->getMockBuilder(EventManagerInterface::class)
            ->onlyMethods(['dispatch'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseInterfaceMock = $this->getMockBuilder(ResponseInterface::class)
            ->addMethods(['setRedirect'])
            ->onlyMethods(['sendResponse'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->redirectControllerMock = $this->getMockBuilder(RedirectController::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->regionCollectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->abstractDbMock = $this->getMockBuilder(AbstractDb::class)
            ->onlyMethods(['getResource'])
            ->addMethods(['getCollection'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultFactoryMock = $this->getMockBuilder(ResultFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerMock = $this->getMockBuilder(ObjectManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->actionFlagMock = $this->getMockBuilder(ActionFlag::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->messageManagerInterfaceMock = $this->getMockBuilder(MessageManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->redirectInterfaceMock = $this->getMockBuilder(RedirectInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

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
                    'getAuthorization',
                    'getRedirect',
                    'getActionFlag',
                    'getHelper'
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->expects($this->any())->method('getResponse')->willReturn($this->responseInterfaceMock);
        $this->contextMock->expects($this->any())->method('getRedirect')->willReturn($this->redirectInterfaceMock);
        $this->contextMock->expects($this->any())->method('getAuthorization')->willReturn($this->authorizationMock);
        $this->contextMock->expects($this->any())->method('getObjectManager')->willReturn($this->objectManagerMock);
        $this->contextMock->expects($this->any())->method('getMessageManager')
            ->willReturn($this->messageManagerInterfaceMock);
        $this->contextMock->expects($this->once())->method('getSession')->willReturn($this->sessionMock);
        $this->contextMock->expects($this->any())->method('getResultFactory')->willReturn($this->resultFactoryMock);
        $this->contextMock->expects($this->any())->method('getResultRedirectFactory')
            ->willReturn($this->resultFactoryMock);
        $this->contextMock->expects($this->any())->method('getActionFlag')->willReturn($this->actionFlagMock);
        $this->contextMock->expects($this->any())->method('getHelper')->willReturn($this->backendHelperMock);
        $this->contextMock->expects($this->any())->method('getEventManager')->willReturn($this->eventManagerMock);
                
        $this->massDeleteController = $this->objectManager->getObject(
            MassDelete::class,
            [
                'context' => $this->contextMock,
                'filter' => $this->filterMock,
                'collectionFactory' => $this->regionCollectionFactoryMock
            ]
        );
    }

    /**
     * Run test execute method
     *
     * @return void
     */
    public function testExecute()
    {
        $deletedRecordsCount = 2;

        $collection = [
            $this->getRegionMock(),
            $this->getRegionMock()
        ];

        $this->regionCollectionFactoryMock->expects($this->once())->method('create')
            ->willReturn($this->regionCollectionMock);
        $this->filterMock->expects($this->once())->method('getCollection')
            ->with($this->regionCollectionMock)
            ->willReturn($this->regionCollectionMock);

        $this->regionCollectionMock->expects($this->once())->method('getItems')
            ->willReturn($this->regionCollectionMock);
        $this->regionCollectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($collection));

        $this->messageManagerInterfaceMock->expects($this->once())
            ->method('addSuccess')
            ->with(__('A total of %1 record(s) have been deleted.', $deletedRecordsCount));
        $this->messageManagerInterfaceMock->expects($this->never())->method('addErrorMessage');

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->redirectControllerMock);

        $result = $this->createMock(ResultInterface::class);

        $this->redirectControllerMock->expects($this->once())->method('setPath')
            ->with('*/*/index')->willReturn($result);

        $this->assertSame($result, $this->massDeleteController->execute());
    }

    /**
     * Run test isAllowedTrue method
     *
     * @return void
     */
    public function testIsAllowedTrue()
    {
        $result = true;
        $this->authorizationMock->expects($this->once())->method('isAllowed')
            ->with('Leeto_RegionManager::row_data_delete')
            ->willReturn(true);
        $this->assertEquals($result, $this->massDeleteController->isAllowed());
    }

    /**
     * Run test isAllowedFalse method
     *
     * @return void
     */
    public function testIsAllowedFalse()
    {
        $result = true;
        $this->authorizationMock->expects($this->once())->method('isAllowed')
            ->with('Leeto_RegionManager::row_data_delete')
            ->willReturn(false);
        $this->assertNotEquals($result, $this->massDeleteController->isAllowed());
    }

    /**
     * Create Collection Mock
     *
     * @return Leeto\RegionManager\Model\ResourceModel\Region\Collection|MockObject
     */
    protected function getRegionMock()
    {
        $regionMock = $this->getMockBuilder(Collection::class)
            ->addMethods(['delete', 'setId', 'getEntityId'])
            ->disableOriginalConstructor()
            ->getMock();
        $regionMock->expects($this->once())->method('setId')->willReturn($regionMock);
        $regionMock->expects($this->once())->method('getEntityId')->willReturn(1);
        $regionMock->expects($this->once())->method('delete')->willReturn(true);

        return $regionMock;
    }
}
