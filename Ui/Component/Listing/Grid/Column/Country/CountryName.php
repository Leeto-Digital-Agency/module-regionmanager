<?php

namespace Leeto\RegionManager\Ui\Component\Listing\Grid\Column\Country;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class CountryName extends Column
{
    /**
     * @var CountryFactory
     */
    protected $countryFactory;

    /**
     * @param ContextInterface     $context
     * @param UiComponentFactory   $uiComponentFactory
     * @param CountryFactory       $countryFactory
     * @param array                $components
     * @param array                $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CountryFactory $countryFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->countryFactory = $countryFactory;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $countryName = $this->countryFactory->create()->loadByCode($item['country_id'])->getName();
                $item['country_name'] = $countryName;
            }
        }

        return $dataSource;
    }
}
