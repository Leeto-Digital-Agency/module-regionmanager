<?php

namespace Leeto\RegionManager\Model;

use Leeto\RegionManager\Api\Data\GridInterface;

class Region extends \Magento\Framework\Model\AbstractModel implements GridInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'directory_country_region';

    /**
     * @var string
     */
    protected $_cacheTag = 'directory_country_region';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'directory_country_region';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('Leeto\RegionManager\Model\ResourceModel\Region');
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

    /**
     * Get CountryId.
     *
     * @return varchar
     */
    public function getCountryId()
    {
        return $this->getData(self::COUNTRY_ID);
    }

    /**
     * Set CountryId.
     */
    public function setCountryId($countryId)
    {
        return $this->setData(self::COUNTRY_ID, $countryId);
    }

    /**
     * Get Code.
     *
     * @return varchar
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    /**
     * Set Code.
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * Get DefaultName.
     *
     * @return varchar
     */
    public function getDefaultName()
    {
        return $this->getData(self::DEFAULT_NAME);
    }

    /**
     * Set DefaultName.
     */
    public function setDefaultName($defaultName)
    {
        return $this->setData(self::DEFAULT_NAME, $defaultName);
    }
}