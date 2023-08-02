<?php

namespace Leeto\RegionManager\Api\Data;

interface CountryGridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'country_id';
    const FORM_NAMESPACE = 'countries_form';

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