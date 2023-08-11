<?php

namespace Leeto\RegionManager\Test\Unit\Block\Adminhtml\Region;

use Leeto\RegionManager\Block\Adminhtml\Region\AddRow;
use Magento\Backend\Block\Widget\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\UrlInterface;

class AddRowTest extends TestCase
{
    /**
     * @var AddRow
     */
    protected $block;

    /**
     * @var UrlInterface|MockObject
     */
    private $urlBuilderMock;

    /**
     * @var MockObject|Context
     */
    protected $contextMock;

    /**
     * Initialize objects required for testing
     */
    
    protected function setUp(): void
    {
        $this->contextMock = $this->createPartialMock(
            Context::class,
            ['getUrlBuilder', 'getButtonList']
        );
        
        $this->urlBuilderMock = $this->getMockBuilder(UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock->expects($this->any())
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilderMock);

        $this->block = $this->createPartialMock(AddRow::class, [
            'getChildBlock'
        ]);
    }

    /**
     * Test the construction of the block
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(AddRow::class, $this->block);
    }

    /**
     * Test the block's header text retrieval
     */
    public function testGetHeaderText()
    {
        $expectedHeaderText = __('Add Row Data');
        $this->assertEquals($expectedHeaderText, $this->block->getHeaderText());
    }

    /**
     * Test get form action
     */
    public function testGetFormActionUrl(): void
    {
        $excpectedUrl = 'someUrl';
        $this->block->setData(['form_action_url' => $excpectedUrl]);
        $this->assertEquals($excpectedUrl, $this->block->getFormActionUrl());
    }
}
