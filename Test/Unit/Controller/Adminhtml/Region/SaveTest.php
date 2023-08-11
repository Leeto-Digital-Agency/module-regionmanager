<?php

namespace Leeto\RegionManager\Test\Unit\Controller\Adminhtml\Region;

use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use Leeto\RegionManager\Model\RegionFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Backend\Helper\Data as BackendHelper;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Backend\Model\Session;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\App\ActionFlag;
use Leeto\RegionManager\Controller\Adminhtml\Region\Save;
use Leeto\RegionManager\Model\Region;

class SaveTest extends TestCase
{
    /**
     * @var MockObject|RegionFactory
     */
    protected $regionFactoryMock;

    /**
     * @var MockObject|Context
     */
    protected $contextMock;

    /**
     * @var MockObject|ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|RequestInterface
     */
    protected $requestMock;

    /**
     * @var MockObject|ResponseInterface
     */
    protected $responseInterfaceMock;

    /**
     * @var MockObject|BackendHelper
     */
    protected $backendHelperMock;

    /**
     * @var MockObject|RedirectInterface
     */
    protected $redirectInterfaceMock;

    /**
     * @var MockObject|Session
     */
    protected $sessionMock;

    /**
     * @var MockObject|ObjectManagerInterface
     */
    protected $objectManagerMock;

    /**
     * @var MockObject|ResultFactory
     */
    protected $resultFactoryMock;

    /**
     * @var MockObject|AuthorizationInterface
     */
    protected $authorizationMock;

    /**
     * @var MockObject|ActionFlag
     */
    protected $actionFlagMock;

    /**
     * @var MockObject|MessageManagerInterface
     */
    protected $messageManagerInterfaceMock;

    /**
     * @var Save
     */
    protected $saveController;

    /**
     * @var MockObject|Region
     */
    protected $regionModelMock;

    public function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->regionFactoryMock = $this->getMockBuilder(RegionFactory::class)
            ->onlyMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->regionModelMock = $this->getMockBuilder(Region::class)
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
        $this->responseInterfaceMock = $this->getMockBuilder(ResponseInterface::class)
            ->addMethods(['setRedirect'])
            ->onlyMethods(['sendResponse'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->backendHelperMock = $this->getMockBuilder(BackendHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->redirectInterfaceMock = $this->getMockBuilder(RedirectInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerMock = $this->getMockBuilder(ObjectManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultFactoryMock = $this->getMockBuilder(ResultFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->authorizationMock = $this->getMockBuilder(AuthorizationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->actionFlagMock = $this->getMockBuilder(ActionFlag::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->messageManagerInterfaceMock = $this->getMockBuilder(MessageManagerInterface::class)
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
        
        $this->responseInterfaceMock->expects($this->any())->method('setRedirect')
            ->willReturn($this->responseInterfaceMock);

        $this->saveController = $this->objectManager->getObject(
            Save::class,
            [
                'context' => $this->contextMock,
                'regionFactory' => $this->regionFactoryMock,
            ]
        );
    }

    /**
     * Run test execute method
     *
     * @param array $data
     * @return void
     *
     * @dataProvider dataProviderExecuteSuccess
     */
    public function testExecuteSuccess($data)
    {
        $this->requestMock->expects($this->atLeastOnce())
            ->method('getPostValue')
            ->willReturn($data);

        $this->regionFactoryMock->expects($this->once())->method('create')->willReturn($this->regionModelMock);
        if (isset($data['id'])) {
            $this->regionModelMock->expects($this->once())->method('setData')
                ->with(
                    [
                        'country_id' => $data['country_id'],
                        'code' => $data['code'],
                        'default_name' => $data['default_name'],
                        'entity_id' => $data['id']
                    ]
                )
                ->willReturn($this->regionModelMock);
        } else {
            $this->regionModelMock->expects($this->once())->method('setData')
                ->with(
                    [
                        'country_id' => $data['country_id'],
                        'code' => $data['code'],
                        'default_name' => $data['default_name']
                    ]
                )
                ->willReturn($this->regionModelMock);
        }
        $this->regionModelMock->expects($this->once())->method('save')->willReturn($this->regionModelMock);
        $this->messageManagerInterfaceMock->expects($this->once())->method('addSuccess')->willReturnSelf();
        
        $this->backendHelperMock->expects($this->any())->method('getUrl')->willReturn('someUrl');

        $this->saveController->execute();
    }

    /**
     * Run test execute method
     *
     * @param array $data
     * @return void
     *
     * @dataProvider dataProviderExecuteNoData
     */
    public function testExecuteNoData($data)
    {
        $this->requestMock->expects($this->atLeastOnce())
            ->method('getPostValue')
            ->willReturn($data);

        $this->backendHelperMock->expects($this->any())->method('getUrl')->willReturn('someUrl');

        $this->saveController->execute();
    }

    /**
     * Data provider for testExecuteSuccess
     *
     * @return array
     */
    public function dataProviderExecuteSuccess()
    {
        return [
            [
                [
                    'country_id' => 'US',
                    'code' => 'AB',
                    'default_name' => 'sample'
                ],
                [
                    'country_id' => 'US',
                    'code' => 'AB',
                    'default_name' => 'sample',
                    'id' => 1
                ]
            ]
        ];
    }

    /**
     * Data provider for testExecuteNoData
     *
     * @return array
     */
    public function dataProviderExecuteNoData()
    {
        return [
            [
                [
                ]
            ]
        ];
    }
}
