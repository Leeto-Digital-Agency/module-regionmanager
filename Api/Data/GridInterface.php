<?php

namespace Leeto\RegionManager\Api\Data;

interface GridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'region_id';
    const COUNTRY_ID = 'country_id';
    const CODE = 'code';
    const DEFAULT_NAME = 'default_name';

    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId);

    /**
     * Get CountryId.
     *
     * @return varchar
     */
    public function getCountryId();

    /**
     * Set CountryId.
     */
    public function setCountryId($countryId);

    /**
     * Get Code.
     *
     * @return varchar
     */
    public function getCode();

    /**
     * Set Code.
     */
    public function setCode($code);

    /**
     * Get DefaultName.
     *
     * @return varchar
     */
    public function getDefaultName();

    /**
     * Set DefaultName.
     */
    public function setDefaultName($defaultName);
}