<?php

namespace Leeto\RegionManager\Api\Data;

interface CountryGridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    public const ENTITY_ID = 'country_id';
    public const FORM_NAMESPACE = 'countries_form';

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
}
