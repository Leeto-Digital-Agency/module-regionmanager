<?php

namespace Leeto\RegionManager\Controller\Adminhtml\Region;

use Magento\Backend\App\Action;
use Leeto\RegionManager\Model\RegionFactory;
use Magento\Backend\App\Action\Context;

class Save extends Action
{
    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @param Context       $context
     * @param RegionFactory $regionFactory
     */
    public function __construct(
        Context $context,
        RegionFactory $regionFactory
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
            if ($data['code'] == $data['default_name']
                && !empty($data['code'])
                && !empty($data['default_name'])
            ) {
                $this->messageManager->addError(__('Code cannot be the same as default name'));
                return $this->_redirect('regions/region/index');
            }
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
