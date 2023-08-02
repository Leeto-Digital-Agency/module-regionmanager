<?php

namespace Leeto\RegionManager\Controller\Adminhtml\Region;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Leeto\RegionManager\Model\RegionFactory
     */
    var $regionFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Leeto\RegionManager\Model\RegionFactory $regionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Leeto\RegionManager\Model\RegionFactory $regionFactory
    ) {
        parent::__construct($context);
        $this->regionFactory = $regionFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('regions/region/index');
            return;
        }
        try {
            $rowData = $this->regionFactory->create();
            $rowData->setData($data);
            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            }
            $rowData->save();
            $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('regions/region/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Leeto_RegionManager::save');
    }
}