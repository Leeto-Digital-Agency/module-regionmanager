<?php

namespace Leeto\RegionManager\Model;

use Leeto\RegionManager\Api\Data\CountryGridInterface;

class Country extends \Magento\Framework\Model\AbstractModel implements CountryGridInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'directory_country';

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
        $this->_init('Leeto\RegionManager\Model\ResourceModel\Country');
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