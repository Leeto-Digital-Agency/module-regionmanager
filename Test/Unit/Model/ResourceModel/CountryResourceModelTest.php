<?php

namespace Leeto\RegionManager\Test\Unit\Model\ResourceModel;

use Leeto\RegionManager\Model\Country;
use Leeto\RegionManager\Model\ResourceModel\Country as CountryResourceModel;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class CountryResourceModelTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var CountryResourceModel
     */
    protected $countryResourceModel;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->countryResourceModel = $this->objectManager->getObject(CountryResourceModel::class);
    }

    /**
     * Test for _construct method.
     */
    public function testConstruct()
    {
        $resourceModel = $this->createMock(CountryResourceModel::class);
        $this->assertInstanceOf(CountryResourceModel::class, $resourceModel);
    }
}
