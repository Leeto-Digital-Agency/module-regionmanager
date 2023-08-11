<?php

namespace Leeto\RegionManager\Controller\Adminhtml\Country;

use Magento\Backend\App\Action;
use Leeto\RegionManager\Model\RegionFactory;
use Leeto\RegionManager\Model\ResourceModel\Region\CollectionFactory;
use Magento\Backend\App\Action\Context;

class Save extends Action
{
    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @var CollectionFactory
     */
    protected $regionCollection;

    /**
     * @param Context           $context
     * @param RegionFactory     $regionFactory
     * @param CollectionFactory $regionCollection
     */
    public function __construct(
        Context $context,
        RegionFactory $regionFactory,
        CollectionFactory $regionCollection
    ) {
        parent::__construct($context);
        $this->regionFactory = $regionFactory;
        $this->regionCollection = $regionCollection;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $insertData = [];
        try {
            $rowData = $this->regionFactory->create();

            foreach ($data['country_regions'] as $key => $region) {
                $updated = false;
                $error = false;
                $insertData['country_id'] = $data['country_id'];
                if ($region['code'] == $region['default_name']) {
                    $this->messageManager->addError(__("Code cannot be the same as default name!", $region['code']));
                    $error = true;
                }
                $existingRecordsByCode = $this->regionCollection->create()
                    ->addFieldToFilter('country_id', $data['country_id'])
                    ->addFieldToFilter('code', $region['code']);
                //check if other records have the same code or default name when updating records and then throw an error
                if ($existingRecordsByCode->count() > 0
                    && (isset($region['region_id'])
                    && ($existingRecordsByCode->getFirstItem()->getRegionId() != $region['region_id']))
                ) {
                    $this->messageManager->addError(__("Record with '%1' code already exists!", $region['code']));
                    $error = true;
                } else {
                    $insertData['code'] = $region['code'];
                }
                $existingRecordsByDefaultName = $this->regionCollection->create()
                    ->addFieldToFilter('country_id', $data['country_id'])
                    ->addFieldToFilter('default_name', $region['default_name']);
                if ($existingRecordsByDefaultName->count() > 0
                    && (isset($region['region_id'])
                    && ($existingRecordsByDefaultName->getFirstItem()->getRegionId() != $region['region_id']))
                ) {
                    $this->messageManager->addError(
                        __(
                            "Record with '%1' Default Name already exists!",
                            $region['default_name']
                        )
                    );
                    $error = true;
                } else {
                    $insertData['default_name'] = $region['default_name'];
                }

                if (!isset($region['region_id'])
                    && ($existingRecordsByCode->count() > 0
                    || $existingRecordsByDefaultName->count() > 0)
                ) {
                    $this->messageManager->addError(__("Record already exists!"));
                    $error = true;
                }
                $rowData->setData($insertData);

                if (isset($region['region_id']) && $region['region_id']) {
                    $rowData->setEntityId($region['region_id']);
                }
                // when adding new records we need to check for duplicates
                if (!$error) {
                    $rowData->save();
                    $updated = true;
                }
                if (!isset($region['region_id']) || $region['region_id'] == '') {
                    $data['country_regions'][$key]['region_id'] = $rowData->getRegionId();
                }
            }

            // get all the regions submited when saving
            $dataRegions = array_map(function ($el) {
                if (isset($el['region_id'])) {
                    return $el['region_id'];
                }
                return '';
            }, $data['country_regions']);

            $regionsId = [];
            // get all the regions currently assigned to this country
            $countryOldRegions = $this->regionCollection->create()
                ->addFieldToFilter('country_id', $data['country_id'])
                ->getItems();
            foreach ($countryOldRegions as $reg) {
                $regionsId[] = $reg->getData('region_id');
            }
            // determine which regions have been deleted and remove them from the table
            $deletedRegionsIds = array_diff($regionsId, $dataRegions);
            foreach ($deletedRegionsIds as $key => $deletedRegionId) {
                $rowData->load($deletedRegionId);
                $rowData->delete();
                $updated = true;
            }
            if ($updated) {
                $this->messageManager->addSuccess(__('Row data has been successfully updated.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        if ($this->getRequest()->getParam('back')) {
            return $this->_redirect('regions/country/addrow', ['id' => $data['country_id']]);
        }
        return $this->_redirect('regions/country/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Leeto_RegionManager::save');
    }

    public function isAllowed()
    {
        return $this->_isAllowed();
    }
}
