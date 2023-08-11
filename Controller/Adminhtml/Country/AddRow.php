<?php

namespace Leeto\RegionManager\Controller\Adminhtml\Country;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Leeto\RegionManager\Model\CountryFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;

class AddRow extends Action
{
    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var CountryFactory
     */
    private $countryFactory;

    /**
     * @param Context        $context
     * @param Registry       $coreRegistry,
     * @param CountryFactory $countryFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        CountryFactory $countryFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->countryFactory = $countryFactory;
    }

    /**
     * Mapped Grid List page.
     * @return Page
     */
    public function execute()
    {
        $rowId = $this->getRequest()->getParam('id');
        $rowData = $this->countryFactory->create();
        /** @var Page $resultPage */
        if ($rowId) {
            $rowData = $rowData->load($rowId);
            $rowTitle = $rowData->getCountryId();
            if (!$rowData->getEntityId()) {
                $this->messageManager->addError(__('row data no longer exist.'));
                $this->_redirect('regions/country/index');
                return;
            }
        }

        $this->coreRegistry->register('row_data', $rowData);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $title = $rowId ? __('Edit ') . $rowTitle : __('Add Row Data');
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Leeto_RegionManager::add_row');
    }

    public function isAllowed()
    {
        return $this->_isAllowed();
    }
}
