<?php

namespace Leeto\RegionManager\Model\ResourceModel\Region;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Leeto\RegionManager\Model\Region as RegionModel;
use Leeto\RegionManager\Model\ResourceModel\Region as RegionResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'region_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            RegionModel::class,
            RegionResourceModel::class
        );
    }
}
