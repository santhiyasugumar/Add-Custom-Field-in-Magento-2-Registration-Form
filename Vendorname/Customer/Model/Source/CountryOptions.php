<?php

namespace Vendorname\Customer\Model\Source;

class CountryOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource 
{
    protected $eavConfig;

    public function __construct(
        \Magento\Directory\Model\Config\Source\Country $countryFactory,
    ){
        $this->_countryFactory = $countryFactory;
    }

    public function getAllOptions() 
    {
        return $this->_countryFactory->toOptionArray();
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