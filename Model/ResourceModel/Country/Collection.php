<?php

namespace Leeto\RegionManager\Model\ResourceModel\Country;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'country_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('Leeto\RegionManager\Model\Country', 'Leeto\RegionManager\Model\ResourceModel\Country');
    }
}