<?php

namespace Leeto\RegionManager\Ui\Component\Listing\Grid\Column\Country;

class CountryName extends \Magento\Ui\Component\Listing\Columns\Column {
    /** 
    * @var \Magento\Directory\Model\CountryFactory
    */
    protected $countryFactory;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface        $context
     * @param \Magento\Framework\View\Element\UiComponentFactory                  $uiComponentFactory
     * @param \Magento\Directory\Model\CountryFactory                             $countryFactory
     * @param array                                                               $components
     * @param array                                                               $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        array $components = [],
        array $data = []
    ){
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->countryFactory = $countryFactory;
    }

    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $countryName = $this->countryFactory->create()->loadByCode($item['country_id'])->getName();
                $item['country_name'] = $countryName;
            }
        }

        return $dataSource;
    }
}