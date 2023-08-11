<?php

namespace Leeto\RegionManager\Model;

use Leeto\RegionManager\Api\Data\CountryGridInterface;
use Magento\Framework\Model\AbstractModel;
use Leeto\RegionManager\Model\ResourceModel\Country as CountryResourceModel;

class Country extends AbstractModel implements CountryGridInterface
{
    /**
     * CMS page cache tag.
     */
    public const CACHE_TAG = 'directory_country';

    /**
     * @var string
     */
    protected $_cacheTag = 'directory_country';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'directory_country';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(CountryResourceModel::class);
    }
    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }
}
