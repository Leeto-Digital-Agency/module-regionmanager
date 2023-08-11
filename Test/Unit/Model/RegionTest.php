<?php

namespace Leeto\RegionManager\Test\Unit\Model;

use Leeto\RegionManager\Model\Region;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class RegionTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var Region
     */
    protected $regionModel;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->regionModel = $this->objectManager->getObject(Region::class);
    }

    /**
     * Test for getEntityId method.
     */
    public function testGetEntityId()
    {
        $expectedValue = 42;
        $this->regionModel->setData(Region::ENTITY_ID, $expectedValue);
        $this->assertEquals($expectedValue, $this->regionModel->getEntityId());
    }

    /**
     * Test for setEntityId method.
     */
    public function testSetEntityId()
    {
        $expectedValue = 42;
        $this->regionModel->setEntityId($expectedValue);
        $actualValue = $this->regionModel->getData(Region::ENTITY_ID);
        $this->assertEquals($expectedValue, $actualValue);
    }

    /**
     * Test for getCountryId method.
     */
    public function testGetCountryId()
    {
        $expectedValue = 'US';
        $this->regionModel->setData(Region::COUNTRY_ID, $expectedValue);
        $this->assertEquals($expectedValue, $this->regionModel->getCountryId());
    }

    /**
     * Test for setCountryId method.
     */
    public function testSetCountryId()
    {
        $expectedValue = 'US';
        $this->regionModel->setCountryId($expectedValue);
        $actualValue = $this->regionModel->getData(Region::COUNTRY_ID);
        $this->assertEquals($expectedValue, $actualValue);
    }

    /**
     * Test for getCode method.
     */
    public function testGetCode()
    {
        $expectedValue = 'CA';
        $this->regionModel->setData(Region::CODE, $expectedValue);
        $this->assertEquals($expectedValue, $this->regionModel->getCode());
    }

    /**
     * Test for setCode method.
     */
    public function testSetCode()
    {
        $expectedValue = 'CA';
        $this->regionModel->setCode($expectedValue);
        $actualValue = $this->regionModel->getData(Region::CODE);
        $this->assertEquals($expectedValue, $actualValue);
    }

    /**
     * Test for getDefaultName method.
     */
    public function testGetDefaultName()
    {
        $expectedValue = 'California';
        $this->regionModel->setData(Region::DEFAULT_NAME, $expectedValue);
        $this->assertEquals($expectedValue, $this->regionModel->getDefaultName());
    }

    /**
     * Test for setDefaultName method.
     */
    public function testSetDefaultName()
    {
        $expectedValue = 'California';
        $this->regionModel->setDefaultName($expectedValue);
        $actualValue = $this->regionModel->getData(Region::DEFAULT_NAME);
        $this->assertEquals($expectedValue, $actualValue);
    }
}
