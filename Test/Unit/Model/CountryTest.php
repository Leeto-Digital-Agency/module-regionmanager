<?php

namespace Leeto\RegionManager\Test\Unit\Model;

use Leeto\RegionManager\Model\Country;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var Country
     */
    protected $countryModel;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->countryModel = $this->objectManager->getObject(Country::class);
    }

    /**
     * Test for getEntityId method.
     */
    public function testGetEntityId()
    {
        $expectedValue = 42;
        $this->countryModel->setData(Country::ENTITY_ID, $expectedValue);
        $this->assertEquals($expectedValue, $this->countryModel->getEntityId());
    }

    /**
     * Test for setEntityId method.
     */
    public function testSetEntityId()
    {
        $expectedValue = 42;
        $this->countryModel->setEntityId($expectedValue);
        $actualValue = $this->countryModel->getData(Country::ENTITY_ID);
        $this->assertEquals($expectedValue, $actualValue);
    }
}
