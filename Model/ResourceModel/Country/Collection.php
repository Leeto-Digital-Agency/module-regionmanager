<?php

namespace Leeto\RegionManager\Model\ResourceModel\Country;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Leeto\RegionManager\Model\Country as CountryModel;
use Leeto\RegionManager\Model\ResourceModel\Country as CountryResourceModel;

class Collection extends AbstractCollection
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
        $this->_init(
            CountryModel::class,
            CountryResourceModel::class
        );
    }
}
