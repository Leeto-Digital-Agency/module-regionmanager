<?php

namespace Leeto\RegionManager\Test\Unit\Controller\Adminhtml\Country;

use PHPUnit\Framework\TestCase;
use Leeto\RegionManager\Model\RegionFactory;
use Leeto\RegionManager\Model\Region;
use Leeto\RegionManager\Model\ResourceModel\Region\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Leeto\RegionManager\Controller\Adminhtml\Country\Save;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Leeto\RegionManager\Model\ResourceModel\Region\Collection;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Backend\Model\Session;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\ResponseInterface;
use Magento\Backend\Helper\Data as BackendHelper;
use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SaveTest extends TestCase
{
    /**
     * @var MockObject|RegionFactory
     */
    protected $regionFactoryMock;
    
    /**
     * @var MockObject|AbstractDb
     */
    protected $resourceMock;

    /**
     * @var MockObject|MessageManagerInterface
     */
    protected $messageManagerInterfaceMock;

    /**
     * @var MockObject|Region
     */
    protected $regionModelMock;

    /**
     * @var MockObject|BackendHelper
     */
    protected $backendHelperMock;

    /**
     * @var MockObject|RedirectInterface
     */
    protected $redirectInterfaceMock;
   
    /**
     * @var MockObject|CollectionFactory
     */
    protected $regionCollectionFactoryMock;

    /**
     * @var MockObject|Collection
     */
    protected $regionCollectionMock;

    /**
     * @var MockObject|ResponseInterface
     */
    protected $responseInterfaceMock;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var Save
     */
    protected $saveController;

    /**
     * @var MockObject|Context
     */
    protected $contextMock;

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
     * @var MockObject|RequestInterface
     */
    protected $requestMock;

    public function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->regionFactoryMock = $this->getMockBuilder(RegionFactory::class)
            ->onlyMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->resourceMock = $this->getMockBuilder(AbstractDb::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->regionCollectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->regionCollectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(['addFieldToFilter', 'count', 'getItems', 'getFirstItem'])
            ->getMock();
        $this->redirectInterfaceMock = $this->getMockBuilder(RedirectInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->messageManagerInterfaceMock = $this->getMockBuilder(MessageManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->addMethods(['setIsUrlNotice'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->actionFlagMock = $this->getMockBuilder(ActionFlag::class)
            ->onlyMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->responseInterfaceMock = $this->getMockBuilder(ResponseInterface::class)
            ->addMethods(['setRedirect'])
            ->onlyMethods(['sendResponse'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->backendHelperMock = $this->getMockBuilder(BackendHelper::class)
            ->onlyMethods(['getUrl'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->regionModelMock = $this->getMockBuilder(Region::class)
            ->addMethods(['create'])
            ->onlyMethods(['setData', 'save', 'load', 'delete'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->objectManagerMock = $this->getMockBuilder(ObjectManagerInterface::class)
            ->onlyMethods(['create'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

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
                    'getAuthorization',
                    'getRedirect',
                    'getActionFlag',
                    'getHelper'
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->authorizationMock = $this->getMockBuilder(AuthorizationInterface::class)
            ->addMethods(['create'])
            ->onlyMethods(['isAllowed'])
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

        $this->regionCollectionFactoryMock->expects($this->atLeastOnce())->method('create')
            ->willReturn($this->regionCollectionMock);
        
        $this->regionCollectionMock->expects($this->any())->method('addFieldToFilter')->willReturnSelf();
        $this->regionCollectionMock->expects($this->any())->method('getFirstItem')->willReturn($this->regionModelMock);

        $this->messageManagerInterfaceMock->expects($this->any())->method('addError')
            ->willReturn($this->messageManagerInterfaceMock);
        
        $this->regionModelMock->expects($this->any())->method('create')->willReturn($this->regionModelMock);
        
        $this->actionFlagMock->expects($this->any())->method('get')->willReturn(true);

        $this->responseInterfaceMock->expects($this->any())->method('setRedirect')
            ->willReturn($this->responseInterfaceMock);

        $this->regionFactoryMock->expects($this->atLeastOnce())->method('create')->willReturn($this->regionModelMock);

        $this->saveController = $this->objectManager->getObject(
            Save::class,
            [
                'context' => $this->contextMock,
                'regionFactory' => $this->regionFactoryMock,
                'regionCollection' => $this->regionCollectionFactoryMock,
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
        $region = $data['country_regions'][0];
        $this->regionCollectionMock->expects($this->any())->method('count')->willReturn(0);

        $this->regionModelMock->method('setData')
            ->with(
                [
                    'country_id' => $data['country_id'],
                    'code' => $region['code'],
                    'default_name' => $region['default_name']
                ]
            );
        if (isset($region['region_id']) && $region['region_id']) {
            $this->regionModelMock->method('setEntityId')
                ->with($region['region_id']);
        }

        $this->regionModelMock->method('save')->willReturnSelf();

        $this->regionCollectionMock->method('getItems')
            ->willReturn(new DataObject(
                [
                    'country_id' => 1,
                    'code' => 'F',
                    'default_name' => 'V',
                    'region_id' => 4
                ]
            ));

        $this->regionModelMock->method('load')->willReturnSelf();
        $this->regionModelMock->method('delete')->willReturnSelf();
        $this->regionModelMock->expects($this->once())->method('save')->willReturnSelf();
        
        $this->messageManagerInterfaceMock->expects($this->any())->method('addSuccess')->willReturnSelf();
                
        $redirect = ResponseInterface::class;

        if (isset($data['back']) && $data['back']) {
            $this->requestMock->expects($this->atLeastOnce())
                ->method('getParam')
                ->with('back')
                ->willReturn(true);
        }
        $this->backendHelperMock->expects($this->any())->method('getUrl')->willReturn('someUrl');

        $this->assertInstanceOf($redirect, $this->saveController->execute());
    }

    /**
     * Run test execute method
     *
     * @param array $data
     * @return void
     *
     * @dataProvider dataProviderExecuteCodeSameAsDefaultName
     */
    public function testExecuteCodeSameAsDefaultName($data)
    {
        $this->requestMock->expects($this->atLeastOnce())
            ->method('getPostValue')
            ->willReturn($data);
        $region = $data['country_regions'][0];
        $this->messageManagerInterfaceMock->expects($this->atLeastOnce())->method('addError')->willReturnSelf();
        
        $this->regionCollectionMock->expects($this->any())->method('count')->willReturn(0);

        $this->regionModelMock->method('setData')
            ->with(
                [
                    'country_id' => $data['country_id'],
                    'code' => $region['code'],
                    'default_name' => $region['default_name']
                ]
            );

        $this->regionCollectionMock->method('getItems')
            ->willReturn(new DataObject(
                [
                    'country_id' => 1,
                    'code' => 'F',
                    'default_name' => 'V',
                    'region_id' => 4
                ]
            ));

        $this->regionModelMock->method('load')->willReturnSelf();
        $this->regionModelMock->method('delete')->willReturnSelf();
                
        $redirect = ResponseInterface::class;

        if (isset($data['back']) && $data['back']) {
            $this->requestMock->expects($this->atLeastOnce())
                ->method('getParam')
                ->with('back')
                ->willReturn(true);
        }
        $this->backendHelperMock->expects($this->any())->method('getUrl')->willReturn('someUrl');

        $this->assertInstanceOf($redirect, $this->saveController->execute());
    }

    /**
     * Run test execute method
     *
     * @param array $data
     * @return void
     *
     * @dataProvider dataProviderExecuteDeleteAllRegions
     */
    public function testExecuteDeleteAllRegions($data)
    {
        $this->requestMock->expects($this->atLeastOnce())
            ->method('getPostValue')
            ->willReturn($data);
                
        $this->regionFactoryMock->expects($this->once())->method('create')->willReturn($this->regionModelMock);
        $this->regionCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->regionCollectionMock);
        $this->regionCollectionMock->expects($this->once())->method('addFieldToFilter')
            ->willReturn($this->regionCollectionMock);
        $this->regionCollectionMock->method('getItems')
            ->willReturn(
                [
                    new DataObject(
                        [
                            'country_id' => 1,
                            'code' => 'F',
                            'default_name' => 'V',
                            'region_id' => 4
                        ]
                    )
                ]
            );
        $this->regionModelMock->expects($this->atLeastOnce())->method('load')->willReturn($this->regionModelMock);
        $this->regionModelMock->expects($this->atLeastOnce())->method('delete')->willReturn($this->regionModelMock);
        $this->messageManagerInterfaceMock->expects($this->any())->method('addSuccess')->willReturnSelf();

        $redirect = ResponseInterface::class;

        if (isset($data['back']) && $data['back']) {
            $this->requestMock->expects($this->atLeastOnce())
                ->method('getParam')
                ->with('back')
                ->willReturn(true);
        }
        $this->backendHelperMock->expects($this->any())->method('getUrl')->willReturn('someUrl');

        $this->assertInstanceOf($redirect, $this->saveController->execute());
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
                    'country_regions' => [
                        [
                            'code' => 'A',
                            'default_name' => 'B'
                        ]
                    ],
                    'country_id' => 'C',
                    'back' => true
                ]
            ]
        ];
    }

    /**
     * Data provider for ExecuteCodeSameAsDefaultName
     *
     * @return array
     */
    public function dataProviderExecuteCodeSameAsDefaultName()
    {
        return [
            [
                [
                    'country_regions' => [
                        [
                            'code' => 'B',
                            'default_name' => 'B'
                        ]
                    ],
                    'country_id' => 'C',
                    'back' => true
                ]
            ]
        ];
    }

    /**
     * Data provider for ExecuteDeleteAllRegions
     *
     * @return array
     */
    public function dataProviderExecuteDeleteAllRegions()
    {
        return [
            [
                [
                    'country_id' => 'C',
                    'back' => true
                ]
            ]
        ];
    }
}
