<?php

namespace Leeto\RegionManager\Controller\Adminhtml\Region;

use Magento\Backend\App\Action;
use Leeto\RegionManager\Model\RegionFactory;
use Magento\Backend\App\Action\Context;

class Delete extends Action
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

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $model = $this->regionFactory->create();

            // entity type check
            $model->load($id);
            try {
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The record was deleted successfully!'));
                return $resultRedirect->setPath('regions/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath(
                    'regions/*/addrow',
                    ['id' => $this->getRequest()->getParam('id')]
                );
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find an attribute to delete.'));
        return $resultRedirect->setPath('regions/*/');
    }
}
