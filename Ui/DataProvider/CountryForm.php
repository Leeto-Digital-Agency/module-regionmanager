<?php

namespace Leeto\RegionManager\Ui\DataProvider;

use Leeto\RegionManager\Model\ResourceModel\Country\CollectionFactory;
use Leeto\RegionManager\Model\ResourceModel\Region\CollectionFactory as RegionCollection;

class CountryForm extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
    * @var RegionCollection
    */
    protected $regionCollection;

    /**
    * @var CollectionFactory
    */
    protected $collection;

    /**
     * @var                                                                       $name
     * @var                                                                       $primaryFieldName
     * @var                                                                       $requestFieldName
     * @param \Leeto\RegionManager\Model\ResourceModel\Country\CollectionFactory  $collectionFactory
     * @param \Leeto\RegionManager\Model\ResourceModel\Region\CollectionFactory   $regionCollection
     * @param array                                                               $meta                                                       
     * @param array                                                               $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RegionCollection $regionCollection,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collection = $collectionFactory->create();
        $this->regionCollection = $regionCollection->create();
    }

    public function getData()
    {
        $result = [];
        $countryRegions = [];
        foreach ($this->collection->getItems() as $item) {
            $countryCollection = $this->regionCollection->addFieldToFilter('country_id', $item->getCountryId());
            // prepare data
            foreach ($countryCollection as $countryItem) {
                $countryRegions[] = [
                    'code' => $countryItem->getCode(),
                    'default_name' => $countryItem->getDefaultName(),
                    'region_id' => $countryItem->getRegionId(),
                    'country_id' => $countryItem->getCountryId()
                ];
            }
            $item->setData('country_regions', $countryRegions);
            $result[$item->getId()] = $item->getData();
        }
        return $result;
    }
}
