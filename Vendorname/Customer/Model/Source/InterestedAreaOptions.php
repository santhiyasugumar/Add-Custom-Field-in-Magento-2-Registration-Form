<?php
namespace Vendorname\Customer\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class InterestedAreaOptions extends AbstractSource
{

/**
 * Retrieve All options
 *
 * @return array
 */
public function getAllOptions()
  {
    return [
        'microbiology' => [
            'label' => 'Microbiology',
            'value' => 'microbiology'
        ],
        'cell_biology' => [
            'label' => 'Cell Biology',
            'value' => 'cell_biology'
        ],
        'molecular_biology' => [
          'label' => 'Molecular Biology',
          'value' => 'molecular_biology'
        ],
        'hydroponics_soilless_farming' => [
            'label' => 'Hydroponics soilless farming',
            'value' => 'hydroponics_soilless_farming'
        ],
        'plant_tissue_culture' => [
            'label' => 'Plant Tissue culture',
            'value' => 'plant_tissue_culture'
        ],
        'chemicals' => [
            'label' => 'Chemicals & Biochemicals',
            'value' => 'chemicals'
        ],
        'lab_consumables' => [
            'label' => 'MolecularLab Consumables & Instruments',
            'value' => 'lab_consumables'
        ]
    ];
   }
}