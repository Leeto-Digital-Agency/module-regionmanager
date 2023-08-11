<?php

namespace Leeto\RegionManager\Test\Unit\Block\Adminhtml\Country\Edit;

use Leeto\RegionManager\Block\Adminhtml\Country\Edit\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    /**
     * @var Form
     */
    protected $block;

    /**
     * @var MockObject|Registry
     */
    protected $registryMock;

    /**
     * @var MockObject|FormFactory
     */
    protected $formFactoryMock;

    protected function setUp(): void
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        // Mock the dependencies
        $this->registryMock = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->formFactoryMock = $this->getMockBuilder(FormFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $wysiwygConfigMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Create the block instance with constructor injection of the mocked dependencies
        $this->block = new Form(
            $contextMock,
            $this->registryMock,
            $this->formFactoryMock,
            $wysiwygConfigMock
        );
    }

    /**
     * Test isEditForm method for adding a new row.
     */
    public function testIsEditFormForNewRow()
    {
        // Create a new instance of the model without an entity ID to simulate adding a new row
        $model = new \Magento\Framework\DataObject();

        // Set the model in the registry
        $this->registryMock->expects($this->any())->method('registry')->with('row_data')->willReturn($model);

        // Call the method you want to test
        $result = $this->block->isEditForm();

        // Assert that the result is false for a new row
        $this->assertFalse($result);
    }

    /**
     * Test isEditForm method for editing an existing row.
     */
    public function testIsEditFormForExistingRow()
    {
        // Create a new instance of the model with an entity ID to simulate editing an existing row
        $model = new \Magento\Framework\DataObject(['entity_id' => 1]);

        // Set the model in the registry
        $this->registryMock->expects($this->any())->method('registry')->with('row_data')->willReturn($model);

        // Call the method you want to test
        $result = $this->block->isEditForm();

        // Assert that the result is true for editing an existing row
        $this->assertTrue($result);
    }
}
