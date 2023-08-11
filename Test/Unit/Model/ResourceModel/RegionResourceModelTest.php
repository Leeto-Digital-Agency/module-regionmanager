<?php

namespace Leeto\RegionManager\Test\Unit\Model\ResourceModel;

use Leeto\RegionManager\Model\Region;
use Leeto\RegionManager\Model\ResourceModel\Region as RegionResourceModel;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class RegionResourceModelTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var RegionResourceModel
     */
    protected $regionResourceModel;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->regionResourceModel = $this->objectManager->getObject(RegionResourceModel::class);
    }

    /**
     * Test for _construct method.
     */
    public function testConstruct()
    {
        $resourceModel = $this->createMock(RegionResourceModel::class);
        $this->assertInstanceOf(RegionResourceModel::class, $resourceModel);
    }
}
