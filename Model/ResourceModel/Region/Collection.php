<?php

namespace Leeto\RegionManager\Model\ResourceModel\Region;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
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
        $this->_init('Leeto\RegionManager\Model\Region', 'Leeto\RegionManager\Model\ResourceModel\Region');
    }
}