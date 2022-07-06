<?php

namespace Vendorname\Customer\Model\Source;

class StateOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource 
{
    public function __construct(
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Model\Config\Source\Country $countryAllFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
    ){
        $this->_countryFactory = $countryFactory;
        $this->_countryAllFactory = $countryAllFactory;
        $this->resultPageFactory = $resultPageFactory;
    }

    public function getAllOptions() 
    {
        $countryAll = $this->_countryAllFactory->toOptionArray();
        $regions = [];
        foreach ($countryAll as $country) {
            $options =$this->_countryFactory->create()->setId(
                $country['value']
            )->getLoadedRegionCollection()->toOptionArray();
            
            foreach ($options as $region) {
                $regions[] = [
                    'value' => $region['value'],
                    'label' => $region['label'],
                    'title' => $region['title'],
                    'country_id' => $region['country_id'],
                ];
                
            }
        }
        return $regions;
    }
    
    public function getOptionText($value) 
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
}